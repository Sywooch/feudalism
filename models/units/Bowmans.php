<?php

namespace app\models\units;

use Yii;

/**
 * 
 */
class Bowmans extends Unit
{
    
    const PROTOTYPE = Unit::PROTOTYPE_BOWMANS;
        
    public static function getPrototypeName()
    {
        return Yii::t('app', 'Bowmans');
    }

}
