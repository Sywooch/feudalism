<?php

namespace app\controllers;

use Yii,
    app\models\holdings\Castle,
    app\models\Tile,
    app\models\Unit,
    app\controllers\Controller,
    yii\web\NotFoundHttpException,
    yii\web\Response,
    yii\widgets\ActiveForm,
    yii\filters\AccessControl,
    yii\filters\VerbFilter;

/**
 * CastleController implements the CRUD actions for Castle model.
 */
class CastleController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['build', 'fortification-increase', 'quarters-increase', 'spawn-unit'],
            'rules' => [
                [
                    'actions' => ['build', 'fortification-increase', 'quarters-increase', 'spawn-unit'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'build' => ['post'],
                'fortification-increase' => ['post'],
                'quarters-increase' => ['post'],
                'spawn-unit' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Displays a single Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex(int $id)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException(Yii::t('app', 'Castle not found'));
        }
        
        return $this->render('view', [
            'castle' => $model,
        ]);
    }

    /**
     * Creates a new Castle model.
     * @param integer $Castle
     * @return mixed
     */
    public function actionBuild()
    {
        $Castle = Yii::$app->request->post('Castle');
        $tile = Tile::findOne($Castle['tileId']);
        if (is_null($tile)) {
            return $this->renderJsonError(Yii::t('app','Invalid tile ID'));
        }
        
        $model = Castle::build($Castle['name'], $this->user, $tile);
        if ($model->id) {
            
            if (is_null($this->user->position)) {
                $this->user->link('currentHolding', $model);
            }
            
            return $this->redirect(['/castle/view', 'id' => $model->id]);
        } else {
            if (count($model->getErrors())) {
                return $this->renderJsonError($model->getErrors());
            } elseif (count($this->user->getErrors())) {
                return $this->renderJsonError($this->user->getErrors());
            } else {
                return $this->renderJsonError(Yii::t('app','Unknown error!'));
            }
        }
    }
    
    public function actionBuildForm(int $tileId)
    {
        $model = new Castle([
            'tileId' => $tileId,
            'protoId' => Castle::PROTOTYPE,
            'buildedUserId' => $this->viewer_id,
        ]);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('build-form',[
            'model' => $model,
        ]);
    }
    
    /**
     * Increase a fortification for Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionFortificationIncrease()
    {
        /* @var $model Castle */
        $model = Castle::findOne(Yii::$app->request->post('id'));
        if (is_null($model)) {
            return $this->renderJsonError(Yii::t('app','Invalid castle ID'));
        }
        
        if ($model->fortificationIncrease($this->user)) {
            return $this->renderJsonOk([
                'newValue' => $model->fortification
            ]);
        } else {
            if (count($model->getErrors())) {
                return $this->renderJsonError($model->getErrors());
            } elseif (count($this->user->getErrors())) {
                return $this->renderJsonError($this->user->getErrors());
            } else {
                return $this->renderJsonError(Yii::t('app','Unknown error!'));
            }
        }
    }
    
    /**
     * Increase a quarters for Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionQuartersIncrease()
    {        
        /* @var $model Castle */
        $model = Castle::findOne(Yii::$app->request->post('id'));
        if (is_null($model)) {
            return $this->renderJsonError(Yii::t('app','Invalid castle ID'));
        }
        
        if ($model->quartersIncrease($this->user)) {
            return $this->renderJsonOk([
                'newValue' => $model->quarters
            ]);
        } else {
            if (count($model->getErrors())) {
                return $this->renderJsonError($model->getErrors());
            } elseif (count($this->user->getErrors())) {
                return $this->renderJsonError($this->user->getErrors());
            } else {
                return $this->renderJsonError(Yii::t('app','Unknown error!'));
            }
        }
    }
    
    /**
     * Spawn new unit in castle
     * @param integer $id
     * @param integer $protoId
     */
    public function actionSpawnUnit()
    {
        /* @var $model Castle */
        $model = Castle::findOne(Yii::$app->request->post('id'));
        if (is_null($model)) {
            return $this->renderJsonError(Yii::t('app','Invalid castle ID'));
        }
        
        /* @var $unit Unit */
        $unit = $model->spawnUnit(Yii::$app->request->post('protoId'), $this->user);
        if (!$unit->isNewRecord && !count($model->getErrors())) {
            return $this->renderJson($unit);
        } else {
            if (count($unit->getErrors())) {
                return $this->renderJsonError($unit->getErrors());
            } elseif (count($model->getErrors())) {
                return $this->renderJsonError($model->getErrors());
            } elseif (count($this->user->getErrors())) {
                return $this->renderJsonError($this->user->getErrors());
            } else {
                return $this->renderJsonError(Yii::t('app','Unknown error!'));
            }
        }
        
    }
    
}
