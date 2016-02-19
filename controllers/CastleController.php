<?php

namespace app\controllers;

use Yii,
    app\models\Castle,
    yii\web\NotFoundHttpException,
    yii\filters\VerbFilter,
    app\controllers\MyController;

/**
 * CastleController implements the CRUD actions for Castle model.
 */
class CastleController extends MyController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'build' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Castle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBuild()
    {
        if (!Yii::$app->user->isGuest) {
            $model = new Castle();

            if ($model->load(Yii::$app->request->post())) {
                $model->userId = $this->viewer_id;
                if ($model->save()) {
                    return $this->renderJson($model);
                } else {
                    return $this->renderJsonError($model->getErrors());
                }
            } else {
                return $this->renderJsonError('Can not load castle info');
            }
        }
    }


    /**
     * Finds the Castle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Castle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Castle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
