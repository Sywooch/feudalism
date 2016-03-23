<?php

namespace app\components;

use Yii,
    app\components\MathHelper;

/**
 * Методы для подсчёта XP
 *
 * @author i.gorohov
 */
abstract class ExperienceCalculator {
    
    /**
     * Массив [уровень => опыт] для захардкоженных уровней 0-8
     * @return array
     */
    public static function getHardcodedLevels()
    {
        return [
            0 => 0,
            1 => 10000,
            2 => 30000,
            3 => 70000,
            4 => 150000,
            5 => 300000,
            6 => 600000,
            7 => 1200000,
            8 => 2400000    
        ];
    }
    
    /**
     * Необходимое число опыта для уровней 9+
     * @param integer $level
     * @return integer
     */
    public static function getExperienceForBigLevel($level)
    {
        return MathHelper::aroundNumber(pow(2,$level)*9375,true);
    }
    
    /**
     * Необходимое число опыта для уровня
     * @param integer $level
     * @return integer
     */
    public static function getExperienceByLevel($level)
    {
        if ($level < 9) {
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
        while ($exp > static::getExperienceByLevel($level)) {
            $level++;
        }
        
        return $level;
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
