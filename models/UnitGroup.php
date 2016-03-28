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
 * @property string $name
 *
 * @property Tile $tile
 * @property User $user
 * @property Unit[] $units
 * @property User[] $users
 */
class UnitGroup extends ActiveRecord
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
            [['userId', 'tileId'], 'required'],
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
            'name' => Yii::t('app', 'Name'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [
            'id',
            'userId',
            'tileId',
            'name'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTile()
    {
        return $this->hasOne(Tiles::className(), ['id' => 'tileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'userId']);
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
