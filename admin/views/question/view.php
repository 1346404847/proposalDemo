<?php
use \yii\helpers\Html;
\admin\assets\question\QuestionAsset::register($this);
?>
<div class="block-header">
    <h2>问题处理</h2>
    <ul class="actions"></ul>
</div>
<div class="card">
    <div class="card-header">
        <h2>最近问题列表 <small>提供最近此玩家的列表</small></h2>
    </div>
    <div class="table-responsive" style="overflow: hidden;" tabindex="1">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>提问时间</th>
                    <th>处理时间</th>
                    <th>标题</th>
                    <th>内容</th>
                    <th>回复</th>
                    <th>玩家咨询类别</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recent_list as $value): ?>
            <?php if ((string) $value->_id == $id) { continue; } ?>
            <tr>
                <td><?php echo date('Y-m-d H:i:s', $value->create_time); ?></td>
                <td><?php echo isset($value->op_datetime) && !empty($value->op_datetime) ? date('Y-m-d H:i:s', $value->op_datetime) : '未处理'; ?></td>
                <td><?php echo Html::encode($value->title); ?></td>
                <td><?php echo Html::encode($value->content); ?></td>
                <td><?php echo isset($value->op_content) ? Html::encode($value->op_content) : ""; ?></td>
                <td><?php echo isset($value->cat_first) ? Html::encode($value->cat_first.'-'.$value->cat_second) : "未选择"; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h2>反馈处理 <small>处理玩家反馈.</small></h2>
    </div>
    <form action="/question/view" method="post">
    <div class="card-body card-padding">
        <div class="table-responsive" style="overflow: hidden;" tabindex="3">
            <table class="table table-hover">
                <thead></thead>
                <tbody>
                    <tr>
                        <td>pid</td>
                        <td><?php echo Html::encode($pid); ?></td>
                    </tr>
                    <tr>
                        <td>vip</td>
                        <td><?php echo Html::encode($vip); ?></td>
                    </tr>
                    <tr>
                        <td>角色名</td>
                        <td><?php echo Html::encode($role); ?></td>
                    </tr>
                    <tr>
                        <td>区服</td>
                        <td><?php echo Html::encode($area_service); ?></td>
                    </tr>
                    <tr>
                        <td>uid</td>
                        <td><?php echo Html::encode($uid); ?></td>
                    </tr>
                    <tr>
                        <td>版本</td>
                        <td><?php echo Html::encode($version); ?></td>
                    </tr>
                    <tr>
                        <td>客户端版本</td>
                        <td><?php echo Html::encode($package_version); ?></td>
                    </tr>
                    <tr>
                        <td>标题</td>
                        <td><?php echo Html::encode($title); ?></td>
                    </tr>
                    <tr>
                        <td>内容</td>
                        <td><?php echo Html::encode($content); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="c-black f-500 m-b-20">选择分类</p>
        <div class='form-group'>
            <label class="col-sm-1">一级分类</label>
            <div class="col-sm-1">
                <select id="cat_first" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($cat_first_list as $v):?>
                        <option value="<?= $v["cat_id"] ?>" <?php if($v['cat_id'] == $cat_first) echo 'selected';?> ><?= $v["cat_name"] ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <label class="col-sm-1">二级分类</label>
            <div class="col-sm-1">
                <select id="cat_second" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($cat_second_list as $v):?>
                        <option value="<?= $v['cat_id'] ?>" <?php if($v['cat_id'] == $cat_second) echo 'selected';?> ><?= $v['cat_name'] ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div style='clear:both'></div>
        <br>
        <p class="c-black f-500 m-b-20">用户反馈</p>
        <div class="form-group">
            <div class="fg-line">
                <textarea class="form-control" rows="5" placeholder="请输入内容" name="op_content" id="op_content"><?php echo Html::encode($op_content); ?></textarea>
                <input type="hidden" id="m_id" name="m_id" value="<?php echo $id; ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2">
                <button class="btn btn-primary btn-sm waves-effect waves-button waves-float" id="repeat_submit" type="button">回复</button>
            </div>
        </div>
    </div>
    </form>
</div>
<script>
    var category = <?= $category ?>;
</script>
