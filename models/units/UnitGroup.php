<?php

namespace app\models\units;

use Yii,
    app\models\ActiveRecord,
    app\models\Position,
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
class UnitGroup extends ActiveRecord implements Position
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unitsGroups';
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
            'name',
            'coords',
        ];
    }
    
    public function getDisplayedAttributes($owner = false, $displayedAttributes = array()) {
        $ar = parent::getDisplayedAttributes($owner, $displayedAttributes);
        $ar['isOwner'] = $owner;
        return $ar;
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

    public function getCoords()
    {
        return [$this->tile->centerLat-0.03, $this->tile->centerLng+0.03];
    }
    
    /**
     * 
     * @return boolean
     */
    public function isOwner(User &$user)
    {
        return ((int)$this->userId === (int)$user->id);
    }
    
}