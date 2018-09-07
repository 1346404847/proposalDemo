<?php
namespace admin\assets\other;

use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $css      = [
    ];
    public $js       = [
        'js/other/list.js'
    ];
    public $depends  = [
        'admin\assets\AppAsset'
    ];
}