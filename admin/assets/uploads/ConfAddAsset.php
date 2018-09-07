<?php
namespace admin\assets\uploads;

use yii\web\AssetBundle;

class ConfAddAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/uploads/add.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}