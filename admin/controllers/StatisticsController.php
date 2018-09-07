<?php

namespace admin\controllers;

use admin\models\StatisticsCache;
use Yii;
use admin\models\QuestionnaireConfig;

class StatisticsController extends BaseController
{

    public function actionCount()
    {
        return $this->render("count");
    }

    /**
     * 添加统计
     */
    public function actionCountKey()
    {
        if (Yii::$app->request->isAjax) {
            $params = Yii::$app->request->post();

            $number = $this->getId($params);
            if ($number) {
                $res = $this->getCount($params);
                if ($res == 2) {
                    echo json_encode(['flag' => 0, 'info' => "key已经存在，请稍后重试！"]);
                    die;
                }elseif ($res == 3) {
                    echo json_encode(['flag' => 0, 'info' => "添加失败！"]);
                    die;
                }else {
                    echo json_encode(['flag' => 1, 'info' => "成功"]);
                    die;
                }
            }else{
                echo json_encode(['flag' => 0, 'info' => "输入有效的问卷KEY"]);
                die;
            }

        }
    }

    /**
     * @param $params
     * @return bool
     */
    public function getCount($params)
    {

        $key = trim($params['count']);

        $statistics = StatisticsCache::find()->where(['questionnaireConfId'=>$key,'status'=>0])->asArray()->one();

        if ($statistics) {
            return 2;
        }

        $count = new StatisticsCache();

        $count->questionnaireConfId = $key;

        $count->status = 0;

        $count->create_time = time();

        $count->game = Yii::$app->session['game'];

        $res = $count->save();

        if (!$res) {
            return 3;
        }
        return 1;
    }


    /**
     * @param $key
     * @return string
     */
    public function getId($key)
    {
        $result = QuestionnaireConfig::find()->where(['_id' => trim($key['count'])])->asArray()->one();

        if (empty($result)) {
            return false;
        }
        return true;
    }

}
