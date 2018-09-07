<?php
/**
 * Created by PhpStorm.
 * Date: 15-11-18
 * Time: 9:57
 */

namespace admin\controllers;

use admin\models\Questionnaire;
use admin\models\QuestionnaireStatisticsCache;
use admin\models\UploadQuestionnaireConf;
use admin\models\QuestionnaireConfig;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;


class QuestionnaireController extends BaseController
{
    /**
     * 配置列表
     *
     * @return string
     */
    public function actionConfIndex ()
    {
        if (Yii::$app->request->isAjax) {
            $current = Yii::$app->request->post('current');
            $rowCount = Yii::$app->request->post('rowCount');
            $offset = ($current - 1) * $rowCount;
            $rs = [];

            $list = QuestionnaireConfig::find()
                ->offset($offset)
                ->limit($rowCount)
                ->orderBy(['_id' => SORT_DESC])
                ->where([
                    'status' => QuestionnaireConfig::STATUS_NORMAL,
                    'game' => Yii::$app->session['game'],
                ])
                ->asArray()
                ->all();

            $rs['rows'] = $list;
            $rs['total'] = QuestionnaireConfig::find()->count();
            $rs['current'] = $current;

            echo json_encode($rs);
            die;
        } else {
            return $this->render('confIndex');
        }
    }

    /**
     * 新增活动配置
     *
     * @return string
     */
    public function actionConfAdd ()
    {
        if (Yii::$app->request->isAjax) {
            //文件上传model
            $uploadModel = new UploadQuestionnaireConf();
            //问卷配置model
            $model = new QuestionnaireConfig();
            //长传配置文件
            $config = [];

            if (Yii::$app->request->isPost) {
                $uploadModel->confFile = UploadedFile::getInstance($uploadModel, 'confFile');
                if (!$uploadModel->upload()) {
                    $erros = $uploadModel->getFirstErrors();

                    echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                    die;
                }
            }

            include $uploadModel->filePath;
            $config = $config['questions'];
            unlink($uploadModel->filePath);

            $_config = ArrayHelper::index($config, 'Id');

            //装载参数
            $model->title = $_config[1]['content'];
            $model->subtitle = $_config[2]['content'];
            $model->data = $config;
            $model->start_time = Json::decode($_config[6]['content'], true)['start'];
            $model->finish_time = Json::decode($_config[6]['content'], true)['finish'];
            $model->status = QuestionnaireConfig::STATUS_NORMAL;
            $model->create_time = time();
            $model->game = Yii::$app->session['game'];

            if ($model->save()) {
                echo json_encode(['flag' => 1, 'info' => '新增成功']);
                die;
            } else {
                $erros = $model->getFirstErrors();

                echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                die;
            }
        }

        return $this->render('confAdd');
    }

    /**
     * 修改配置文件
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionConfEdit ()
    {
        if (Yii::$app->request->isAjax) {
            //文件上传model
            $uploadModel = new UploadQuestionnaireConf();
            //长传配置文件
            $config = [];
            $_id = Yii::$app->getRequest()->post("_id");
            //配置记录
            $row = QuestionnaireConfig::findOne(["_id" => $_id]);

            $uploadModel->confFile = UploadedFile::getInstance($uploadModel, 'confFile');
            if (!$uploadModel->upload()) {
                $erros = $uploadModel->getFirstErrors();

                echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                die;
            }

            //读取配置文件
            include $uploadModel->filePath;
            $config = $config['questions'];
            unlink($uploadModel->filePath);

            $_config = ArrayHelper::index($config, 'Id');

            //装载参数
            $row->title = $_config[1]['content'];
            $row->subtitle = $_config[2]['content'];
            $row->data = $config;
            $row->start_time = Json::decode($_config[6]['content'], true)['start'];
            $row->finish_time = Json::decode($_config[6]['content'], true)['finish'];
            $row->status = QuestionnaireConfig::STATUS_NORMAL;
            $row->update_time = time();

            if ($row->save()) {
                echo json_encode(['flag' => 1, 'info' => '新增成功']);
                die;
            } else {
                $erros = $row->getFirstErrors();

                echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                die;
            }
        } else {
            $_id = Yii::$app->request->get('_id');
            $row = QuestionnaireConfig::findOne($_id);

            if (!$row) {
                throw new BadRequestHttpException('_id不存在');
            }

            return $this->render('confEdit', [
                '_id' => $_id,
            ]);
        }
    }

    /**
     * 问卷调查首页
     *
     * @return string
     */
    public function actionIndex ()
    {
        $confList = QuestionnaireConfig::find()
            ->where([
                'status' => QuestionnaireConfig::STATUS_NORMAL,
                'game' => Yii::$app->session['game'],
            ])
            ->orderBy(['create_time' => SORT_DESC])
            ->asArray()
            ->all();

        foreach ($confList as &$v) {
            $v['_id'] = (string)$v['_id'];
            $count = Questionnaire::find()
                ->where([
                    'questionnaireConfId' => $v['_id'],
                    'game' => Yii::$app->session['game'],
                ])
                ->count();

            $v['count'] = number_format($count, 0);
        }
        unset($v);

        return $this->render('index', [
            "confList" => $confList
        ]);
    }

    /**
     * 问卷调查列表
     *
     * @return string
     * @throws \yii\mongodb\Exception
     */
    public function actionList ()
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            $current  = Yii::$app->getRequest()->post('current');
            $rowCount = Yii::$app->getRequest()->post('rowCount');
            $offset = ($current - 1) * $rowCount;
            $response['rows']     = [];
            $response['current']  = $current;
            $response['rowCount'] = $rowCount;
            $response['total']    = 0;
            $where = $this->getWhere(Yii::$app->getRequest()->post());
            $list  = Questionnaire::find()->orderBy(['_id' => SORT_DESC])->offset($offset)->limit($rowCount)->where($where)->asArray()->all();
            if (empty($list)) {
                die(Json::encode($response));
            }
            foreach ($list as &$v) {
                $v['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
            }
            unset($v);
            $response['total'] = Questionnaire::find()->where($where)->count();
            $response['rows']  = $list;
            die(Json::encode($response));
        }

        $questionnaireConfId = Yii::$app->getRequest()->get('questionnaireConfId');
        $questionnaireConf = QuestionnaireConfig::findOne(['_id' => $questionnaireConfId]);
        return $this->render('list', [
            'questionnaireConfId' => $questionnaireConfId,
            'questionnaireConf'   => $questionnaireConf,
        ]);
    }

    /**
     * 获取where
     *
     * @param $params
     * @return array
     */
    public function getWhere ($params)
    {
        $where = [];

        if (trim($params['area_service']) !== '') {
            $where['area_service'] = trim($params['area_service']);
        }

        if (trim($params['platform']) !== '') {
            $where['platform'] = trim($params['platform']);
        }

        if (trim($params['openid']) !== '') {
            $where['openid'] = $params['openid'];
        }

        if (trim($params['uid']) !== '') {
            $where['uid'] = $params['uid'];
        }

        if (intval($params['vip']) >= 0) {
            $where['vip'] = $params['vip'];
        }

        if (trim($params['version']) !== '') {
            $where['version'] = trim($params['version']);
        }

        if (trim($params['role']) !== '') {
            $where['role'] = trim($params['role']);
        }

        if ($params['create_time_start']) {
            $create_time_start = strtotime($params['create_time_start']);
            $where['create_time'] = ['$gte' => $create_time_start];
        }

        if ($params['create_time_end']) {
            $create_time_end = strtotime($params['create_time_end']);
            $where['create_time'] = isset($where['create_time']) ? ['$lte' => $create_time_end] + $where['create_time'] : ['$lte' => $create_time_end];
        }

        if (!empty($params['questionnaireConfId'])) {
            $where['questionnaireConfId'] = $params['questionnaireConfId'];
        }

        $where['game'] = Yii::$app->session['game'];

        return $where;
    }

    /**
     * 查看问卷详情
     *
     * @param $questionnaireConfId
     * @return string
     */
    public function actionQuestionnaireView ($questionnaireConfId)
    {
        $row = QuestionnaireConfig::findOne($questionnaireConfId);
        $data = [];

        foreach ($row['data'] as $k => $v) {
            $tmp = [];

            if (in_array($v['type'], [1, 2, 6])) {
                continue;
            }

            $tmp['type'] = $v['type'];
            $tmp['id'] = $v['id'];
            $tmp['content'] = $v['content'];

            foreach ($v as $k1 => $v1) {
                if (strpos($k1, 'Choose') === 0) {
                    $tmp['Choose'][] = $v1;
                }
            }

            $data[] = $tmp;
        }

        return $this->render('questionnaireView', [
            'row'  => $row,
            'data' => $data,
        ]);
    }

    /**
     * 查看个人问卷详情
     *
     * @param $id
     * @return string
     */
    public function actionView ($id)
    {
        $row       = Questionnaire::findOne(['_id' => $id])->toArray();
        $questions = ArrayHelper::index($row['questions'], 'id');
        $confRow   = QuestionnaireConfig::findOne($row['questionnaireConfId']);
        $data      = [];
        foreach ($confRow['data'] as $k => $v) {
            $tmp = [];
            if (in_array($v['type'], [1, 2, 6])) {
                continue;
            }
            $tmp['id']      = $v['id'];
            $tmp['type']    = $v['type'];
            $tmp['content'] = $v['content'];
            foreach ($v as $k1 => $v1) {
                if (strpos($k1, 'Choose') === 0) {
                    $tmp['Choose'][$k1] = $v1;
                }
            }

            $data[] = $tmp;
        }

        $row['create_time'] = date("Y-m-d H:i:s");
        foreach ($data as $d=>$item){
            foreach ($questions as $s=>$question){
                if((int)$d == (int)$s-4){
                    if($item['type'] == $question['type'] && $item['type'] == '5' && $question['type'] == '5'){
                        $item['answer'] = $question['val'];
                        $data[$d] = $item;
                    }
                    if(isset($item['Choose'])){
                        foreach ($item['Choose'] as $i=>$choose){
                            if(is_array($question['val']) && in_array($i,$question['val'])){
                                if($choose == "其他"){
                                    if(isset($question[$i]) && !empty($question[$i])){
                                        if(is_array($question[$i]) && !empty($question[$i])){
                                            $item['Choose'][$i] = $choose.':checked:'.$question[$i][0];
                                        }else{
                                            $item['Choose'][$i] = $choose.':checked:'.$question[$i];
                                        }
                                    }

                                }else{
                                    $item['Choose'][$i] = $choose . ':checked';
                                }
                            }

                        }
                    }
                    $data[$d] = $item;
                }else{
                    continue;
                }
            }
        }
        return $this->render('view', ['confRow' => $confRow, 'data' => $data, 'questions' => $questions, 'row' => $row]);
    }

    /**
     * 导出excel
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function actionExport ()
    {
        $params = Yii::$app->getRequest()->get();
        $where = $this->getWhere($params);
        $sort = Yii::$app->request->post('sort');

        if (empty($sort)) {
            $sort['_id'] = SORT_DESC;
        } else {
            foreach ($sort as $key => $value) {
                if (empty($this->sortMap[$value])) continue;
                $sort[$key] = $this->sortMap[$value];
            }
        }

        $result = Questionnaire::find()->where($where)->orderBy($sort)->all();
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'openid');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'uid');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'VIP');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '平台');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '区服');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', '等级');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', '答题版本');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', '创建时间');

        foreach ($result as $key => $value) {
            $poi = $key + 2;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $poi, $value->openid);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $poi, $value->uid);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $poi, $value->vip);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $poi, $value->role);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $poi, $value->platform);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $poi, $value->area_service);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $poi, $value->level);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $poi, $value->version);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $poi, date("Y-m-d H:i:s", (int)$value->create_time));
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="' . Yii::$app->session['game'] . '_' . time() . '.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }

    /**
     * 获取统计列表
     *
     * @throws \yii\mongodb\Exception
     */
    public function actionStatisticalList ()
    {
        $params = Yii::$app->getRequest()->get();
        $class = [
            'progress-bar-danger',
            'progress-bar-warning',
            'progress-bar-success',
            'progress-bar-info',
        ];

        $row = QuestionnaireStatisticsCache::findOne(['questionnaireConfId'=> $params['questionnaireConfId']]);
        $questions = ArrayHelper::getValue($row['data'], $params['vip'], []);

        $html = $this->renderPartial('questionsList', [
            'questions' => $questions,
            'class' => $class,
        ]);
        echo json_encode(['flag'=>1, 'info'=>$html]);
        die;
    }

    public function actionExportAnswer()
    {
        $params = Yii::$app->getRequest()->get();

        $result = Questionnaire::find()
            ->select(['questions'])
            ->where(['questionnaireConfId' => $params['questionnaireConfId']])
            ->asArray()
            ->all();

        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        foreach ($result as $key => $value) {
            $poi = $key + 1;
            foreach($value['questions'] as $v){
                if($v['type'] == 5){
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $poi, $v['val']);
                }
            }
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="' . Yii::$app->session['game'] . '_' . time() . '.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }
}