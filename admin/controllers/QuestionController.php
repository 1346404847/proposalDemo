<?php
/**
 * 处理问题列表
 */
namespace admin\controllers;

use admin\models\QuestionList;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class QuestionController extends BaseController
{
    public $sortMap = [
        'asc' => SORT_ASC,
        'desc' => SORT_DESC
    ];

    public $statusMap = [
        1 => "待处理",
        2 => "已处理"
    ];

    public function init()
    {
        parent::init();
        $this->view->title = '表单列表';
    }
    /**
     * 问题列表
     * @return string
     */
    public function actionIndex()
    {
        $model = new QuestionList();
        $category = json_encode($model->category);
        $catFirstList = $model->getCat(0);
        $platform = Yii::$app->cache->get('platform.'.Yii::$app->session['game']) ?: [];
        return $this->render('index',['category'=>$category,'catFirstList'=>$catFirstList, 'platform'=>$platform]);
    }

    public function actionList()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }
        $where = $this->getSearchParams();
        $sort = Yii::$app->request->post('sort');
        $current = intval(Yii::$app->request->post('current')) <= 1 ? 0 : Yii::$app->request->post('current')-1;
        $rowCount = intval(Yii::$app->request->post('rowCount')) ? Yii::$app->request->post('rowCount') : Yii::$app->params['row_count'];
        if (empty($sort)) {
            $sort['create_time'] = SORT_DESC;
        } else {
            foreach ($sort as $key=>$value){
                if (empty($this->sortMap[$value])) continue;
                $sort[$key] = $this->sortMap[$value];
            }
        }
        $provider = new ActiveDataProvider([
            'query' => QuestionList::find()
                    ->where($where),
            'sort' => [
                'defaultOrder' => $sort,
            ],
            'pagination' => [
                'page' => $current,
                'pageSize' => $rowCount
            ]
        ]);
        foreach ($provider->getModels() as $key => $value) {
            $provider->getModels()[$key]->title = Html::encode($value->title);
            $provider->getModels()[$key]->content = Html::encode($value->content);
            $provider->getModels()[$key]->op_datetime = $provider->getModels()[$key]->op_datetime ? date('Y-m-d H:i:s', $provider->getModels()[$key]->op_datetime) : "" ;
            $provider->getModels()[$key]->create_time = $provider->getModels()[$key]->create_time ? date('Y-m-d H:i:s', $provider->getModels()[$key]->create_time) : "" ;
            $provider->getModels()[$key]->status = isset($this->statusMap[$value->status]) ? $this->statusMap[$value->status] : '异常';
            $provider->getModels()[$key]->_id = (string)$value->_id;
            $value->cat_first = isset($value->cat_first) ? $value->cat_first : 0;
            $value->cat_second = isset($value->cat_second) ? $value->cat_second : 0;
        }
        $response['rows'] = $provider->getModels();
        $response['current'] = $provider->getPagination()->page + 1;
        $response['rowCount'] = $provider->getPagination()->pageSize;
        $response['total'] = $provider->totalCount;
        die(Json::encode($response));
    }

    public function actionView()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if (empty($id)) {

                throw new InvalidParamException();
            }

            $question = QuestionList::findOne(['_id' => Yii::$app->request->post('id')]);
            if (!Yii::$app->request->post('op_content')) {
                die(json_encode(["code" => 100, "message" => "请填写内容"]));
            }
            $question->op_content = Yii::$app->request->post('op_content');
            $question->op_user = Yii::$app->session['truename'];
            $question->op_datetime = time();
            $question->status = 2;
            $question->cat_first = Yii::$app->request->post('cat_first');
            $question->cat_second = Yii::$app->request->post('cat_second');
            $question->update();
            if ($question) {
                die(json_encode(["code" => 200, "message" => "更新成功"]));
            }
        }
        if (!Yii::$app->request->get('id')) {
            throw new NotFoundHttpException();
        }
        $model = new QuestionList();
        $id = Yii::$app->request->get('id');
        $detail = QuestionList::findOne(['_id' => $id]);
        if (!$detail) {
            throw new NotFoundHttpException();
        }
        $uid = $detail->uid;
        $where = [];
        $where['uid'] = $uid;
        $where['game'] = Yii::$app->session['game'];
        //$where['_id'] = ['$ne' =>$id]; //无效
        $recentList = QuestionList::find()->where($where)->orderBy(['create_time' => SORT_DESC])->limit(5)->all();
        
        //echo $id;
        //print_r($recentList);die();
        //var_dump($recentList);die;
        //获取玩家咨询类别
        $cat_data = new QuestionList();
        foreach ( $recentList as $k => $v )
        {
        	$recentList[$k]['cat_first'] = $cat_data -> getCatName( isset( $recentList[$k]['cat_first'] ) ? $recentList[$k]['cat_first'] :'' );
        	$recentList[$k]['cat_second'] = $cat_data -> getCatName( isset( $recentList[$k]['cat_second'] ) ? $recentList[$k]['cat_second'] :'' );
        }
        
        $detailArray = $detail->toArray();
        $detailArray['id'] = $id;
        $detailArray['recent_list'] = $recentList;
        //分类列表
        $detailArray['cat_first_list'] = $model->getCat(0);
        if($detailArray['cat_first']){
            $detailArray['cat_second_list'] = $model->getCat($detailArray['cat_first']);
        }else{
            $detailArray['cat_second_list'] = [];
        }
        $detailArray['category'] = json_encode($model->category);
        return $this->render('view', $detailArray);
    }

    public function actionExport()
    {
        $where = $this->getSearchParams();
        $result = QuestionList::find()->where($where)->orderBy(['create_time' => SORT_DESC])->all();
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'id');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'pid');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'uid');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '版本');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '游戏');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '平台');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', '区服');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'VIP');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', '角色');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', '标题');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', '内容');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', '状态');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', '提问时间');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', '反馈内容');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', '处理人');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', '处理时间');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', '时间间隔(秒)');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', '咨询类别');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', '客户端版本');
        //获取玩家咨询类别
        $cat_data = new QuestionList();
        foreach( $result as $key => $value ) {
            $status = $value->status == 2 ? "已处理" : "未处理";
            $op_datetime = empty($value->op_datetime) ? "" : date('Y-m-d H:i:s', $value->op_datetime);
            $poi = $key+2;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $poi, (string) $value->_id);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $poi, $value->pid);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $poi, $value->uid);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $poi, $value->version);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $poi, $value->game);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $poi, $value->platform);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $poi, $value->area_service);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $poi, $value->vip);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $poi, $value->role);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $poi, $value->title);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $poi, $value->content);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $poi, $status);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $poi, date("Y-m-d H:i:s", $value->create_time));
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $poi, $value->op_content);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $poi, $value->op_user);
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $poi, $op_datetime);
            $time = "";
            if ($value->op_datetime) {
                $time = intval($value->op_datetime - $value->create_time);
            }
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $poi, $time);
            //玩家咨询类别
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $poi, $cat_data->getCatName($value->cat_first).'-'.$cat_data->getCatName($value->cat_second));
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $poi, $value->package_version);
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="' . Yii::$app->session['game'] . '_' . time() .'.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }

    public function getSearchParams()
    {
        if (Yii::$app->request->isPost){
            $params = Yii::$app->request->post();
        } else {
            $params = Yii::$app->request->get();
        }
        $where = [];
        $where['game'] = Yii::$app->session['game'];
        if (intval($params['status']) > 0 ) {
            $where['status'] = intval($params['status']);
        }

        if (trim($params['area_service'])) {
            $where['area_service'] = trim($params['area_service']);
        }

        if (trim($params['op_user'])) {
            $where['op_user'] = $params['op_user'];
        }

        if (trim($params['platform'])) {
            $where['platform'] = trim($params['platform']);
        }

        if (!empty($params['pid'])) {
            $where['pid'] = $params['pid'];
        }

        if (!empty($params['vip']) && $params['vip'] != -1) {
            $where['vip'] = $params['vip'];
        }

        if (trim($params['version']) ) {
            $where['version'] = trim($params['version']);
        }

        if ($params['create_time_start']) {
            $create_time_start = strtotime($params['create_time_start']);
            $where['create_time'] = ['$gte' => $create_time_start];
        }

        if ($params['create_time_end']) {
            $create_time_end = strtotime($params['create_time_end']);
            $where['create_time'] = isset($where['create_time']) ? ['$lte' => $create_time_end] + $where['create_time'] : ['$lte' => $create_time_end];
        }

        if ($params['op_datetime_start']) {
            $op_datetime_start = strtotime($params['op_datetime_start']);
            $where['op_datetime'] = ['$gte' => $op_datetime_start];
        }

        if ($params['op_datetime_end']) {
            $op_datetime_end = strtotime($params['op_datetime_end']);
            $where['op_datetime'] = isset($where['op_datetime']) ? ['$lte' => $op_datetime_end] + $where['op_datetime'] : ['$lte' => $op_datetime_end];
        }

        if(isset($params['cat_first']) && $params['cat_first']) {
            $where['cat_first'] = $params['cat_first'];
        }


        if(isset($params['cat_second']) && $params['cat_second']) {
            $where['cat_second'] = $params['cat_second'];
        }
        
        //add  uid
        if(isset($params['uid']) && $params['uid']) {
            $where['uid'] = $params['uid'];
        }
        return $where;
    }
    
    /**
     * 通过平台获取区组
     * @return string
     */
    public function actionGroup()
    {
        if (!Yii::$app->request->getIsAjax()) {
            return Json::encode(['flag'=> 0, 'msg'=>'非法请求']);
        }

        $params = Yii::$app->request->post();

        if(!isset($params['platform_id'])) {
            return Json::encode(['flag'=> 0, 'msg'=>'参数错误']);
        }

        $group = Yii::$app->cache->get("group.{$params['platform_id']}");

        if($group){
            return json_encode(['flag'=>1, 'data'=>$group]);
        }else{
            return Json::encode(['flag'=> 0, 'msg'=>'错误']);
        }
    }
    
}
