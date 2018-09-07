<?php

namespace admin\models;
use Yii;

/**
 * This is the model class for collection "other_question_answers".
 *
 * @property \MongoId|string $_id
 * @property mixed $questionnaireConfId
 * @property mixed $data
 * @property mixed $update_time
 */
class OtherQuestionAnswers extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['customer_service_cn', 'other_question_answers'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'tid',
            'uid',
            'game',
            'role',
            'openid',
            'platform',
            'choose',
            'questionnaireConfId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tid','uid','game','role','openid','platform', 'choose', 'questionnaireConfId'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'tid' => 'Tid',
            'uid' => 'Uid',
            'game' => 'Game',
            'role' => 'Role',
            'openid' => 'Openid',
            'platform' => 'Platform',
            'choose' => 'Choose',
            'questionnaireConfId' => 'Questionnaire Conf ID'
        ];
    }

}

