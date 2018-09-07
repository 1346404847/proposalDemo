<?php
namespace admin\controllers;

use Yii;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use admin\models\Questionnaire;
use admin\models\OtherQuestionAnswers;


class OtherController extends BaseController
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            [
                [
                    'class'   => Cors::className(),
                    'cors'    => [
                        'Origin'                        => ['*'],
                        'Access-Control-Request-Method' => ['GET', 'HEAD', 'OPTIONS','POST'],
                    ]
                ],
            ], parent::behaviors()
        );
    }

    /**
     * @return string
     */
    public function actionOtherList()
    {
        $where  = [];
        $_where = [];
        if (Yii::$app->request->isAjax) {
            $vip                  = Yii::$app->request->post('vip');
            $titleId              = Yii::$app->request->post('titleId');
            $otherChoose          = Yii::$app->request->post('otherChoose');
            $questionnaireConfId  = Yii::$app->request->post('questionnaireConfId');

            if ($vip != -1) {
                $where['vip']  = trim($vip);
                $_where['vip'] = trim($vip);
            }
            if ($questionnaireConfId) {
                $where['questionnaireConfId']  = trim($questionnaireConfId);
                $_where['questionnaireConfId'] = trim($questionnaireConfId);
            }

            if($titleId){
                $_where['tid'] = trim($titleId);
            }

            $current  = Yii::$app->request->post('current');
            $rowCount = Yii::$app->request->post('rowCount');
            $offset   = ($current - 1) * $rowCount;
            $res      = [];

            $list     = Questionnaire::find()->orderBy(['_id' => SORT_DESC])->where($where)->asArray()->all();
            $this->insertAnswerData($list,$questionnaireConfId,$otherChoose,$titleId);

            $lists    = OtherQuestionAnswers::find()->offset($offset)->limit($rowCount)->orderBy(['_id' => SORT_DESC])->where($_where)->asArray()->all();

            $numCount       = OtherQuestionAnswers::find()->where($_where)->asArray()->count();
            $res['rows']    = $lists;
            $res['total']   = $numCount;
            $res['current'] = $current;
            echo  json_encode($res);
            die;
        } else {
            return $this->render('list');
        }
    }

    /**
     * @param $list
     * @param $questionnaireConfId
     * @param $otherChoose
     * @param $titleId
     */
    public function insertAnswerData($list, $questionnaireConfId, $otherChoose, $titleId)
    {
        $data  = [];
        if ($list) {
            foreach ($list as $i=>$item) {
                foreach ($item['questions'] as $j=>$val) {
                    if (trim($val['id']) == trim($titleId)) {
                        if (isset($val[$otherChoose])) {
                            if(is_array($val[$otherChoose])){
                                $data = [
                                    'tid' => trim($titleId),                              // 题目ID
                                    'uid' => trim($item['uid']),                          // 玩家UID
                                    'role' => trim($item['role']),                        // 玩家角色
                                    'game' => trim($item['game']),                        // 游戏
                                    'openid' => trim($item['openid']),                    // 玩家openID
                                    'platform' => trim($item['platform']),                // 平台
                                    'choose' => trim($val[$otherChoose][0]),              // 选项
                                    'questionnaireConfId' => trim($questionnaireConfId)   // 问卷ID,key
                                ];
                            }else{
                                $data = [
                                    'tid' => trim($titleId),
                                    'uid' => trim($item['uid']),
                                    'role' => trim($item['role']),
                                    'game' => trim($item['game']),
                                    'openid' => trim($item['openid']),
                                    'platform' => trim($item['platform']),
                                    'choose' => trim($val[$otherChoose]),
                                    'questionnaireConfId' => trim($questionnaireConfId)
                                ];
                            }
                        }

                        $model  = new OtherQuestionAnswers();
                        $insert = $model::find()->where($data)->one();
                        if(empty($insert)) {
                            $model->attributes = $data;
                            $model->save();
                        }else{
                            continue;
                        }
                    }
                }
            }
        }
    }

}