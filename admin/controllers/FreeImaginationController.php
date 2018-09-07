<?php
namespace admin\controllers;
/**
 * Created by PhpStorm.
 * 自由想象
 * Time: 上午11:40
 */
use admin\models\Questionnaire;
use yii;

class FreeImaginationController extends BaseController
{

    public function actionList()
    {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $where = [];
            $re = [];
            if ($data['vip'] != -1) {
                $where['vip'] = $data['vip'];
            }

            $where['questionnaireConfId'] = $data['questionnaireConfId'];
            $where['game'] = Yii::$app->session['game'];
            $current  = Yii::$app->request->post('current');
            $rowCount = Yii::$app->request->post('rowCount');
            $offset   = ($current - 1) * $rowCount;
            $res = Questionnaire::find()
                ->offset($offset)
                ->limit($rowCount)
                ->where($where)
                ->asArray()
                ->all();
            $re['rows']    = $res;
            $re['total']   = Questionnaire::find()->where($where)->count();
            $re['current'] = $current;
            echo  json_encode($re);
            die;

        }
        return $this->render('list');
    }


    public function actionExportAnswer()
    {
        $params = Yii::$app->getRequest()->get();

        $data = ['questions','vip','uid'];
        $where = [];

        if ($params['vip'] != -1) {
            $where['vip'] = $params['vip'];
        }

        $where['questionnaireConfId'] = $params['questionnaireConfId'];

        $result = Questionnaire::find()
            ->select($data)
            ->where($where)
            ->asArray()
            ->all();

        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        foreach ($result as $key => $value) {
            $poi = $key + 1;
            foreach($value['questions'] as $v){
                if($v['id'] == $params['titleId']){
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $poi, $value['uid']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $poi, $v['val']);
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