<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\UnitGroup,
    app\models\Castle,
    yii\base\Exception;

/**
 * This is the model class for table "tiles".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $biome
 * @property integer $elevation
 * @property integer $temperature
 * @property integer $rainfall
 * @property integer $drainage
 *
 * @property Castle[] $castles
 * @property UnitGroup[] $unitGroups
 */
class Tile extends MyModel
{
    
    /**
     * Неопределённый тип биома
     */
    const BIOME_UNDEFINED = 0;
    
    /**
     * Холодный океан
     */
    const BIOME_ARCTIC_OCEAN = 1;
    
    /**
     * Умеренный океан
     */
    const BIOME_TEMPERATE_OCEAN = 2;
    
    /**
     * Тропический океан
     */
    const BIOME_TROPICAL_OCEAN = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'biome'], 'required'],
            [['x', 'y', 'biome', 'elevation', 'temperature', 'rainfall', 'drainage'], 'integer'],
            [['x', 'y'], 'unique', 'targetAttribute' => ['x', 'y'], 'message' => Yii::t('app','The combination of X and Y has already been taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'x' => Yii::t('app', 'X'),
            'y' => Yii::t('app', 'Y'),
            'biome' => Yii::t('app', 'Biome'),
            'elevation' => Yii::t('app', 'Biome'),
            'temperature' => Yii::t('app', 'Biome'),
            'rainfall' => Yii::t('app', 'Biome'),
            'drainage' => Yii::t('app', 'Biome')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCastles()
    {
        return $this->hasMany(Castle::className(), ['tileId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitGroups()
    {
        return $this->hasMany(UnitGroup::className(), ['tileId' => 'id']);
    }
    
    /**
     * Находит или создаёт новый обьект тайла по переданным координатам
     * @param integer $x
     * @param integer $y
     * @param integer $biome Const. biome type
     * @param boolean $save autosave after create
     * @return \self
     * @throws Exception
     */
    public static function getByCoords($x, $y, $biome = self::BIOME_UNDEFINED, $save = true) {
        $model = self::find()->where(['x' => $x, 'y' => $y])->one();
        if (is_null($model)) {
            $model = new self([
                'x' => $x,
                'y' => $y,
                'biome' => $biome
            ]);
            if ($save && !$model->save()) {
                throw new Exception("Can not save new tile [{$x}, {$y}] model!");
            }
        }
        return $model;
    }
}
