<?php

namespace app\models\units;

use Yii;

/**
 * 
 */
class Horsemans extends Unit
{
    
    const PROTOTYPE = Unit::PROTOTYPE_HORSEMANS;
        
    public static function getPrototypeName()
    {
        return Yii::t('app', 'Horsemans');
    }

}
