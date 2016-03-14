<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\Unit,
    app\models\User,
    app\models\Tile;

/**
 * This is the model class for table "castles".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $tileId 
 * @property string $name
 * @property integer $fortification
 * @property integer $quarters
 * @property integer $quartersUsed
 *
 * @property Unit[] $units
 * @property User $user
 * @property User[] $users
 * @property Tile $tile
 */
class Castle extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'castles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'tileId', 'fortification', 'quarters', 'quartersUsed'], 'integer'],
            [['tileId', 'name'], 'required'],
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
            'name' => Yii::t('app', 'Name'),
            'fortification' => Yii::t('app', 'Fortification'),
            'quarters' => Yii::t('app', 'Quarters'),
            'quartersUsed' => Yii::t('app', 'Quarters Used'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['currentCastleId' => 'id']);
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['currentCastleId' => 'id']);
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getTile() 
    { 
        return $this->hasOne(Tile::className(), ['id' => 'tileId']); 
    } 
}
