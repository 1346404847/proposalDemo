<?php

namespace console\controllers;

use admin\models\QuestionnaireStatisticsCache;
use yii\console\Controller;
/**
 * Created by PhpStorm.
 * Date: 16-7-25
 * Time: 12:32
 */
class QuestionnaireStatisticsCacheController extends Controller
{
    /**
     * 问卷统计
     */
    public function actionSetCache($game, $_id=null)
    {
        $result = QuestionnaireStatisticsCache::setCache($game, $_id);

        if(true !== $result){
            echo $result;
        }

        return 0;
    }
}