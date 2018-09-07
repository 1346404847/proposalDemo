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
class QuestionnaireStatisticsCache extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['customer_service_cn', 'questionnaire_statistics_cache'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'questionnaireConfId',
            'data',
            'update_time',
            'game',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['questionnaireConfId', 'data', 'update_time', 'game'], 'safe']
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
            'data' => 'Data',
            'update_time' => 'Update Time',
        ];
    }

    static public function setCache($game, $_id=null)
    {
        $questionsConf  = QuestionnaireConfig::find()
                        ->where(['game' => $game])
                        ->orderBy(['_id'=>SORT_DESC])
                        ->limit(1)
                        ->asArray();

        if($_id){
            $questionsConf->andWhere(['_id' => $_id]);
        }

        $questionsConf  = $questionsConf->one();

        $questionsInfo  = Questionnaire::find()
                        ->where(['game' => $game,'questionnaireConfId'=>json_decode(json_encode($questionsConf['_id']),true)['$oid']])
                        ->orderBy(['_id'=>SORT_DESC])
                        ->asArray()->one();

        $questions = [];

        $allWhere  = ['questionnaireConfId' => (string)$questionsConf['_id']];

        $data      = [];
        foreach(range(-1, 15) as $vip){
            if($vip > -1){
                $allWhere['vip'] = (string)$vip;
            }
            $questionsTotal = Questionnaire::find()->where($allWhere)->count();
            $questions = [];
            foreach ($questionsConf['data'] as $d=>$v) {
                $tmp = [];

                //非选择题跳过
                if (!in_array($v['type'], [QuestionnaireConfig::TYPE_RADIO, QuestionnaireConfig::TYPE_MULTISELECT,QuestionnaireConfig::TYPE_ANSWER])) {
                    continue;
                }

                $tmp['type']    = $v['type'];
                $tmp['content'] = $v['content'];

                if($v['type'] == QuestionnaireConfig::TYPE_ANSWER){
                    $item       = [];
                    $item['id'] = $v['id'];
                    $item['questionnaireConfId'] = json_decode(json_encode($questionsConf['_id']),true)['$oid'];
                    $tmp['questions'][] = $item;
                }

                foreach ($v as $k1 => $v1) {
                    $tmp1 = [];
                    if (strpos($k1, 'Choose') !== 0) {
                        continue;
                    }

                    if($questionsInfo['questions']){
                        foreach ($questionsInfo['questions'] as $i=>$q){
                            if($d == $i+3){
                                if(is_array($q['val']) && in_array($k1,$q['val']) && $v1 == "其他"){
                                    $tmp1['checked'] = $k1;
                                    $tmp1['id']      = $q['id'];
                                    $tmp1['questionnaireConfId'] = json_decode(json_encode($questionsConf['_id']),true)['$oid'];
                                }
                            }
                        }
                    }
                    $where = [
                        "questions" => [
                            '$elemMatch' => [
                                "id"  => $v['id'],
                                "val" => $k1,
                            ]
                        ]
                    ];

                    $count  = Questionnaire::find()
                            ->where($where)
                            ->andWhere($allWhere)
                            ->count();

                    $tmp1['count'] = number_format($count, 0);

                    //除数不能为零
                    if($questionsTotal != 0){
                        $tmp1['percentage'] = number_format($count/$questionsTotal * 100, 2, '.' , '');
                    }else{
                        $tmp1['percentage'] = '0.00';
                    }

                    $tmp1['content'] = $v1;

                    $tmp['questions'][] = $tmp1;
                }
                $questions[] = $tmp;
            }
            $data[$vip]      = $questions;
        }

        $params                = [];
        $params['data']        = $data;
        $params['update_time'] = time();
        $params['game']        = $game;
        $cacheRow = self::find()->where(['questionnaireConfId'=>(string)$questionsConf['_id']])->one();

        if(empty($cacheRow)){
            $params['questionnaireConfId'] = (string)$questionsConf['_id'];
            $cacheRow = new self();
        }else{
            $cacheRow->load($params, '');
        }

        if($cacheRow->load($params, '') && !$cacheRow->save()){
            $errors = $cacheRow->firstErrors;
            return array_shift($errors);
        }

        return true;
    }
}
