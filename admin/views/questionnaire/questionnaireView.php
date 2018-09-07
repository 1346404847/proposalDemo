<?php
use admin\models\QuestionnaireConfig;

$this->title = "答卷详情";
?>
<div class="block-header">
    <h2>问卷详情</h2>

    <ul class="actions">
    </ul>
</div>
<div class="card">
    <div class="card-header">
        <h2><?= $row->title ?> <small><?= $row->subtitle ?></small></h2>
    </div>
    <div class="card-body card-padding">
            <div class="card-body card-padding">
                <?php foreach($data as $v): ?>
                    <p class="c-black f-500 m-b-20"><?= $v['content'] ?></p>
                    <?php if($v["type"] == QuestionnaireConfig::TYPE_MULTISELECT):?>
                        <?php foreach($v['Choose'] as $v1):?>
                            <div class="checkbox m-b-15">
                                <label>
                                    <input type="checkbox" value="">
                                    <i class="input-helper"></i>
                                    <?= $v1 ?>
                                </label>
                            </div>
                       <?php endforeach;?>

                    <?php elseif($v['type'] == QuestionnaireConfig::TYPE_RADIO): ?>
                        <?php foreach($v['Choose'] as $v1):?>
                            <div class="radio m-b-15">
                                <label>
                                    <input type="radio" name="<?= $v['type']?>" >
                                    <i class="input-helper"></i>
                                    <?= $v1 ?>
                                </label>
                            </div>
                        <?php endforeach;?>

                    <?php elseif($v['type'] == QuestionnaireConfig::TYPE_ANSWER): ?>
                            <div class="m-b-15">
                                <label>
                                    <textarea class="" rows="8" cols="50"></textarea>
                                </label>
                            </div>
                    <?php endif;?>
                <?php endforeach;?>

            </div>

        <div class="form-group">
            <div class="col-sm-offset-2">
                <button class="btn btn-primary btn-sm waves-effect waves-button waves-float" id="repeat_submit" type="button" onclick="window.close()">关闭</button>
            </div>
        </div>
    </div>
</div>







