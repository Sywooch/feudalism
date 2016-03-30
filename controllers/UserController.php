<?php

namespace app\controllers;

use Yii,
    app\models\User,
    app\controllers\Controller,
    yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view'],
            'rules' => [
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }
    
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = User::findIdentity($id);
        $owner = $this->viewer_id === $model->id;
        if (!is_null($model)) {
            if (Yii::$app->request->isAjax) {
                return $this->renderPartial('view', ['model' => $model, 'isOwner' => $owner]);
            } else {
                return $this->render('view', ['model' => $model, 'isOwner' => $owner]);
            }
        } else {
            return $this->renderJsonError(Yii::t('app','User not found'));
        }
    }

    
}
