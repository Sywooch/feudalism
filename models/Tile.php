<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\UnitGroup,
    app\models\Castle;

/**
 * This is the model class for table "tiles".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $z
 * @property integer $type
 *
 * @property Castle[] $castles
 * @property UnitGroup[] $unitGroups
 */
class Tile extends MyModel
{
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
            [['x', 'y', 'z', 'type'], 'required'],
            [['x', 'y', 'z', 'type'], 'integer'],
            [['x', 'y', 'z'], 'unique', 'targetAttribute' => ['x', 'y', 'z'], 'message' => 'The combination of X, Y and Z has already been taken.']
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
            'z' => Yii::t('app', 'Z'),
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
}
