<?php

namespace app\models\titles;

use Yii,
    app\models\User;

/**
 * Баронство
 *
 * @author i.gorohov
 */
class Barony extends Title {
    
    const LEVEL = Title::LEVEL_BARONY;
    
    public static function getFullName()
    {
        return Yii::t('app', '{} barony', [$this->name]);
    }
    
    public static function getUserName()
    {
        return Yii::t('app', '{0,plural,='.User::GENDER_FEMALE.'{Baroness} ='.User::GENDER_MALE.'{Baron}} other{Baron}} {1}',[$this->user->gender, $this->user->name]);
    }
    
}
