<?php
/**
 * Created by PhpStorm.
 * Date: 17-3-6
 * Time: 下午4:55
 */
use Symfony\Component\Yaml\Yaml;

if (YII_DEBUG) {
    $file = __DIR__ . '/../../testproposal.yaml';
}else {
    $file = __DIR__ . '/../../proposal.yaml';
}

$config = Yaml::parse(file_get_contents($file));

$mongodbConfig = [];
$mongodbConfig['class'] = '\yii\mongodb\Connection';

if (YII_DEBUG){
    $mongodbConfig['dsn'] = "mongodb://{$config['mongo']['db_user']}:{$config['mongo']['db_pass']}@{$config['mongo']['db_address']}:{$config['mongo']['db_port']}/admin";
}else {
    $mongodbConfig['dsn'] = "mongodb://{$config['mongo']['db_user']}:{$config['mongo']['db_pass']}@{$config['mongo']['db_address']}:{$config['mongo']['db_port']}/admin";
}


return [
    'language'=>'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'mongodb' => $mongodbConfig,
        'cache' => [
            'class' => 'yii\mongodb\Cache',
            'db' => 'mongodb',
            'cacheCollection' => 'asset_cache',
        ],
    ],
];
