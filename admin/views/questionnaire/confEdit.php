<?php
use admin\assets\questionnaire\ConfEditAsset;

ConfEditAsset::register($this);
$this->title = "修改问卷配置";
?>

<div class="block-header">
    <h2>修改问卷配置</h2>
    <ul class="actions">
    </ul>
</div>
<div class="card">
    <form class="form-horizontal" role="form" id="myform">
        <div class="card-header">
            <h2>
                修改问卷配置
                <small>修改问卷配置</small>
            </h2>
        </div>
        <div class="card-body card-padding">
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">问卷配置文件</label>

                <div class="col-sm-10">
                    <div class="fg-line">
                        <input type="file" class="form-control" id="upload" name="confFile" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button class="btn btn-primary btn-sm" id="submit">修改问卷配置</button>
                </div>
            </div>
            <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->csrfToken; ?>">
            <input type="hidden" name="_id" value="<?= $_id; ?>"/>
        </div>
    </form>
</div>
