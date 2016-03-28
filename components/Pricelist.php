<?php

namespace app\components;

use Yii,
    yii\base\Exception;

/**
 * Description of Pricelist
 *
 * @author i.gorohov
 */
class Pricelist {
    
    /**
     * Возвращает стоимость действия
     * @param integer $category
     * @param integer $action
     * @param array $params
     * @return double
     * @throws Exception
     */
    public static function get($category, $action = 0, $params = [])
    {
        if (isset(Yii::$app->params['prices'][$category][$action])) {
            if (is_callable(Yii::$app->params['prices'][$category][$action])) {
                return Yii::$app->params['prices'][$category][$action]($params);
            } else {
                return Yii::$app->params['prices'][$category][$action];
            }
        } else {
            throw new Exception("Price for «{$category} — {$action}» not setted! Check @app/config/prices");
        }
    }
    
}
