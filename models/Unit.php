<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\Castle,
    app\models\UnitGroup,
    app\models\User;

/**
 * This is the model class for table "units".
 *
 * @property integer $userId
 * @property integer $protoId
 * @property integer $count 
 * @property integer $currentGroupId
 * @property integer $currentCastleId
 * @property integer $spawned
 * @property integer $lastSalary
 *
 * @property Castle $currentCastle
 * @property UnitGroup $currentGroup
 * @property User $user
 */
class Unit extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'protoId'], 'required'],
            [['userId', 'protoId', 'count', 'currentGroupId', 'currentCastleId', 'spawned', 'lastSalary'], 'integer'],
            [['userId', 'protoId', 'currentGroupId', 'currentCastleId'], 'unique', 'targetAttribute' => ['userId', 'protoId', 'currentGroupId', 'currentCastleId'], 'message' => Yii::t('app','The combination of User ID, Proto ID, Current Group ID and Current Castle ID has already been taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'count' => Yii::t('app', 'Count'), 
            'currentGroupId' => Yii::t('app', 'Current Group ID'),
            'currentCastleId' => Yii::t('app', 'Current Castle ID'),
            'spawned' => Yii::t('app', 'Spawned'),
            'lastSalary' => Yii::t('app', 'Last Salary'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'userId',
            'protoId',
            'count',
            'spawned'
        ];
        
        if ($owner) {
            $attributes = array_merge($attributes, [
                'currentGroupId',
                'currentCastleId',
                'lastSalary'
            ]);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentCastle()
    {
        return $this->hasOne(Castle::className(), ['id' => 'currentCastleId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentGroup()
    {
        return $this->hasOne(UnitGroup::className(), ['id' => 'currentGroupId']);
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
            $this->spawned = time();
            $this->lastSalary = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public static function primaryKey()
    {
        return ['userId', 'protoId', 'currentGroupId', 'currentCastleId'];
    }

}
