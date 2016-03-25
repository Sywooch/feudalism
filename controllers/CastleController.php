<?php

namespace app\controllers;

use Yii,
    app\models\Castle,
    app\models\Tile,
    app\models\Unit,
    app\controllers\Controller,
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
            ],
        ];

        return $behaviors;
    }

    /**
     * Displays a single Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
        $isOwner = $model->userId === $this->viewer_id;
        if (is_null($model)) {
            return $this->renderJsonError(Yii::t('app','Castle not found'));
        } else {
            return $this->renderJson($model->getDisplayedAttributes($isOwner, [
                'userName',
                'userLevel'
            ]));
        }
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
            return $this->renderJson($model);
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
     * Increase a fortification for Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionFortificationIncrease($id)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
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
    public function actionQuartersIncrease($id)
    {        
        /* @var $model Castle */
        $model = Castle::findOne($id);
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
    public function actionSpawnUnit($id, $protoId)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
        
        // Юзер — владелец замка
        if ($model->userId === $this->viewer_id) {
            
            // Есть неиспользованные казармы
            if ($model->canSpawnUnit) {
                
                // У юзера достаточно денег для создания
                if ($this->user->isHaveMoneyForAction('unit', 'spawn', ['protoId' => $protoId])) {
                    
                    $transaction = Yii::$app->db->beginTransaction();
                    
                    $unit = new Unit([
                        'userId' => $this->viewer_id,
                        'protoId' => $protoId,
                        'currentCastleId' => $model->id
                    ]);
                    if ($unit->save()) {
                        $this->user->payForAction('unit', 'spawn', ['protoId' => $protoId]);
                        if ($this->user->addExperienceForAction('unit', 'spawn', ['protoId' => $protoId], true)) {
                            $model->quartersUsed++;
                            if ($model->save()) {
                                $transaction->commit();
                                return $this->renderJsonOk();
                            } else {
                                return $this->renderJsonError($model->getErrors());
                            }
                        } else {
                            return $this->renderJsonError($this->user->getErrors());
                        }  
                    } else {
                        return $this->renderJsonError($unit->getErrors());
                    }                    
                } else {
                    return $this->renderJsonError(Yii::t('app','You haven`t money'));
                }               
            } else {
                return $this->renderJsonError(Yii::t('app','Action not allowed'));
            }
        } else {
            return $this->renderJsonError(Yii::t('app','Action not allowed'));
        }
        
    }
    
}
