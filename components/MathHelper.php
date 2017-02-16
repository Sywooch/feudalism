<?php

namespace app\components;

use app\models\Tile;

/**
 * Полезные математические штуки
 *
 * @author ilya
 */
abstract class MathHelper {
    
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
    
    /**
     * Бросает виртуальный дайс
     * @param integer $d
     * @return integer
     */
    public static function dice($d = 6)
    {
        return mt_rand(1, $d);
    }
    
    /**
     * 
     * @param integer $d
     * @param integer $count
     * @return integer
     */
    public static function multipleDice($d = 6, $count = 2)
    {
        $sum = 0;
        for ($i = 0; $i < $count; $i++) {
            $sum += self::dice($d);
        }
        
        return $sum;
    }
    
    /**
     * 
     * @param integer $d
     * @return integer
     */
    public static function fudgeDice($d = 1)
    {
        return mt_rand(-1*$d, $d);
    }
    
    /**
     * 
     * @param integer $d
     * @param integer $count
     * @return integer
     */
    public static function multipleFudgeDice($d = 1, $count = 4)
    {
        $sum = 0;
        for ($i = 0; $i < $count; $i++) {
            $sum += self::fudgeDice($d);
        }
        
        return $sum;
    }
    
    /**
     * Округляет число до последнего знака перед запятой
     * 1234 -> 1000, 54321 -> 60000
     * @param integer $number
     * @param boolean $ceil если true то округляет в большую сторону
     * @return integer
     */
    public static function aroundNumber($number, $ceil = false)
    {
        $p = pow(10,strlen($number)-1);
        return ($ceil ? ceil($number/$p) : round($number/$p))*$p;        
    }
    
    /**
     * 
     * @param Tile $a
     * @param Tile $b
     * @return double
     */
    public static function calcDist(Tile $a, Tile $b)
    {
        return hypot($a->x - $b->x,$a->y - $b->y);
    }
    
}
