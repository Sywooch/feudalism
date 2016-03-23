<?php

namespace app\controllers;

use Yii,
    app\models\Castle,
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
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBuild()
    {
        if ($this->user->isHaveMoneyForAction('castle', 'build')) {

            $model = new Castle();
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load(Yii::$app->request->post())) {
                $model->userId = $this->viewer_id;
                $model->fortification = 1;
                $model->quarters = 1;
                if ($model->save()) {
                    $this->user->payForAction('castle', 'build');
                    if ($this->user->addExperienceForAction('castle', 'build', [], true)) {
                        $transaction->commit();
                        return $this->renderJson($model);
                    } else {
                        return $this->renderJsonError($this->user->getErrors());
                    }
                } else {
                    return $this->renderJsonError($model->getErrors());
                }
            } else {
                return $this->renderJsonError(Yii::t('app','Can not load castle info'));
            }
        } else {
            return $this->renderJsonError(Yii::t('app','You haven`t money'));
        }
    }
    
    /**
     * Build a new fortification for Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionFortificationIncrease($id)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
        
        // Юзер — владелец замка
        if ($model->userId === $this->viewer_id) {
            
            // Расширение фортификаций доступно для замка
            if ($model->canFortificationIncreases) {
                $current = $model->fortification;
                
                // У юзера достаточно денег для расширения
                if ($this->user->isHaveMoneyForAction('castle', 'fortification-increase', ['current' => $current])) {
                    $model->fortification++;
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {
                        $this->user->payForAction('castle', 'fortification-increase', ['current' => $current]);
                        if ($this->user->addExperienceForAction('castle', 'fortification-increase', ['current' => $current], true)) {
                            $transaction->commit();
                            return $this->renderJsonOk();
                        } else {
                            return $this->renderJsonError($this->user->getErrors());
                        }
                    } else {
                        return $this->renderJsonError($model->getErrors());
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
    
    /**
     * Build a new quarters for Castle model.
     * @param integer $id
     * @return mixed
     */
    public function actionQuartersIncrease($id)
    {
        /* @var $model Castle */
        $model = Castle::findOne($id);
        
        // Юзер — владелец замка
        if ($model->userId === $this->viewer_id) {
            
            // Расширение казарм доступно для замка
            if ($model->canQuartersIncreases) {
                $current = $model->quarters;
                
                // У юзера достаточно денег для расширения
                if ($this->user->isHaveMoneyForAction('castle', 'quarters-increase', ['current' => $current])) {
                    $model->quarters++;
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {
                        $this->user->payForAction('castle', 'quarters-increase', ['current' => $current]);
                        if ($this->user->addExperienceForAction('castle', 'quarters-increase', ['current' => $current], true)) {
                            $transaction->commit();
                            return $this->renderJsonOk();
                        } else {
                            return $this->renderJsonError($this->user->getErrors());
                        }
                    } else {
                        return $this->renderJsonError($model->getErrors());
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
