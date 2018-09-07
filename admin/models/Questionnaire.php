<?php

namespace admin\models;

use Yii;

class Questionnaire extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName ()
    {
        return ['customer_service_cn', 'questionnaire'];
    }

    /**
     * @inheritdoc
     */
    public function attributes ()
    {
        return [
            '_id',
            'uid',
            'vip',
            'openid',
            'role',
            'version',
            'platform',
            'area_service',
            'level',
            'create_time',
            'questions',
            'questionnaireConfId',
            'game',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [['uid', 'vip', 'openid', 'role', 'version', 'platform', 'area_service', 'level', 'create_time', 'questions', 'questionnaireConfId', 'game'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels ()
    {
        return [
            '_id'                 => 'ID',
            'uid'                 => 'Uid',
            'vip'                 => 'VIP',
            'openid'              => '玩家唯一的id',
            'role'                => '角色,玩家名字',
            'version'             => '答题版本',
            'platform'            => '平台',
            'area_service'        => '区服',
            'level'               => '等级',
            'create_time'         => '创建时间',
            'questions'           => '答题列表',
            'questionnaireConfId' => '问卷id'
        ];
    }

    /**
     * 设定API返回数据
     *
     * @return array|void
     */
    public function fields ()
    {

        return [
            '_id',
            'uid',
            'vip',
            'openid',
            'role',
            'version',
            'platform',
            'area_service',
            'level',
            'create_time',
            'questions',
            'questionnaireConfId',
        ];
    }

    public function extraFields ()
    {
        return ['_id'];
    }
}
