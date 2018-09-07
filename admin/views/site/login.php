<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KOF玩家反馈信息平台</title>

    <!-- Vendor CSS -->
    <link href="/vendors/animate-css/animate.min.css" rel="stylesheet">
    <link href="/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
    <link href="/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="/vendors/socicon/socicon.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="/css/app.min.1.css" rel="stylesheet">
    <link href="/css/app.min.2.css" rel="stylesheet">
</head>

<body class="login-content">
<!-- Login -->
<div class="lc-block toggled" id="l-login">
    <form action="/site/login" class="form-horizontal" method="post">
    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="md md-person"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Username" name="LoginForm[username]">
        </div>
    </div>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="md md-accessibility"></i></span>
        <div class="fg-line">
            <input type="password" name="LoginForm[password]" class="form-control" placeholder="Password">
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="checkbox">
        <label>
            <input type="checkbox" value="">
            <i class="input-helper"></i>
            Keep me signed in
        </label>
    </div>

    <a href="#" class="btn btn-login btn-danger btn-float"><i class="md md-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-register" class="bgm-red">Register</li>
<!--        <li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>-->
    </ul>
</div>

<!-- Register -->
<div class="lc-block" id="l-register">
    <a href="#" class="btn btn-login btn-danger btn-float"><i class="md md-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
<!--        <li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>-->
    </ul>
</div>

<!-- Forgot Password -->
<div class="lc-block" id="l-forget-password">
    <p class="text-left">KOF</p>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="md md-email"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Email Address">
        </div>
    </div>

    <a href="/site/login" class="btn btn-login btn-danger btn-float"><i class="md md-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
        <li data-block="#l-register" class="bgm-red">Register</li>
    </ul>
</div>
<input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken(); ?>">
</form>
<!-- Javascript Libraries -->
<script src="/js/jquery-2.1.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script src="/vendors/waves/waves.min.js"></script>

<script src="/js/functions.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".btn-login").click(function(){
            $('form').submit();
        })
    })
</script>
</body>
</html>
