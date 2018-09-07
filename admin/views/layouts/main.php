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
<li id="menu-trigger" data-trigger="#sidebar">
    <div class="line-wrap">
        <div class="line top"></div>
        <div class="line center"></div>
        <div class="line bottom"></div>
    </div>
</li>

<li class="logo hidden-xs">
    <a href="<?php echo Url::toRoute('question/index') ?>"><?php if(isset(Yii::$app->session['game'])){ echo ucfirst(Yii::$app->session['game']);}?> 玩家反馈信息平台</a>
</li>

<li class="pull-right">
<ul class="top-menu">
<?php if(isset(Yii::$app->session['gameAll']) && (count(Yii::$app->session['gameAll'])>1 || in_array('game.0', Yii::$app->session['gameAll']))) {?>
<li id="toggle-width">
    <div class="toggle-switch">
    <a href="<?php echo Url::toRoute('game/game-list') ?>" class='btn btn-info' >返回游戏列表</a>
    </div>
</li>
<?php }?>
<li id="toggle-width">
    <div class="toggle-switch">
        <input id="tw-switch" type="checkbox" hidden="hidden">
        <label for="tw-switch" class="ts-helper"></label>
    </div>
</li>
<li class="dropdown">
    <a data-toggle="dropdown" class="tm-settings" href="#"></a>
    <ul class="dropdown-menu dm-icon pull-right">
        <li>
            <a><i class="md md-person"></i><?php echo Yii::$app->session['truename'] ?></a>
        </li>
        <li>
            <a data-action="clear-localstorage" href="<?php echo Url::toRoute('user/logout') ?>"><i class="md md-delete"></i>logout</a>
        </li>
    </ul>
</li>
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
        <aside id="sidebar">
            <div class="sidebar-inner">
                <div class="si-inner">
                    <div class="profile-menu">
                        <a href="index.html">
                            <div class="profile-pic">
                                <img alt="" src="/img/profile-pics/4.jpg">
                            </div>

                            <div class="profile-info">
                                <?php echo Yii::$app->session['truename']; ?>
                                <i class="md md-arrow-drop-down"></i>
                            </div>
                        </a>

                        <ul class="main-menu">
                            <li>
                                <a href="#"><i class="md md-person"></i><?php ?></a>
                            </li>
                            <li>
                                <a href="<?php echo Url::toRoute('user/logout') ?>"><i class="md md-settings-power"></i>Logout</a>
                            </li>
                        </ul>
                    </div>

                    <ul class="main-menu">
                        <li <?php if(Yii::$app->controller->id == 'question'):;?>class="active"<?php endif?>>
                            <a href="<?php echo Url::toRoute('question/index') ?>"><i class="md md-home"></i>反馈列表</a>
                        </li>
                        <li <?php if(Yii::$app->controller->id == 'questionnaire' && Yii::$app->controller->action->id == 'index'):;?>class="active"<?php endif?>>
                            <a href="<?php echo Url::toRoute('questionnaire/index') ?>"><i class="md md-home"></i>问卷调查</a>
                        </li>
                        <li <?php if(Yii::$app->controller->id == 'questionnaire' && Yii::$app->controller->action->id == 'conf-index'):;?>class="active"<?php endif?>>
                            <a href="<?php echo Url::toRoute('/questionaire-list/conf-list') ?>"><i class="md md-view-list"></i>问卷配置列表</a>
                        </li>

                        <li <?php if(Yii::$app->controller->id == 'statistics' && Yii::$app->controller->action->id == 'count'):;?>class="active"<?php endif?>>
                            <a href="<?php echo Url::toRoute('/statistics/count') ?>"><i class="md md-view-list"></i>统计配置列表</a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
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
