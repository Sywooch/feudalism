<?php

namespace app\models\titles;

use Yii,
    app\models\holdings\Holding,
    app\models\User;

/**
 * Баронство
 *
 * @author i.gorohov
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
        return Yii::t('app', "{0,select,".User::GENDER_FEMALE."{Baroness} ".User::GENDER_MALE."{Baron} other{Baron}} [{2}] {1}", [$user->gender, $user->name, $user->level]);
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
    
}
