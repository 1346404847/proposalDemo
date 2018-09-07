<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'boa5hwuuG_1MF6GplUuXNjrh3Sx-6dMj',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://172.16.110.248:53100/customer_service'
        ],
    ]
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = ['192.168.33.*'];
    $config['modules']['debug']['panels']['mongodb']['class'] = 'yii\\mongodb\\debug\\MongoDbPanel';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    $config['modules']['gii']['allowedIPs'] = ['192.168.33.*'];
    $config['modules']['gii']['generators']['mongoDbModel']['class'] = 'yii\\mongodb\\gii\\model\\Generator';
}

return $config;
