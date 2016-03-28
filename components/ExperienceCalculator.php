<?php

namespace app\components;

use Yii,
    yii\base\Exception;

/**
 * Методы для подсчёта XP
 *
 * @author i.gorohov
 */
abstract class ExperienceCalculator {
    
    /**
     * Массив [уровень => опыт] для захардкоженных уровней 0-10
     * @return array
     */
    public static function getHardcodedLevels()
    {
        return [
            0 => 0,
            1 => 700,
            2 => 1500
        ];
    }
        
    /**
     * Необходимое число опыта для высоких (незахардкоженных) уровней
     * @param integer $level
     * @return integer
     */
    public static function getExperienceForBigLevel($level)
    {
        $p = pow(10,floor($level/3)+2);        
        switch ($level%3) {
            case 0:
                return 3*$p;
            case 1:
                return 7*$p;
            case 2:
                return 15*$p;
        }
    }
    
    /**
     * Необходимое число опыта для уровня
     * @param integer $level
     * @return integer
     */
    public static function getExperienceByLevel($level)
    {
        if ($level < count(static::getHardcodedLevels())) {
            return static::getHardcodedLevels()[$level];
        } else {
            return static::getExperienceForBigLevel($level);
        }
    }
    
    /**
     * Набранный уровень по числу опыта
     * @param integer $exp
     * @return integer
     */
    public static function getLevelByExperience($exp)
    {
        $level = 0;
        while ($exp >= static::getExperienceByLevel($level)) {
            $level++;
        }
        
        return $level-1;
    }
    
    /**
     * Число опыта за действие
     * @param string $category
     * @param string $action
     * @param array $params
     * @return integer
     */
    public static function get($category, $action = 0, $params = [])
    {
        if (isset(Yii::$app->params['experience'][$category][$action])) {
            if (is_callable(Yii::$app->params['experience'][$category][$action])) {
                return Yii::$app->params['experience'][$category][$action]($params);
            } else {
                return Yii::$app->params['experience'][$category][$action];
            }
        } else {
            throw new Exception("Experience for «{$category} — {$action}» not setted! Check @app/config/experience");
        }
    }
    
}
