<?php
use admin\models\QuestionnaireConfig;
$this->title = "问卷详情";
?>
<div class="block-header">
    <h2>问卷详情</h2>
    <ul class="actions"></ul>
</div>
<div class="card">
    <div class="card-header">
        <h2><?=$confRow->title?>
            <small><?=$confRow->subtitle?></small>
        </h2>
    </div>
    <div class="card-body card-padding">
        <table class="table">
            <tr>
                <td>openid</td>
                <td><?=$row['openid']?></td>
            </tr>
            <tr>
                <td>uid</td>
                <td><?=$row['uid']?></td>
            </tr>
            <tr>
                <td>vip</td>
                <td><?=$row['vip']?></td>
            </tr>
            <tr>
                <td>角色名</td>
                <td><?=$row['role']?></td>
            </tr>
            <tr>
                <td>平台</td>
                <td><?=$row['platform']?></td>
            </tr>
            <tr>
                <td>区服</td>
                <td><?=$row['area_service']?></td>
            </tr>
            <tr>
                <td>答题版本</td>
                <td><?=$row['version']?></td>
            </tr>
            <tr>
                <td>创建时间</td>
                <td><?=$row['create_time']?></td>
            </tr>
        </table>
    </div>
    <div class="card-body card-padding">
        <div class="card-body card-padding">
            <?php foreach ($data as $v):?>
                <p class="c-black f-500 m-b-20"><?=$v['content']?></p>
                <?php if ($v["type"] == QuestionnaireConfig::TYPE_MULTISELECT):?>
                    <?php foreach ($v['Choose'] as $k1 => $v1): ?>
                        <?php
                            $arr = explode(':',$v1);
                        ?>
                        <div class="checkbox m-b-15 disabled">
                            <label>
                                <input type="checkbox" <?php if(count($arr) >= 2) echo $arr[1];?> disabled />
                                <i class="input-helper"></i>
                                <?=$arr[0];?>
                                <?php
                                    if(count($arr) == 3){
                                        if(isset($arr[2]) && empty($arr[2])){
                                            echo "   ";
                                        }else{
                                            echo $arr[2];
                                        }
                                    }
                                ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($v['type'] == QuestionnaireConfig::TYPE_RADIO):?>
                    <?php foreach ($v['Choose'] as $k1 => $v1): ?>
                        <?php
                            $arr = explode(':',$v1);
                        ?>
                        <div class="radio m-b-15 disabled">
                            <label>
                                <input type="radio" name="<?=$v['id']?>" <?php if(count($arr) >= 2) echo $arr[1];?> disabled />
                                <i class="input-helper"></i>
                                <?=$arr[0];?>
                                <?php if(count($arr) == 3) echo $arr[2];?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($v['type'] == QuestionnaireConfig::TYPE_ANSWER):?>
                    <div class="m-b-15">
                        <label>
                            <textarea class="" rows="8" cols="50" disabled><?=isset($v['answer']) && !empty($v['answer']) ? trim($v['answer']) : " ";?></textarea>
                        </label>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2">
                <button class="btn btn-primary btn-sm waves-effect waves-button waves-float" id="repeat_submit"
                        type="button" onclick="window.close()">关闭
                </button>
            </div>
        </div>
    </div>
</div>