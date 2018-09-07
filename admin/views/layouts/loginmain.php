<?php
use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (YII_DEBUG == false) {?>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <?php } ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="/js/jquery-2.1.1.min.js"></script>
</head>
<body>
<header id="header">
<ul class="header-inner">
<!-- <li id="menu-trigger" data-trigger="#sidebar"> -->
<!--     <div class="line-wrap"> -->
<!--         <div class="line top"></div> -->
<!--         <div class="line center"></div> -->
<!--         <div class="line bottom"></div> -->
<!--     </div> -->
<!-- </li> -->

<li class="logo hidden-xs">
    <a href="<?php echo Url::toRoute('question/index') ?>">玩家反馈信息平台</a>
</li>

<li class="pull-right">
<!-- <ul class="top-menu"> -->
<!-- <li id="toggle-width"> -->
<!--     <div class="toggle-switch"> -->
<!--         <input id="tw-switch" type="checkbox" hidden="hidden"> -->
<!--         <label for="tw-switch" class="ts-helper"></label> -->
<!--     </div> -->
<!-- </li> -->

</ul>
</li>
</ul>

<!-- Top Search Content -->
<div id="top-search-wrap">
    <input type="text">
    <i id="top-search-close">&times;</i>
</div>
</header>
    <?php $this->beginBody() ?>
    <section id="main">
        <section id="content">
        <div class="container">
            <?= $content ?>
        </div>
        </section>
    </section>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
$(function(){
    $("body").removeClass("sw-toggled");
})
</script>
