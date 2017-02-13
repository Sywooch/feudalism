<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\Unit,
    app\models\User,
    app\models\Tile;

/**
 * This is the model class for table "unitGroups".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $tileId
 * @property double $lat
 * @property double $lng
 * @property string $name
 *
 * @property Tile $tile
 * @property User $user
 * @property Unit[] $units
 * @property User[] $users
 */
class UnitGroup extends ActiveRecord implements Position
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unitGroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'tileId', 'lat', 'lng'], 'required'],
            [['userId', 'tileId'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'tileId' => Yii::t('app', 'Tile ID'),
            'lat' => Yii::t('app', 'Latitude'),
            'lng' => Yii::t('app', 'Longitude'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [
            'id',
            'userId',
            'tileId',
            'lat',
            'lng',
            'name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTile()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['currentGroupId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['currentGroupId' => 'id']);
    }

}