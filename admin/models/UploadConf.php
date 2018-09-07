<?php
namespace  admin\models;
/**
 * Created by PhpStorm.
 * Date: 18/4/8
 * Time: 上午11:25
 */
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadConf extends Model
{
    /**
     * @var UploadedFile
     */
    public $confFile;
    public $filePath;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['confFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->filePath = Yii::$app->basePath. '/web/uploads/' . $this->confFile->baseName . '.' . $this->confFile->extension;
            if( !file_exists(dirname($this->filePath)) ){
                mkdir(dirname($this->filePath), 0777, true);
            }
            $this->confFile->saveAs($this->filePath);
            return $this->filePath;
        } else {
            return false;
        }
    }


}