<?php

switch ($_SERVER['HTTP_HOST']) {
    case 'proposal.topjoy.com':
    case 'apiproposal.topjoy.com':
        $envStr = 'prod';
        $debugBool = false;
        break;
    case 'www.customer.com':
    case 'www.questionnaire.com':
    case 'proposal.youle.game:8001':
        $envStr = 'dev';
        $debugBool = true;
        break;

    default:
        exit('域名或端口被改了, 请联系管理员处理');
}

defined('YII_DEBUG') or define('YII_DEBUG', $debugBool);
defined('YII_ENV') or define('YII_ENV', $envStr);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
