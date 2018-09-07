<?php
namespace admin\assets\questionairelist;

use yii\web\AssetBundle;

class ConfListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/questionaireList/confIndex.js'
    ];
    public $depends = [
        'admin\assets\AppAsset'
    ];
}