<?php

namespace app\models;

use yii\db\ActiveRecord as YiiActiveRecord,
    yii\base\Exception;

/**
 * Надстройка над ActiveRecord
 * @property array $displayedAttributes
 */
abstract class ActiveRecord extends YiiActiveRecord
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
            $m = static::instantiate(array_merge($params,$paramsToCreate));
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
    
    /**
     * Возвращает массив имён аттрибутов, доступных для отображения клиенту
     * @param boolean $owner если true то расширенный список, доступный владельцу
     * @return array
     */
    public static function displayedAttributes($owner = false)
    {
        throw new Exception('ActiveRecord::displayedAttributes not overwrited in '.static::className());
    }
    
    /**
     * Возвращает массив доступных для отображения клиенту аттрибутов
     * @param boolean $owner
     * @return array
     */
    public function getDisplayedAttributes($owner = false, $displayedAttributes = [])
    {
        $values = [];
        $attrs = static::displayedAttributes($owner);
        if (count($displayedAttributes)) {
            $attrs = array_merge($attrs, $displayedAttributes);
        }
        foreach ($attrs as $attr) {
            $values[$attr] = $this->$attr;
        }
        return $values;
    }
    
}