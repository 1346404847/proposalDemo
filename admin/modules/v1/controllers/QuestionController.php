<?php

namespace admin\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\rest\ActiveController;
use admin\models\QuestionList;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class QuestionController extends ActiveController
{
    public $modelClass = 'admin\models\QuestionList';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        //获取游戏
        $headers = Yii::$app->getRequest()->getHeaders();
        $auth = explode(':', $headers['Authorization']);
        $publicKeys = explode(' ', $auth[0]);
        $game = Yii::$app->params['api_auth_token'][$publicKeys[1]]['game'];

        $provider = new ActiveDataProvider([
            'query' => QuestionList::find()
                    ->where(['uid' => Yii::$app->request->get('uid'), 'game'=>$game]),
            'sort' => [
                'defaultOrder' => [
                    'create_time' => SORT_DESC
                ],
            ],
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        return $provider;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        $this->checkAccess($this->action, $model);
        if (!Yii::$app->request->post('title')
            || !Yii::$app->request->post('role') || !Yii::$app->request->post('platform')
            || !Yii::$app->request->post('area_service') || !Yii::$app->request->post('content')
            || !Yii::$app->request->post('version') || !Yii::$app->request->post('package_version') || !Yii::$app->request->post('game')) {

            throw new ServerErrorHttpException('参数验证失败', -32602);
        }


        //验证签名是否与提交游戏问题相符
        $headers = Yii::$app->getRequest()->getHeaders();
        $auth = explode(':', $headers['Authorization']);
        $publicKeys = explode(' ', $auth[0]);
        if(Yii::$app->request->post('game') != Yii::$app->params['api_auth_token'][$publicKeys[1]]['game']) {
            throw new ForbiddenHttpException('签名与提交内容不符', -32002);
        }
        
        $params = Yii::$app->getRequest()->getBodyParams();
        $params['title'] = strtr($params['title'], array_combine(Yii::$app->params['badword'],array_fill(0,count(Yii::$app->params['badword']),'***')));
        $params['content'] = strtr($params['content'], array_combine(Yii::$app->params['badword'],array_fill(0,count(Yii::$app->params['badword']),'***')));
        $params['status'] = 1;
        $params['create_time'] = time();
        $params['create_source'] = 1;



        if ( isset($params['op_datetime']) ) {unset($params['op_datetime']);}
        if ( isset($params['op_user']) ) {unset($params['op_user']);}

        $model->load($params, '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('提交失败.');
        }

        return ['name' => 'ok', 'message' => '提交成功', 'code' => '200'];
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        $auth = explode(':', $headers['Authorization']);
        if (count($auth) < 2 || empty($auth[1]) || empty($headers['Ttime'])) {
            throw new ForbiddenHttpException('签名失败1', -32002);
        }

        if (strpos($auth[0], 'SERVICE ') === false) {
            throw new ForbiddenHttpException('签名失败2', -32002);
        }

        $date = strtotime($headers['Ttime']) ? strtotime($headers['Ttime']) : false;
        if ($date === false) {
            throw new ForbiddenHttpException('签名失败3', -32002);
        }

        if ($headers['Ttime'] != date('Y-m-d H:i:s', $date) )
        {
            throw new ForbiddenHttpException('签名失败4', -32002);
        }

        $publicKeys = explode(' ', $auth[0]);
        $token = md5('SERVICE' .  Yii::$app->params['api_auth_token'][$publicKeys[1]]['private_key'] . $headers['Ttime']);
        if ($token != $auth[1]) {
            throw new ForbiddenHttpException('签名失败5', -32002);
        }
    }

}
