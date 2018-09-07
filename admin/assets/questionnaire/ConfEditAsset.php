<?php
namespace admin\assets\questionnaire;

use yii\web\AssetBundle;

class ConfEditAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/questionnaire/confEdit.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}