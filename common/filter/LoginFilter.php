<?php 
namespace common\filter;

use Yii;
use yii\base\ActionFilter;

/**
 *  login filter
 * 
 */
class LoginFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if(Yii::$app->controller->id == 'user' || Yii::$app->controller->id == 'game' || (isset(Yii::$app->session['truename']) && isset(Yii::$app->session['number']) && isset(Yii::$app->session['game']))) {
            return true;
        }
        
        return Yii::$app->response->redirect(['user/login']);
    }
}