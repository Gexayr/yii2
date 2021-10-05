<?php

namespace app\models;

use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use ruskid\csvimporter\MultipleImportStrategy;
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
    public $loaded;
    public $not_loaded;
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

    public static function importProducts()
    {
        $imports = ImportForm::find()
            ->where('state', ImportForm::STATE_NEW)
            ->asArray()
            ->all();

        foreach ($imports as $import) {
            $importer = new CSVImporter();
            $filename =  "uploads/" . $import['file'] . ".csv";
            $importer->setData(new CSVReader([
                'filename' => $filename,
                'fgetcsvOptions' => [
                    'delimiter' => ','
                ]
            ]));

            $store_id = $import['store_id'];
            $import_id = $import['id'];

            Yii::$app->db->createCommand("UPDATE imports SET state=:state WHERE id=:id")
                ->bindValue(':id', $import_id)
                ->bindValue(':state', self::STATE_PROCESSING)
                ->execute();

            $numberRowsAffected = $importer->import(new MultipleImportStrategy([
                'tableName' => Products::tableName(),
                'configs' => [
                    [
                        'attribute' => 'upc',
                        'value' => function($line) {
                            return $line[0];
                        },
                        'unique' => true,
                    ],
                    [
                        'attribute' => 'title',
                        'value' => function($line) {
                            return $line[1];
                        },
                    ],

                    [
                        'attribute' => 'price',
                        'value' => function($line) {
                            return $line[2];
                        },
                    ],
                    [
                        'attribute' => 'store_id',
                        'value' => function($line) use ($store_id) {
                            return $store_id;
                        },
                    ],
                    [
                        'attribute' => 'import_id',
                        'value' => function($line) use ($import_id) {
                            return $import_id;
                        },
                    ]
                ],
                'skipImport' => function($line){
                    if($line[0] == ""){
                        return true;
                    }
                }
            ]));

            $not_loaded = 0;
            $line_count = count(file($filename));
            if($line_count > 0) {
                $not_loaded = $line_count - 1 - $numberRowsAffected;
            }

            Yii::$app->db->createCommand("UPDATE imports SET not_loaded=:not_loaded, loaded=:loaded, state=:state WHERE id=:id")
                ->bindValue(':id', $import_id)
                ->bindValue(':not_loaded', $not_loaded)
                ->bindValue(':loaded', $numberRowsAffected)
                ->bindValue(':state', self::STATE_DONE)
                ->execute();

        }

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
