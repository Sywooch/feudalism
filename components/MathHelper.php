<?php

namespace app\components;

/**
 * Description of MyMathHelper
 *
 * @author ilya
 */
class MathHelper {
    
    /**
     * Число е
     */
    const E = 2.71828182846;
    
    /**
     * Константы для halfExpo
     */
    const HE_C = -1.28734;
    const HE_K = 0.841467;

    /**
     * "Половина экспоненты" при <0.5 возвр. 0, при 0.5 возвр. 0.1, при >=1 возвр. 1
     * @param float $x
     * @return float
     */
    public static function halfExpo($x)
    {
        if ($x < 0.5) {
            return 0;
        }
        if ($x > 1) {
            return 1;
        }
        return static::HE_K*pow(static::E, $x) + static::HE_C;
    }
    
}
