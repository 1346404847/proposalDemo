<?php
namespace admin\assets\questionnaire;

use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/questionnaire/list.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}