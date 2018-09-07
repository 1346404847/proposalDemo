<?php

namespace admin\controllers;

use Yii;
use clients\ucenter\services\Common;
use clients\ucenter\services\User;
use clients\ucenter\services\Project;
use clients\ucenter\lib\Exception;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends BaseController
{

    public $layout = 'loginmain';


    /**
     * check login
     *
     * @return string
     */
    public function actionLogin()
    {

        switch ($_SERVER['HTTP_HOST']) {
            case 'proposal.topjoy.com':
            case 'apiproposal.topjoy.com':
                $callbackUrl = 'https' . '://' . $_SERVER['HTTP_HOST'] . '/user/do-login';
                break;
            default:
                $callbackUrl = 'http' . '://' . $_SERVER['HTTP_HOST'] . '/user/do-login';
                break;
        }
        //获取远程服务器登录地址
        try {
            $loginUrl = Common::loginUrl($callbackUrl);
            //执行跳转
            header("Location: $loginUrl");
        } catch (Exception $e) {
            return $this->render('error', ['mess' => '登录异常']);
        }

        die();
    }


    /**
     * 执行登录
     */
    public function actionDoLogin($token = '')
    {
        if (empty($token)) {
            return $this->render('error', ['mess' => 'no token']);
        }
        //验证token,获取uid
        $uid = Common::checkToken($token);

        if (!$uid) {
            return $this->render('error', ['mess' => '无效token']);
        }

        //通过uid获得用户信息、角色，判断用户权限
        $roleArr = [];
        $games = [];
        $roles = [];
        $user = User::getUserById($uid);
        $roleArr = User::getRolesById($uid);

        if (empty($roleArr)) {
            return $this->render('error', ['mess' => '暂无权限']);
        }

        foreach ($roleArr as $key => $value) {
            $roles[] = $value['en_name'];
            $games[] = 'game.' . $value['game_id'];
        }
        if (empty($games)) {
            return $this->render('error', ['mess' => '暂无权限']);
        }
        //存储本地session
        Yii::$app->session['truename'] = $user['name'];
        Yii::$app->session['account'] = $user['account'];
        Yii::$app->session['number'] = $user['number'];
        Yii::$app->session['roles'] = $roles;
        Yii::$app->session['gameAll'] = $games;

        return $this->redirect(['game/game-list']);

    }


    /**
     * logout
     *
     * @return void
     */
    public function actionLogout()
    {
        unset(Yii::$app->session['truename']);
        unset(Yii::$app->session['number']);
        unset(Yii::$app->session['account']);
        unset(Yii::$app->session['roles']);
        unset(Yii::$app->session['gameAll']);
        unset(Yii::$app->session['game']);

        switch ($_SERVER['HTTP_HOST']) {
            case 'proposal.topjoy.com':
            case 'apiproposal.topjoy.com':
                $logoutUrl = Common::logoutUrl('https://' . $_SERVER['HTTP_HOST'] . '/user/login');
                break;
            default:
                $logoutUrl = Common::logoutUrl('http://' . $_SERVER['HTTP_HOST'] . '/user/login');
                break;
        }
        //执行跳转
        header("Location: $logoutUrl");
    }
}
