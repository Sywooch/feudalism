<?php

namespace app\controllers;

use Yii,
    app\models\titles\Title,
    app\models\titles\Barony,
    app\models\holdings\Holding,
    app\controllers\Controller,
    yii\filters\AccessControl,
    yii\filters\VerbFilter;

/**
 * Description of TitleController
 *
 * @author i.gorohov
 */
class TitleController extends Controller
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view', 'create-barony'],
            'rules' => [
                [
                    'actions' => ['view', 'create-barony'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create-barony' => ['post'],
            ],
        ];

        return $behaviors;
    }
    
    /**
     * Displays a single Title model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /* @var $model Title */
        $model = Title::findOne($id);
        if (is_null($model)) {
            return $this->renderJsonError(Yii::t('app','Title not found'));
        } else {
            return $this->renderJson($model->getDisplayedAttributes($model->isOwner($this->user), [
                'userName',
                'userLevel'
            ]));
        }
    }
    
    /**
     * Creates a new Barony model.
     * @param array $Title
     * @param integer $holdingId
     * @return mixed
     */
    public function actionCreateBarony()
    {
        $Title = Yii::$app->request->post('Title');
        $holding = Holding::findOne(Yii::$app->request->post('holdingId'));
        if (is_null($holding)) {
            return $this->renderJsonError(Yii::t('app','Invalid holding ID'));
        }
        
        $model = Barony::create($Title['name'], $this->user, $holding);
        if ($model->id) {            
            if (is_null($this->user->primaryTitle)) {
                $this->user->link('primaryTitle', $model);
            }            
            return $this->renderJson($model);
        } else {
            if (count($model->getErrors())) {
                return $this->renderJsonError($model->getErrors());
            } elseif (count($this->user->getErrors())) {
                return $this->renderJsonError($this->user->getErrors());
            } elseif (count($holding->getErrors())) {
                return $this->renderJsonError($holding->getErrors());
            } else {
                return $this->renderJsonError(Yii::t('app','Unknown error!'));
            }
        }
    }
}
