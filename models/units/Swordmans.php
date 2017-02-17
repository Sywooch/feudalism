<?php

namespace app\models\units;

use Yii;

/**
 * 
 */
class Swordmans extends Unit
{
    
    const PROTOTYPE = Unit::PROTOTYPE_SWORDMANS;
        
    public static function getPrototypeName()
    {
        return Yii::t('app', 'Swordmans');
    }

}
