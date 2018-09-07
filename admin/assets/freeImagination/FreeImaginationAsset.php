<?php
namespace admin\assets\freeImagination;

use yii\web\AssetBundle;

class FreeImaginationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $css      = [
    ];
    public $js       = [
        'js/free/list.js'
    ];
    public $depends  = [
        'admin\assets\AppAsset'
    ];
}