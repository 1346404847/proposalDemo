<?php
/**
 * Created by PhpStorm.
 * Date: 15-11-18
 * Time: 16:31
 */

namespace admin\assets\question;

use yii\web\AssetBundle;


class QuestionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/jtable.js'
    ];
    public $depends = [
        'admin\assets\AppAsset',
    ];
}