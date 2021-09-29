<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\InputForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Import';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('importFormSubmitted')): ?>

    <?php else: ?>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'import-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                <?php
//                $store = \app\models\Store::find()->asArray()->all();
                $stores = \app\models\Store::find()->select('title')->indexBy('id')->column();
                    echo $form->field($model, 'store_id')->dropdownList(
                        $stores,
                        ['prompt'=>'Select Store']
                    );

                    echo $form->field($model, 'uploadedFiles[]')
                        ->fileInput(['multiple'=>true, 'accept' => 'text/csv']);
                ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'import-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
