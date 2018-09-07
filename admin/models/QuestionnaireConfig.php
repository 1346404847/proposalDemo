<?php

namespace admin\models;

use Yii;
use yii\filters\PageCache;

/**
 * This is the model class for collection "conf_list".
 *
 * @property \MongoId|string $_id
 */
class QuestionnaireConfig extends \yii\mongodb\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const TYPE_RADIO = 3;
    const TYPE_MULTISELECT = 4;
    const TYPE_ANSWER = 5;

    /**
     * @inheritdoc
     */
    public static function collectionName ()
    {
        return ['customer_service_cn', 'questionnaireConfig'];
    }

    /**
     * @inheritdoc
     */
    public function attributes ()
    {
        return [
            '_id',
            'title',
            'subtitle',
            'data',
            'start_time',
            'finish_time',
            'status',
            'create_time',
            'update_time',
            'game',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [['title', 'subtitle', 'data', 'start_time', 'finish_time', 'status', 'create_time', 'game'], 'required'],
            [['update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels ()
    {
        return [
            '_id'         => 'ID',
            'title'       => '标题',
            'subtitle'    => '副标题',
            'data'        => '配置',
            'start_time'  => '开始时间',
            'finish_time' => '结束时间',
            'status'      => '状态',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }


    /**
     * 根据参数获取"其他"的key
     * @param $questionnaireConfId
     * @param $questionnaireId
     * @return int|string
     */
    public static function getData($questionnaireConfId,$questionnaireId,$game)
    {
        $otherKey = "";
        $other = [];
        $params = self::find()
            ->where(['_id'=>$questionnaireConfId,'game'=>$game])
            ->asArray()
            ->one();
        if (empty($params)) {
            return "";
        }
        foreach ($params['data'] as $key=>$value) {
            if ($value['id'] == $questionnaireId) {
                $other = $value;
                // echo "<pre>";
                // var_dump($value);
            }
        }

        foreach ($other as $key=>$value)
        {
            if ($value=="其他") {
                $otherKey =  $key;
            }
        }

        return $otherKey;
    }
}
