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
 * @property integer $type
 *
 * @property Castle[] $castles
 * @property UnitGroup[] $unitGroups
 */
class Tile extends MyModel
{
    
    const TYPE_UNDEFINED = 0;
    const TYPE_ARCTIC_OCEAN = 1;
    const TYPE_TEMPERATE_OCEAN = 2;
    const TYPE_TROPICAL_OCEAN = 3;
    
    
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
            [['x', 'y', 'type'], 'required'],
            [['x', 'y', 'type'], 'integer'],
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
            'type' => Yii::t('app', 'Type'),
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
     * @return \self
     * @throws Exception
     */
    public static function getByCoords($x, $y, $type = self::TYPE_UNDEFINED, $save = true) {
        $model = self::find()->where(['x' => $x, 'y' => $y])->one();
        if (is_null($model)) {
            $model = new self([
                'x' => $x,
                'y' => $y,
                'type' => $type
            ]);
            if ($save && !$model->save()) {
                throw new Exception("Can not save new tile [{$x}, {$y}] model!");
            }
        }
        return $model;
    }
}
