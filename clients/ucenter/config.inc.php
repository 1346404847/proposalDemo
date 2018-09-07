<?php
/**
 * Config inc file
 *
 * All rights reserved.
 *
 * PHP version 5
 *
 * @category System
 * @package  ucenter
 */
if(YII_ENV_PROD) {//正式
    $config = [
        'api_url' => 'https://apiucenter.topjoy.com',
        'api_key' => '9173900023',
        'api_secret_key' => 'ea88b7ef6208c3ddd470bd743b03ba48',
        'log_path' => dirname(__FILE__).'/logs'
    ];
}else { //测试
    $config = [
        'api_url' => 'https://apiucenter.topjoy.com',
        'api_key' => '9173900023',
        'api_secret_key' => 'ea88b7ef6208c3ddd470bd743b03ba48',
        'log_path' => dirname(__FILE__).'/logs'
    ];
}
