<?php

namespace app\models\holdings;

use Yii,
    app\models\holdings\Holding,
    app\models\Unit,
    app\models\User,
    app\models\Tile;

/**
 * Замок феодала
 *
 */
class Castle extends Holding
{
    
    const PROTOTYPE = Holding::PROTOTYPE_CASTLE;
        
    /**
     * Строит новый замок со всеми необходимыми проверками
     * @param string $name
     * @param User $user
     * @param Tile $tile
     * @param double $lat
     * @param double $lng
     * @return self
     */
    public static function build($name, User &$user, Tile &$tile, $lat, $lng)
    {
        $model = new self();
        if (!$user->isHaveMoneyForAction('castle', 'build')) {
            $model->addError('userId', Yii::t('app','You haven`t money'));
        }
        
        if ($tile->getHolding()->count()) {
            $model->addError('tileId', Yii::t('app', 'Tile allready occupied'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load([
            'name' => $name,
            'buildedUserId' => $user->id,
            'tileId' => $tile->id,
            'lat' => $lat,
            'lng' => $lng,
            'fortification' => 1,
            'quarters' => 1
        ],'') && $model->save()) {
            if ($user->makeAction('castle', 'build', [], true)) {
                $transaction->commit();
            } 
        }
        return $model;
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function fortificationIncrease(User &$user)
    {        
        // Юзер — владелец замка
        if ($this->isOwner($user)) {
            $this->addError('user', Yii::t('app','Action not allowed'));
            return false;
        }
            
        // Расширение фортификаций доступно для замка
        if (!$this->canFortificationIncreases) {            
            $this->addError('fortification', Yii::t('app','Action not allowed'));
            return false;
        }
        
        $current = $this->fortification;
                
        // У юзера достаточно денег для расширения
        if (!$user->isHaveMoneyForAction('castle', 'fortification-increase', ['current' => $current])) {
            $this->addError('user', Yii::t('app','You haven`t money'));
            return false;
        }
        
        $this->fortification++;
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->save()) {
            if ($user->makeAction('castle', 'fortification-increase', ['current' => $current], true)) {
                $transaction->commit();
                return true;
            }
        }        
        return false;
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function quartersIncrease(User &$user)
    {        
        // Юзер — владелец замка
        if ($this->isOwner($user)) {
            $this->addError('user', Yii::t('app','Action not allowed'));
            return false;
        }
            
        // Расширение фортификаций доступно для замка
        if (!$this->canQuartersIncreases) {            
            $this->addError('quarters', Yii::t('app','Action not allowed'));
            return false;
        }
        
        $current = $this->quarters;
                
        // У юзера достаточно денег для расширения
        if (!$user->isHaveMoneyForAction('castle', 'quarters-increase', ['current' => $current])) {
            $this->addError('user', Yii::t('app','You haven`t money'));
            return false;
        }
        
        $this->quarters++;
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->save()) {
            if ($user->makeAction('castle', 'quarters-increase', ['current' => $current], true)) {
                $transaction->commit();
                return true;
            }
        }        
        return false;
    }
    
    /**
     * 
     * @param integer $protoId
     * @param User $user
     * @return Unit
     */
    public function spawnUnit($protoId, User &$user)
    {

        $unit = Unit::findOrCreate([
            'userId' => $user->id,
            'protoId' => $protoId,
            'currentHoldingId' => $this->id
        ]);
        
        // Юзер — владелец замка
        if ($this->isOwner($user)) {
            $this->addError('user', Yii::t('app','Action not allowed'));
        }
            
        // Есть неиспользованные казармы
        if (!$this->canSpawnUnit) {
            $this->addError('quartersUsed', Yii::t('app','Action not allowed'));
        }
                
        // У юзера достаточно денег для создания
        if (!$user->isHaveMoneyForAction('unit', 'spawn', ['protoId' => $protoId])) {
            $this->addError('user', Yii::t('app','You haven`t money'));
        }
        
        if (!count($this->getErrors())) {
                    
            $transaction = Yii::$app->db->beginTransaction();
            $unit->count++;
            if ($unit->save() && $user->makeAction('unit', 'spawn', ['protoId' => $protoId], true)) {
                $this->quartersUsed++;
                if ($this->save()) {
                    $transaction->commit();
                } 
            }
        }

        return $unit;
    }
        
    public function getFullName()
    {
        return Yii::t('app', "Castle of {0}", [$this->name]);
    }
        
    /**
     * return integer
     */
    public function calcTitleSize()
    {
        return $this->fortification*1;
    }

}
