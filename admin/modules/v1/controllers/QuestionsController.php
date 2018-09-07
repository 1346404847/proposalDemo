<?php

namespace admin\modules\v1\controllers;

use Yii;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use admin\models\Questionnaire;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use admin\models\QuestionnaireConfig;

class QuestionsController extends ActiveController
{
    public $modelClass   = 'admin\models\QuestionnaireConfig';
    public $modelClasses = 'admin\models\Questionnaire';

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
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }


    public function prepareDataProvider()
    {
        $where = [];
        $msg   = [];
        //玩家UID
        if(!empty(Yii::$app->getRequest()->get('uid'))) {
            $where['uid'] = Yii::$app->getRequest()->get('uid');
        }
        //游戏
        if(!empty(Yii::$app->getRequest()->get('game'))) {
            $where['game'] = Yii::$app->getRequest()->get('game');
        }
        //玩家vip
        if(!empty(Yii::$app->getRequest()->get('vip'))) {
            $where['vip'] = Yii::$app->getRequest()->get('vip');
        }
        //玩家openID
        if(!empty(Yii::$app->getRequest()->get('openid'))) {
            $where['openid'] = Yii::$app->getRequest()->get('openid');
        }
        //玩家角色
        if(!empty(Yii::$app->getRequest()->get('role'))) {
            $where['role'] = Yii::$app->getRequest()->get('role');
        }
        //版本
        if(!empty(Yii::$app->getRequest()->get('version'))) {
            $where['version'] = Yii::$app->getRequest()->get('version');
        }
        //平台
        if(!empty(Yii::$app->getRequest()->get('platform'))) {
            $where['platform'] = Yii::$app->getRequest()->get('platform');
        }
        //玩家等级
        if(!empty(Yii::$app->getRequest()->get('level'))) {
            $where['level'] = Yii::$app->getRequest()->get('level');
        }

        //服务器
        if(!empty(Yii::$app->getRequest()->get('area_service'))) {
            $where['area_service'] = Yii::$app->getRequest()->get('area_service');
        }
        //问卷ID
        if(!empty(Yii::$app->getRequest()->get('questionnaireConfId'))) {
            $where['questionnaireConfId'] = Yii::$app->getRequest()->get('questionnaireConfId');
        }

        //判断答题是否结束
        $questionnaireConfig = QuestionnaireConfig::find()
                             ->where(['_id' => Yii::$app->getRequest()->get()['questionnaireConfId']])
                             ->orderBy(['create_time' => SORT_DESC])
                             ->limit(1)
                             ->one();
        
        if (!$questionnaireConfig) {
            return ['name' => 'fail', 'message' => '未配置答卷', 'code' => '-31005','titleHeader' => '未配置答卷'];
        }

        $start_time  = strtotime($questionnaireConfig['start_time']);
        $finish_time = strtotime($questionnaireConfig['finish_time']);
        $time        = time();
        if ($time < $start_time) {
            return ['name' => 'fail', 'message' => '答题未开始', 'code' => '-31006','titleHeader' => $questionnaireConfig['title']];
        }

        if ($time > $finish_time) {
            return ['name' => 'fail', 'message' => '答题已结束', 'code' => '-31007','titleHeader' => $questionnaireConfig['title']];
        }

        //检查是否答题
        $flag = Questionnaire::find()->where($where)->asArray()->one();

        if($flag){
            $failData  = [
                'code' => 400,
                'status' => 1,
                'titleHeader' => $questionnaireConfig['title'],
                'message' => "你已经答过题了。",
                'questions' => []
            ];
            return $failData;
        }else{
            $quesData = QuestionnaireConfig::find()->where(['_id' =>Yii::$app->request->get('questionnaireConfId')])->asArray()->one();
            if($quesData['data']){
                foreach ($quesData['data'] as $q=>$v){
                    if (!in_array($v['type'], [QuestionnaireConfig::TYPE_RADIO, QuestionnaireConfig::TYPE_MULTISELECT,QuestionnaireConfig::TYPE_ANSWER])) {
                        continue;
                    }else{
                        if(!empty($v)){
                            $one = [];
                            foreach ($v as $j=>$content){

                                if($j == 'content'){
                                    $one['title'] = explode('.',$content)[1];
                                }else{
                                    $one[$j] =  $content;
                                }
                                if($j == 'NeedDiamond'){
                                    $lContent  = ltrim($content,'[');
                                    $rContent  = rtrim($lContent,']');
                                    $intail    = explode('-',$rContent);
                                    $newIntail = [];
                                    foreach ($intail as $value) {
                                        $newIntail[] = (int)$value;
                                    }
                                    $one[$j]   = $newIntail;
                                }
                            }
                            $msg[$one['id']]   = $one;
                        }
                    }
                }
            }
            $content = [];
            if ($msg) {
                foreach ($msg as $key=>$value) {
                    unset($value['id']);
                    unset($value['type']);
                    unset($value['NeedDiamond']);
                    unset($value['title']);
                    $content[$key] = [
                        'NeedDiamond' => $msg[$key]['NeedDiamond'],
                        'type' => $msg[$key]['type'],
                        'data' => ['title'=>$msg[$key]['title'], 'val'=>$value]
                    ];
                }
            }
            if($content){
                $data['code']        = 200;  //返回数据成功
                $data['status']      = 0;
                $data['titleHeader'] = $quesData['title'];
                $data['questions']   = $content;
            }else{
                $data['code']        = 400;  //返回数据失败
                $data['status']      = 1;
                $data['titleHeader'] = $quesData['title'];
                $data['message']     = "你已经答过题了。";
                $data['questions']   = [];
            }

            return $data;
        }

    }

    /**
     * Checks the privilege of the current user.
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
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
