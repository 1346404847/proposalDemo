<?php

namespace admin\controllers;

/**
 * Created by PhpStorm.
 * User: playcrab
 * Date: 18/4/8
 * Time: 上午10:38
 */
use yii;
use yii\helpers\Json;
use yii\web\UploadedFile;
use admin\models\UploadConf;
use admin\models\QuestionnaireConfig;


class UploadsController extends BaseController
{
    public function actionUploadExecl()
    {
        if (Yii::$app->request->isAjax) {
            //文件上传
            $uploadModel = new UploadConf();
            $model       = new QuestionnaireConfig();

            if (Yii::$app->request->isPost) {
                $uploadModel->confFile = UploadedFile::getInstance($uploadModel, 'confFile');

                if (!$uploadFilePath = $uploadModel->upload()) {
                    $erros = $uploadModel->getFirstErrors();
                    echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                    die;
                }
            }

            $excelData = $this->readExcel($uploadFilePath);

            if (!empty($excelData)) {
                $model->title       = $excelData[0]['content'];
                $model->subtitle    = $excelData[1]['content'];
                $model->data        = $excelData;
                $model->start_time  = Json::decode($excelData[2]['content'], true)['start'];
                $model->finish_time = Json::decode($excelData[2]['content'], true)['finish'];
                $model->status      = QuestionnaireConfig::STATUS_NORMAL;
                $model->create_time = time();
                $model->game        = Yii::$app->session['game'];

                if ($model->save()) {
                    echo json_encode(['flag' => 1, 'info' => '新增成功']);
                    die;
                } else {
                    $erros = $model->getFirstErrors();

                    echo json_encode(['flag' => 0, 'info' => array_shift($erros)]);
                    die;
                }

            }

        }
        return $this->render('execl');
    }


    public function readExcel($uploadFilePath)
    {
        $data      = [];
        $excelRead = new \PHPExcel_Reader_Excel2007();
        if (!$excelRead->canRead($uploadFilePath)) {
            $excelRead = new \PHPExcel_Reader_Excel5();
            if (!$excelRead->canRead($uploadFilePath)) {
                echo json_encode(['flag' => 0, 'info' => "失败"]);
                die;
            }
        }

        $phpExcel = $excelRead->load($uploadFilePath);
        $curSheet = $phpExcel->getSheet(0);
        //最大行数
        $rowCount = $curSheet->getHighestRow();
        //最大列数
        $colCount   = $curSheet->getHighestColumn();
        $highestcol = \PHPExcel_Cell::columnIndexFromString($colCount);


        $readTag  = false;
        $num_flag = 0;
        //循环行
        for ($row = 1; $row <= $rowCount; $row ++) {
            $flag = $curSheet->getCellByColumnAndRow(0, $row)->getValue();
            if ($flag == "Start") {
                $readTag = true;
            }
            if ($readTag) {
                //循坏列
                for ($col = 1; $col < $highestcol; $col ++) {
                    $col_name = $curSheet->getCellByColumnAndRow($col, 4)->getValue();

                    $col_name = trim($col_name);

                    if (empty($col_name) || trim($col_name) == "$") {
                        continue;
                    }
                    $v = $curSheet->getCellByColumnAndRow($col, $row)->getValue();
                    if (empty($v)) {
                        continue;
                    }

                    if ($v instanceof \PHPExcel_RichText) {
                        $v = $v->__toString();
                    }
                    $data[$num_flag][$col_name] = trim($v);

                }
                $num_flag ++;
            }
            if ($flag == "End") {
                break;
            }
        }
        return $data;
    }

}