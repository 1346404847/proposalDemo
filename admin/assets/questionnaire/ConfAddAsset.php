<?php
namespace admin\assets\questionnaire;

use yii\web\AssetBundle;

class ConfAddAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/questionnaire/confAdd.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}