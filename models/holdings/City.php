<?php

namespace app\models\holdings;

use Yii;

/**
 * 
 */
class City extends Holding
{
    
    const PROTOTYPE = Holding::PROTOTYPE_CITY;
    
    
    public function getFullName()
    {
        return Yii::t('app', "City of {0}", [$this->name]);
    }
        
    /**
     * return integer
     */
    public function calcTitleSize()
    {
        return $this->population*1;
    }
    
}
