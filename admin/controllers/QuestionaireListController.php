<?php
namespace admin\controllers;
/**
 * Created by PhpStorm.
 * Date: 18/4/8
 * Time: 下午7:21
 */
use yii;
use admin\models\QuestionnaireConfig;

class QuestionaireListController extends BaseController
{
    public function actionConfList()
    {

        if (Yii::$app->request->isAjax) {
            $current = Yii::$app->request->post('current');
            $rowCount = Yii::$app->request->post('rowCount');
            $offset = ($current - 1) * $rowCount;
            $rs = [];

            $list = QuestionnaireConfig::find()
                ->offset($offset)
                ->limit($rowCount)
                ->orderBy(['_id' => SORT_DESC])
                ->where([
                            'status' => QuestionnaireConfig::STATUS_NORMAL,
                            'game' => Yii::$app->session['game'],
                        ])
                ->asArray()
                ->all();

            $rs['rows'] = $list;
            $rs['total'] = QuestionnaireConfig::find()->count();
            $rs['current'] = $current;

            echo json_encode($rs);
            die;
        } else {
            return $this->render('conf-list');
        }

    }
}