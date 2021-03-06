<?php

namespace app\models\titles;

use Yii,
    app\components\MathHelper,
    app\models\holdings\Holding,
    app\models\User,
    app\models\Tile;

/**
 * Баронство
 * 
 * @property Holding $holding
 *
 */
class Barony extends Title {
    
    const LEVEL = Title::LEVEL_BARONY;
    
    public function getFullName()
    {
        return Yii::t('app', "{0} barony", [$this->name]);
    }
    
    public function getUserName(User &$user = null)
    {
        if (is_null($user)) {
            $user = &$this->user;
        }
        return Yii::t('app', "{0,select,".User::GENDER_FEMALE."{Baroness} ".User::GENDER_MALE."{Baron} other{Baron}} {1}", [$user->gender, $user->getLeveledName()]);
    }
    
    /**
     * 
     * @param string $name
     * @param User $user
     * @param Holding $holding
     */
    public static function create($name, User &$user, Holding &$holding)
    {
        $model = new self();
        if (!$user->isHaveMoneyForAction('title', 'create', ['level' => static::LEVEL])) {
            $model->addError('userId', Yii::t('app','You haven`t money'));
        }
        
        if ($holding->titleId) {
            $model->addError('titleId', Yii::t('app', 'Holding allready have a title'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load([
            'name' => $name,
            'userId' => $user->id,
            'suserainId' => ($user->primaryTitle ? $user->primaryTitle->suzerainId : null),
            'createdByUserId' => $user->id
        ],'') && $model->save()) {
            if ($user->makeAction('title', 'create', ['level' => static::LEVEL], true)) {
                $transaction->commit();
            } 
        }
        return $model;
    }
    
    public function getHolding() {
        return $this->hasOne(Holding::className(), ['titleId' => 'id']);
    }
    
    public function getClaimedTerritory()
    {
        $capital = $this->holding;
        $capitalTile = $capital->tile;
        $size = $capital->calcTitleSize();
        return Tile::getSpiral($capitalTile, $size);
    }
    
    public function calcTaxrent()
    {
        $capital = $this->holding;
        $size = $capital->calcTitleSize();
        $tilesCount = 1;
        for ($i = 1; $i <= $size; $i++) {
            $tilesCount += 6*$i;
        }
        
        return $capital->population + $tilesCount;
    }
        
}
