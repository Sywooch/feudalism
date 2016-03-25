<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord;

/**
 * Титулы. Таблица "titles".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property integer $userId
 * @property integer $suzerainId
 * @property integer $created
 * @property integer $createdByUserId
 * @property integer $captured
 *
 * @property Castle[] $castles
 * @property Tile[] $tiles
 * @property User $createdByUser
 * @property Title $suzerain
 * @property Title[] $vassals
 * @property User $user
 */
class Title extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'titles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level', 'created'], 'required'],
            [['level', 'userId', 'suzerainId', 'created', 'createdByUserId', 'captured'], 'integer'],
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
            'name' => Yii::t('app', 'Name'),
            'level' => Yii::t('app', 'Level'),
            'userId' => Yii::t('app', 'User ID'),
            'suzerainId' => Yii::t('app', 'Suzerain ID'),
            'created' => Yii::t('app', 'Created'),
            'createdByUserId' => Yii::t('app', 'Created By User ID'),
            'captured' => Yii::t('app', 'Captured'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'name',
            'level',
            'userId',
            'suzerainId',
            'created',
            'createdByUserId',
            'captured'
        ];
        
        return $attributes;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCastles()
    {
        return $this->hasMany(Castle::className(), ['titleId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['titleId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'createdByUserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuzerain()
    {
        return $this->hasOne(Title::className(), ['id' => 'suzerainId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVassals()
    {
        return $this->hasMany(Title::className(), ['suzerainId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created = time();
        }
                
        if ($this->userId !== $this->oldAttributes['userId']) {
            $this->captured = $this->userId ? time() : null;
        }
        
        return parent::beforeSave($insert);
    }

}
