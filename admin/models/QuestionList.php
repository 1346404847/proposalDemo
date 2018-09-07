<?php

namespace admin\models;

use Yii;

class QuestionList extends \yii\mongodb\ActiveRecord
{
    //分类选项
    public $category = [
        ['cat_id'=>1,'cat_name'=>'BUG','pid'=>0],
        ['cat_id'=>2,'cat_name'=>'建议','pid'=>0],
        ['cat_id'=>3,'cat_name'=>'游戏相关游戏相关','pid'=>0],
        ['cat_id'=>4,'cat_name'=>'转项目组','pid'=>0],
        ['cat_id'=>5,'cat_name'=>'无效咨询','pid'=>0],
        ['cat_id'=>6,'cat_name'=>'征战','pid'=>3],
        ['cat_id'=>7,'cat_name'=>'挑战','pid'=>3],
        ['cat_id'=>8,'cat_name'=>'竞技','pid'=>3],
        ['cat_id'=>9,'cat_name'=>'bug反馈','pid'=>1],
        ['cat_id'=>10,'cat_name'=>'意见建议','pid'=>2],
        ['cat_id'=>11,'cat_name'=>'充值问题','pid'=>4],
        ['cat_id'=>12,'cat_name'=>'账号问题','pid'=>4],
        ['cat_id'=>13,'cat_name'=>'数据异常','pid'=>4],
        ['cat_id'=>14,'cat_name'=>'活动异常','pid'=>4],
        ['cat_id'=>15,'cat_name'=>'其他','pid'=>4],
        ['cat_id'=>16,'cat_name'=>'社团','pid'=>3],
        ['cat_id'=>17,'cat_name'=>'格斗家','pid'=>3],
        ['cat_id'=>18,'cat_name'=>'活动咨询','pid'=>3],
        ['cat_id'=>19,'cat_name'=>'充值咨询','pid'=>3],
        ['cat_id'=>20,'cat_name'=>'游戏内容','pid'=>3],
        ['cat_id'=>21,'cat_name'=>'问题不明确','pid'=>5],
        ['cat_id'=>22,'cat_name'=>'无效咨询','pid'=>5],
        ['cat_id'=>23,'cat_name'=>'索要抱怨','pid'=>5],
    ];

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['customer_service', 'question_list'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'uid',
            'rid',
            'pid',
            'version',
            'package_version',
            'game',
            'platform',
            'area_service',
            'role',
            'title',
            'content',
            'device',
            'vip',
            'status',
            'op_content',
            'op_user',
            'op_datetime',
            'create_time',
            'create_source',
            'cat_first',
            'cat_second',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'rid', 'pid', 'version', 'vip','game', 'platform', 'area_service', 'role', 'title', 'content', 'device', 'status', 'op_user', 'op_datetime', 'create_time', 'create_source','package_version'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'uid' => 'Uid',
            'rid' => 'uid和分区组成的一个id',
            'pid' => '玩家唯一的id',
            'version' => '版本',
            'package_version' => '客户端安装包的版本号',
            'game' => '游戏',
            'platform' => '平台',
            'area_service' => '区服',
            'role' => '角色,玩家名字',
            'vip' => 'VIP',
            'title' => '标题',
            'status' => '1: 待接取 2: 已处理',
            'content' => '内容',
            'device' => '设备信息',
            'op_content' => '反馈内容',
            'op_user' => '处理人',
            'op_datetime' => '处理时间',
            'create_time' => '创建时间',
            'create_source' => '创建来源默认是1',
        ];
    }

    /**
     * 设定API返回数据
     *
     * @return array|void
     */
    public function fields()
    {

        return [
            '_id',
            'uid',
            'rid',
            'pid',
            'title',
            'version',
            'package_version',
            'vip',
            'game',
            'platform',
            'area_service',
            'role',
            'title',
            'content',
            'op_content',
            'status',
            'create_time',
            'op_user',
            'op_datetime',
            'cat_first',
            'cat_second',
        ];
    }

    public function extraFields()
    {
        return ['_id'];
    }

    function getCat($pid=0)
    {
        $catList = [];
        foreach($this->category as $v){
            if($pid == $v['pid']){
                $catList[] = $v;
            }
        }
        return $catList;
    }
    //玩家咨询获取数组中所对应的类别名字
    public function getCatName( $id )
    {
    	if( !empty($id) )
    	{
    		foreach ( $this->category as $val )
    		{
    			if( $val['cat_id'] == $id )
    			{
	    			return  $val['cat_name'];
    			}
    		}
    	}
    	else 
    	{
    		return '未选择';
    	}
    }
}
