<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace admin\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //vendor css
        'vendors/fullcalendar/fullcalendar.css',
        'vendors/animate-css/animate.min.css',
        'vendors/sweet-alert/sweet-alert.min.css',
        'vendors/material-icons/material-design-iconic-font.min.css',
        'vendors/socicon/socicon.min.css',
        'vendors/bootgrid/jquery.bootgrid.min.css',
        'vendors/summernote/summernote.css',
        //CSS
        'css/app.min.1.css',
        'css/app.min.2.css',
    ];
    public $js = [
        'js/jquery-2.1.1.min.js',
        'js/bootstrap.min.js',

        'vendors/flot/jquery.flot.min.js',
        'vendors/flot/jquery.flot.resize.min.js',
        'vendors/flot/plugins/curvedLines.js',
        'vendors/sparklines/jquery.sparkline.min.js',
        'vendors/easypiechart/jquery.easypiechart.min.js',

        'vendors/fullcalendar/lib/moment.min.js',
        'vendors/fullcalendar/fullcalendar.min.js',
        'vendors/simpleWeather/jquery.simpleWeather.min.js',
        'vendors/auto-size/jquery.autosize.min.js',
        'vendors/nicescroll/jquery.nicescroll.min.js',
        'vendors/waves/waves.min.js',
        'vendors/bootstrap-growl/bootstrap-growl.min.js',
        'vendors/sweet-alert/sweet-alert.min.js',
        'vendors/bootgrid/jquery.bootgrid.min.js',
        'vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
        'vendors/input-mask/input-mask.min.js',
        'vendors/noUiSlider/jquery.nouislider.all.min.js',
        'vendors/chosen/chosen.jquery.min.js',
        'vendors/summernote/summernote.min.js',


//        'js/flot-charts/curved-line-chart.js',
//        'js/flot-charts/line-chart.js',
//        'js/charts.js',

        'js/functions.js',
//        'js/jtable.js'
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
