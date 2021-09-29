<?php

namespace app\models;

use yii\db\ActiveRecord;

class Store extends ActiveRecord
{
    public $id;
    public $title;

    public static function tableName()
    {
        return 'store';
    }

}
