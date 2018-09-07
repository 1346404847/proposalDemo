<?php
namespace admin\assets\statistics;

use yii\web\AssetBundle;

class ConfAddAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/statistics/add.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}