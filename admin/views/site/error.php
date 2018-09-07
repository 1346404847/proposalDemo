<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>

    <!-- Vendor CSS -->
    <link href="/vendors/animate-css/animate.min.css" rel="stylesheet">
    <link href="/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
    <link href="/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="/vendors/socicon/socicon.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="/css/app.min.1.css" rel="stylesheet">
    <link href="/css/app.min.2.css" rel="stylesheet">
</head>

<body class="four-zero-content">
<div class="four-zero">
    <h2>......</h2>
    <small><?= nl2br(Html::encode($message)) ?>Nah.. it's 404</small>

    <footer>
        <a href="/question/list"><i class="md md-arrow-back"></i></a>
        <a href="/question/list"><i class="md md-home"></i></a>
    </footer>
</div>
</body>
</html>
