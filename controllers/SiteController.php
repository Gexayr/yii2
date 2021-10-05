<?php

namespace app\controllers;

use app\models\WriteJob;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\ImportForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $imports = ImportForm::find()
            ->with('store')
            ->asArray()
            ->all();

        return $this->render('index', [
            'imports' => $imports,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionImport()
    {
        $model = new ImportForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->uploadedFiles = UploadedFile::getInstances($model, 'uploadedFiles');
            if ($model->upload()) {
                Yii::$app->queue->push(new WriteJob());
                Yii::$app->session->setFlash('success', "File imported successfully.");
                return $this->goHome();
            }
        }
        return $this->render('import', [
            'model' => $model,
        ]);
    }

    public function actionSetImport()
    {
//        Yii::$app->queue->delay(10)->push(new WriteJob());

        ImportForm::importProducts();
        Yii::$app->session->setFlash('success', "Products imported successfully.");
        return $this->goHome();
    }
}
