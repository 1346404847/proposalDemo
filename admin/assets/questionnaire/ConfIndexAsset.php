<?php
namespace admin\assets\questionnaire;

use yii\web\AssetBundle;

class ConfIndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/questionnaire/confIndex.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}