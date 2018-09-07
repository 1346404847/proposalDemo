<?php

namespace admin\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for collection "users".
 *
 * @property \MongoId|string $_id
 * @property mixed $username
 * @property mixed $password
 * @property mixed $uid
 * @property mixed $datetime
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['customer_service', 'users'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'username',
            'password',
            'uid',
            'auth_key',
            'datetime',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        if ($this->password === md5($password)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'uid', 'datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'uid' => 'Uid',
            'auth_key' => '简单密匙',
            'datetime' => 'Datetime',
        ];
    }
}
