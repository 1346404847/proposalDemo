<?php

namespace admin\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'common\filter\LoginFilter',
            ],
         
        ];
    }
}
