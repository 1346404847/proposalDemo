<?php
use admin\assets\freeImagination\FreeImaginationAsset;

FreeImaginationAsset::register($this);

$this->title          = '内容列表';

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

    <div class="card-body card-padding">

            <div class="col-sm-4" style="margin-left: 90%;">
                <button class="btn btn-primary btn-sm m-t-5 waves-effect waves-button waves-float" id="export_answer" type="button">导出简答题</button>
            </div>

    </div>
    <table id="data-table-command" class="table table-striped table-vmiddle">
        <thead>
            <tr>
                <th data-column-id="uid">uid</th>
                <th data-column-id="content" data-formatter="content">答题内容</th>
                <th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr></tr>
        </tbody>
    </table>
</div>
<script>
    window.titleId     = '<?php echo $questionnaireId;?>';
    window.questionnaireConfId = '<?php echo $questionnaireConfId;?>';
    window.vip         = '<?php echo $vip;?>';
</script>
