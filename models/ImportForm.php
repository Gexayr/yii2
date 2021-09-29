<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class ImportForm extends ActiveRecord
{
    const STATE_NEW = 'NEW';
    const STATE_PROCESSING = 'PROCESSING';
    const STATE_DONE = 'DONE';

    public $store_id;
    public $file;
    public $state;
    /**
     * @var UploadedFile[]
     */
    public $uploadedFiles;

    public static function tableName()
    {
        return 'imports';
    }

    public function getStore()
    {
        return $this->hasOne(Store::class, ['id' => 'store_id']);

    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['import_id' => 'id']);
    }

    public function getProductErrors()
    {

//        $fileHandler=fopen("upload.csv",'r');
//        if($fileHandler){
//            while($line=fgetcsv($fileHandler,1000)){
//                $model = new CLASS_NAME;
//                $model->image_url=$line[0];
//                $model->save();
//            }
//        }
        return $this->hasMany(Products::class, ['import_id' => 'id']);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['store_id', 'uploadedFiles'], 'required'],
            [['uploadedFiles'], 'file',
                'extensions' => 'csv',
                'checkExtensionByMimeType' => false,
                'maxFiles' => 10,
                'maxSize'=>1024 * 1024 * 5
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'store_id' => 'Store',
            'uploadedFiles' => 'Import',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {

            $files = [];
            foreach ($this->uploadedFiles as $file) {
                $filename = $this->getRandomString();
                $file->saveAs('uploads/' . $filename . '.' . $file->extension);
                $files[] = $filename;
            }
            $this->setImport($files);
            return true;
        }
        return false;
    }

    private function setImport(array $files)
    {
        $data = [];
        foreach ($files as $file) {
            $data[] =  [$this->store_id, $file, self::STATE_NEW];
        }
        Yii::$app->db->createCommand()->batchInsert('imports',
            ['store_id', 'file', 'state'], $data)->execute();
    }

    private function getRandomString()
    {
        $security = new Security();
        return $security->generateRandomString(10);
    }
}
