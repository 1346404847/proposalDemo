<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/badword.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'customer_service',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Chongqing',
    'modules' => [
        'v1' => [
            'class' => 'admin\modules\v1\Module',
            'basePath' => '@app/modules/v1'
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/question',
                        'v1/questions',
                        'v1/questionnaire'
                    ],
                    'pluralize' => false,
                    'except' => ['delete'],
                ],
                '<controller:\w+\-\w+|\w+>/<action:\w+\-\w+|\w+>' => '<controller>/<action>',
                '/' => 'question/index',
            ]
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'user' => [
            'identityClass' => 'admin\models\Users',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\mongodb\Session'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cache' => [
            'class' => 'yii\mongodb\Cache',
            'db' => 'mongodb',
            'cacheCollection' => 'asset_cache',
        ],
    ],
    'params' => $params,
];
