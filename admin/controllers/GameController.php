<?php
namespace admin\controllers;

use yii;

class GameController extends BaseController
{
    public $layout = 'loginmain';
    /**
     * 游戏列表
     */
    public function actionGameList()
    {
        $games = Yii::$app->session['gameAll'];
        
        //仅有单个游戏权限直接跳转到主页
        if(count($games) == 1 && !in_array('game.0', $games)) {
            $game = Yii::$app->cache->get($games[0]);
            Yii::$app->session['game'] = $game['prefix'];
            return $this->redirect(['question/index']);
        }
        
        //拥有多个游戏权限，跳转至游戏列表页选择游戏
        if(in_array('game.0', $games)) {
            $gameArr = Yii::$app->cache->get('game.0');
        }else {
            $gameArr = Yii::$app->cache->mget($games);
        }
        if(!empty($gameArr)) {
            return $this->render('choose-game',['gameArr' => $gameArr]);
        }
    
        return $this->render('error',['mess'=>'获取游戏信息失败']);
    }
    
    /**
     * 获取用户选择游戏
     * @param number $game_id
     * @param string $game_prefix
     * @return mixed
     */
    public function actionChooseGame($game_id = '',$game_prefix = '')
    {
        //判断参数和权限
        if(empty($game_id) || empty($game_prefix)) {
            return $this->render('error', ['mess'=>'参数错误']);
        }
        $gameMess = Yii::$app->cache->get('game.'.$game_id);
        if((!in_array('game.'.$game_id, Yii::$app->session['gameAll']) && !in_array('game.0', Yii::$app->session['gameAll'])) || $game_prefix != $gameMess['prefix']) {
            return $this->render('error', ['mess'=>'暂无该游戏权限']);
        }
    
        //将用户选择游戏存入session
        Yii::$app->session['game'] = $gameMess['prefix'];
        return $this->redirect(['question/index']);
    }
}

?>