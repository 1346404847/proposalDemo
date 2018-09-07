<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for collection "questionnaire_statistics_cache".
 *
 * @property \MongoId|string $_id
 * @property mixed $questionnaireConfId
 * @property mixed $data
 * @property mixed $update_time
 */
class StatisticsCache extends \yii\mongodb\ActiveRecord
{
    const STATUS_SUCCESS = 0;
    const STATUS_FAIL    = 1;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['customer_service_cn', 'statistics_cache'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'questionnaireConfId',
            'status',
            'create_time',
            'game',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['questionnaireConfId', 'game']
                , 'required'
            ],
            [
                'status','default','value' => self::STATUS_SUCCESS
            ],
            [
                'create_time','default','value'=> time()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'questionnaireConfId' => 'Questionnaire Conf ID',
            'status'=>'Status',
            'create_time' => 'Create Time',
            'game' => 'Game',
        ];
    }


}
