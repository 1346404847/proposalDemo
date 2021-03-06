<?php
/**
 * Game Client class file
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
 * Class Game
 * @package clients\ucenter\services
 */
class Game extends Base
{
    const URL_PATH = 'game/rpc';

    /**
     * 根据游戏Id获取游戏信息
     *
     * @param number $id id
     *
     * @return array
     * @throws Exception
     */
    public static function getGameById($id)
    {
        $game = new self();
        $params = ['id' => $id];
        return $game->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }


    /**
     * 获取所有数据
     *
     * @return array
     * @throws Exception
     */
    public static function getAll()
    {
        $game = new self();
        $params = [];
        return $game->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }

    /**
     * 根据游戏Id获取平台信息集合
     *
     * @param number $id 游戏Id
     *
     * @return array
     * @throws Exception
     */
    public static function getPlatformsById($id)
    {
        $game = new self();
        $params = ['id' => $id];
        return $game->getClient()->postByCurl(__FUNCTION__, $params, self::URL_PATH);
    }

}