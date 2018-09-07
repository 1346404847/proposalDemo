<?php
use admin\assets\other\ListAsset;

ListAsset::register($this);

$this->title          = '问卷问卷配置列表';

$choose               = Yii::$app->request->get("choose");
$questionnaireId      = Yii::$app->request->get("id");
$questionnaireConfId  = Yii::$app->request->get("questionnaireConfId");
$vip = Yii::$app->request->get("vip");
?>

<div class="block-header">
    <h2>答题内容</h2>
</div>
<div class="card">
    <div class="card-header">
        <h2>答题内容列表 <small></small></h2>
    </div>
    <table id="data-table-command" class="table table-striped table-vmiddle">
        <thead>
            <tr>
                <th data-column-id="role">玩家角色</th>
                <th data-column-id="content" data-formatter="content">答题内容</th>
            </tr>
        </thead>
        <tbody>
            <tr></tr>
        </tbody>
    </table>
</div>
<script>
    window.otherChoose = '<?php echo $choose;?>';
    window.titleId     = '<?php echo $questionnaireId;?>';
    window.questionnaireConfId = '<?php echo $questionnaireConfId;?>';
    window.vip         = '<?php echo $vip;?>';
</script>
