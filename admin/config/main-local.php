<?php

/**
 * Created by PhpStorm.
 * Date: 17-3-6
 * Time: 下午4:55
 */
use Symfony\Component\Yaml\Yaml;

if (YII_DEBUG) {
    $file = __DIR__ . '/../../testproposal.yaml';
} else {
    $file = __DIR__ . '/../../proposal.yaml';
}

$config = Yaml::parse(file_get_contents($file));

$mongodbConfig          = [];
$mongodbConfig['class'] = '\yii\mongodb\Connection';

if (YII_DEBUG) {
    $mongodbConfig['dsn'] = "mongodb://{$config['mongo']['db_user']}:{$config['mongo']['db_pass']}@{$config['mongo']['db_address']}:{$config['mongo']['db_port']}/admin";
} else {
    $mongodbConfig['dsn'] = "mongodb://{$config['mongo']['db_user']}:{$config['mongo']['db_pass']}@{$config['mongo']['db_address']}:{$config['mongo']['db_port']}/admin";
}


$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'boa5hwuuG_1MF6GplUuXNjrh3Sx-6dMj',
        ],
        'mongodb' => $mongodbConfig,
    ],
];



if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]                                    = 'debug';
    $config['modules']['debug']['class']                      = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs']                 = ['192.168.33.*'];
    $config['modules']['debug']['panels']['mongodb']['class'] = 'yii\\mongodb\\debug\\MongoDbPanel';

    $config['bootstrap'][]                                           = 'gii';
    $config['modules']['gii']['class']                               = 'yii\gii\Module';
    $config['modules']['gii']['allowedIPs']                          = ['192.168.33.*'];
    $config['modules']['gii']['generators']['mongoDbModel']['class'] = 'yii\\mongodb\\gii\\model\\Generator';
}

return $config;
