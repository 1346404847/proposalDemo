<?php
/**
 * Common Client class file
 *
 * App Confidential
 * All rights reserved.
 *
 * PHP version 5
 *
 * @category System
 * @package  ucenter
 * @license  http://www.server.com/license   Software Distribution
 * @link     http://www.server.com
 */
namespace clients\ucenter\services;
use clients\ucenter\lib\Base;
use clients\ucenter\lib\Exception;


/**
 * Class Common
 * @package clients\ucenter\services
 */
class Common extends Base
{
    const URL_PATH = 'common/rpc';

    /**
     * 获取登录地址
     *
     * @param string $callback callback
     *
     * @return string
     * @throws Exception
     */
    public static function loginUrl($callback)
    {
        $common = new self();
        $params = [];
        $params['callback'] = $callback;
        return $common->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }

    /**
     * 获取登出地址
     *
     * @param string $callback callback
     *
     * @return string
     * @throws Exception
     */
    public static function logoutUrl($callback = '')
    {
        $common = new self();
        $params = [];

        if ($callback) {
            $params['callback'] = $callback;
        }
        return $common->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }

    /**
     * 检验token
     *
     * @param string $token token字符串
     *
     * @return mixed
     * @throws Exception
     */
    public static function checkToken($token)
    {
        $common = new self();
        $params = ['token' => $token];
        return $common->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }

}