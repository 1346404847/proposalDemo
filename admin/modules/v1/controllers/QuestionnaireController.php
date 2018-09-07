<?php

namespace admin\modules\v1\controllers;

use admin\models\Questionnaire;
use Yii;

use admin\models\QuestionnaireConfig;
use yii\web\ServerErrorHttpException;


use yii\filters\Cors;
use yii\helpers\ArrayHelper;

class QuestionnaireController extends BaseController
{
    public $modelClass = 'admin\models\Questionnaire';


    public function behaviors()
    {

        return ArrayHelper::merge(
            [
                [
                    'class'   => Cors::className(),
                    'cors'    => [
                        'Origin' => ['https://web-cdn.topjoy.com','https://apiproposal.topjoy.com'],
                        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Allow-Credentials' => true,
                        'Access-Control-Max-Age' => 86400,
                        'Access-Control-Expose-Headers' => [],
                    ]
                ],
            ], parent::behaviors()
        );
    }


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }


    /**
     * 提交问卷
     * 错误码:
     * -31005       未配置答卷
     * -31006       答题未开始
     * -31007       答题已结束
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCreate()
    {
        $where = [];
        //请求参数记录日志
        Yii::info(Yii::$app->getRequest()->post(), 'interface_request');

        $model = new $this->modelClass();

        $this->checkAccess($this->action, $model);

        if (!Yii::$app->request->post('uid') || Yii::$app->request->post('vip') === null
            || !Yii::$app->request->post('openid') || !Yii::$app->request->post('role')
            || !Yii::$app->request->post('version') || !Yii::$app->request->post('platform')
            || !Yii::$app->request->post('area_service') || Yii::$app->request->post('level') === null
            || !Yii::$app->request->post('create_time') || !Yii::$app->request->post('questions')
            || !Yii::$app->request->post('game') || !Yii::$app->request->post('questionnaireConfId')
        ) {

            throw new ServerErrorHttpException('参数验证失败', '-32602');
        }

        $params = Yii::$app->getRequest()->getBodyParams();


        //玩家UID
        $where['uid'] = Yii::$app->request->post('uid');
        //平台
        $where['platform'] = Yii::$app->request->post('platform');
        //区服
        $where['area_service'] = Yii::$app->request->post('area_service');
        //游戏
        $where['game'] = Yii::$app->request->post('game');
        //问卷ID
        $where['questionnaireConfId'] = Yii::$app->request->post('questionnaireConfId');

        // 压测临时关闭
        $search = Questionnaire::find()->where($where)->asArray()->one();

        //判断用户是否存在
        // 压测临时关闭
        if(!empty($search)){
            return ['name'=> 'ok','code' => '-31008', 'message' => '该用户已经答过此问卷,不能重复答题'];
        }

        //解析json
        $params['questions'] = json_decode($params['questions'], true);

        //获取问卷配置
        $questionnaireConfig = QuestionnaireConfig::find()
            ->where(['_id' => Yii::$app->getRequest()->post()['questionnaireConfId']])
            ->orderBy(['create_time' => SORT_DESC])
            ->limit(1)
            ->one();

        if (!$questionnaireConfig) {
            return ['name' => 'fail', 'message' => '未配置答卷', 'code' => '-31005'];
        }

        $start_time  = strtotime($questionnaireConfig['start_time']);
        $finish_time = strtotime($questionnaireConfig['finish_time']);
        $time        = time();
        if ($time < $start_time) {
            return ['name' => 'fail', 'message' => '答题未开始', 'code' => '-31006'];
        }

        if ($time > $finish_time) {
            return ['name' => 'fail', 'message' => '答题已结束', 'code' => '-31007'];
        }

        $params['create_time'] = time();

        $model->load($params, '');
        if ($model->save()) {
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('提交失败.');
        }
        return ['name' => 'ok', 'message' => '提交成功', 'code' => '200'];
    }

}
