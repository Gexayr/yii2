<?php
namespace app\models;

use yii\base\BaseObject;
use yii\queue\JobInterface;

class WriteJob extends BaseObject implements JobInterface
{
    public function execute($queue)
    {
        ImportForm::importProducts();
    }
}