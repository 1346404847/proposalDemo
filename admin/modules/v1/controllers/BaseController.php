<?php

namespace admin\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class BaseController extends ActiveController
{
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
