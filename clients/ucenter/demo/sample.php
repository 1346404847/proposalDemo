<?php
require "../autoload.php";
use clients\ucenter\services\Common;
use clients\ucenter\services\User;
use clients\ucenter\services\Game;
use clients\ucenter\services\Platform;

################################### Common Service Start ###############################################################
//获取登录Url
$loginUrl = Common::loginUrl('http://admin.tds..com/index.php?r=ucenter/callback');
echo $loginUrl . "\n";

//获取登出Url
$logoutUrl = Common::logoutUrl('http://admin.tds.server.com/index.php?r=ucenter/callback');
echo $logoutUrl . "\n";

//验证登录Token
$result = Common::checkToken('abcdedf');
echo  $result ? 1 : 0 . "\n";
################################## Common Service End ##################################################################
################################## User Service Start ##################################################################
//根据用户ID,获取用户信息
$user = User::getUserById(1);
print_r($user);

//获取用户的所有角色
$roles = User::getRoles(291, 0, 0, false);
print_r($roles);

//获取用户的所有功能列表
$functions = User::getFunctionPaths(85);
print_r($functions);
################################# User Service End     #################################################################
################################## Game Services Start #################################################################
//根据游戏Id, 获取游戏信息
$game = Game::getGameById(1);
print_r($game);

//获取所有游戏数据
$allGames = Game::getAll();
print_r($allGames);

//根据游戏ID获取平台信息集合
$platforms = Game::getPlatformsById(1);
print_r($platforms);
################################# Game Services End ###################################################################
################################# Platform Services Start #############################################################
//根据平台ID获取平台信息
$platform = Platform::getPlatformById(1);
print_r($platform);
################################ Platform Services End ################################################################