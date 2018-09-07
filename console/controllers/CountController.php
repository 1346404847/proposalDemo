<?php

namespace  console\controllers;


use admin\models\QuestionnaireStatisticsCache;
use admin\models\StatisticsCache;
use Yii;
use yii\console\Controller;


class CountController extends Controller
{
    public function actionSetCache()
    {
        
        while (true) {
            $list = StatisticsCache::find()
                ->where(
                    [
                        'status'=>StatisticsCache::STATUS_SUCCESS,
                    ]
                )->one();

            if (!$list) {
                exit(1);
            }
            $game = $list['game'];
            $_id =  $list['questionnaireConfId'];
            $result = QuestionnaireStatisticsCache::setCache($game, $_id);

            if ($result) {
                $list->status = StatisticsCache::STATUS_FAIL;
                $res = $list->save();
                if (!$res) {
                    echo $res;
                    exit(1);
                }
            }else {
                echo $result;
                exit(1);
            }
            echo  0;
        }
    }
}
