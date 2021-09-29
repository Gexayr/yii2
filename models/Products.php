<?php

namespace app\models;

use yii\db\ActiveRecord;

class Products extends ActiveRecord
{
    public $id;
    public $store_id;
    public $upc;
    public $price;
    public $title;
    public $import_id;

    public static function tableName()
    {
        return 'store_product';
    }

}
