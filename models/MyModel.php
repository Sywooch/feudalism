<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Надстройка над ActiveRecord
 */
abstract class MyModel extends ActiveRecord
{
    
    /**
     * Находит или создаёт модель по параметрам
     * @param array $params параметры по которым искать
     * @param array $paramsToCreate параметры, которые будут назначены новосозданной модели
     * @param array $paramsToLoad параметры, которые будут назначены найденой модели (false чтобы использовать $paramsToCreate)
     * @param boolean $save сохранить ли после этого
     * @return static
     */
    public static function findOrCreate($params, $paramsToCreate = [], $paramsToLoad = [], $save = false)
    {
        $m = static::find()->where($params)->one();
        if (is_null($m)) {
            $m = new static(array_merge($params,$paramsToCreate));
        } else {
            if ($paramsToLoad === false) {
                $paramsToLoad = $paramsToCreate;
            }
            $m->load($paramsToLoad, '');
        }
        if ($save) {
            $m->save();
        }
        
        return $m;
    }
    
}