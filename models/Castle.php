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
 * @property integer $titleId
 * @property string $name
 * @property integer $fortification
 * @property integer $quarters
 * @property integer $quartersUsed
 * @property integer $builded 
 * @property integer $captured 
 *
 * @property Unit[] $units
 * @property User $user
 * @property User[] $users
 * @property Tile $tile
 * @property Title $title
 * 
 * @property string $userName
 * @property integer $userLevel
 * 
 * @property boolean $canFortificationIncreases 
 * @property boolean $canQuartersIncreases
 * @property boolean $canSpawnUnit
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
            [['userId', 'tileId', 'titleId', 'fortification', 'quarters', 'quartersUsed', 'builded', 'captured'], 'integer'],
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
            'tileId' => Yii::t('app', 'Tile ID'), 
            'titleId' => Yii::t('app', 'Title ID'), 
            'name' => Yii::t('app', 'Name'),
            'fortification' => Yii::t('app', 'Fortification'),
            'quarters' => Yii::t('app', 'Quarters'),
            'quartersUsed' => Yii::t('app', 'Quarters Used'),
            'builded' => Yii::t('app', 'Builded'), 
            'captured' => Yii::t('app', 'Captured'), 
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'userId',
            'name',
            'fortification',
            'quarters'
        ];
        if ($owner) {
            $attributes[] = 'quartersUsed';
        }
        return $attributes;
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
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getTitle() 
    {         
        return $this->hasOne(Title::className(), ['id' => 'titleId']);
    }
    
    public function getUserName()
    {
        return $this->user->genderedName;
    }
    
    public function getUserLevel()
    {
        return $this->user->level;
    }
    
    public function getCanFortificationIncreases()
    {
        return $this->userLevel > $this->fortification;
    }
    
    public function getCanQuartersIncreases()
    {
        return $this->userLevel > $this->quarters;
    }
    
    public function getCanSpawnUnit()
    {
        return $this->quartersUsed < $this->quarters;
    }

}
