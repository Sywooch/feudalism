<?php

namespace app\models\units;

use Yii,
    app\models\ActiveRecord,
    app\models\User,
    app\models\holdings\Holding;

/**
 * This is the model class for table "units".
 *
 * @property integer $userId
 * @property integer $protoId
 * @property integer $count 
 * @property integer $currentGroupId
 * @property integer $currentHoldingId
 * @property integer $spawned
 * @property integer $lastSalary
 *
 * @property Holding $currentHolding
 * @property UnitGroup $currentGroup
 * @property User $user
 */
class Unit extends ActiveRecord
{
    /**
     * Переопределяется в наследниках
     */
    const PROTOTYPE = null;
    
    const PROTOTYPE_SWORDMANS = 1;
    
    const PROTOTYPE_BOWMANS = 2;
    
    const PROTOTYPE_HORSEMANS = 3;
    
    public function init()
    {
        $this->protoId = static::PROTOTYPE;
        parent::init();
    }

    public static function find()
    {
        return new UnitQuery(static::className(), ['protoId' => static::PROTOTYPE]);
    }

    public function beforeSave($insert)
    {
        $this->protoId = static::PROTOTYPE;
        
        if ($insert) {
            $this->spawned = time();
            $this->lastSalary = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public static function instantiate($row)
    {
        switch ((int)$row['protoId']) {
            case self::PROTOTYPE_SWORDMANS:
                return new Swordmans($row);
            case self::PROTOTYPE_BOWMANS:
                return new Bowmans($row);
            case self::PROTOTYPE_HORSEMANS:
                return new Horsemans($row);
            default:
                throw new Exception("Invalid unit prototype (ID:{$row['protoId']})!");
        }
    }
    
    public static function getList()
    {
        return [
            self::PROTOTYPE_SWORDMANS => Swordmans::getPrototypeName(),
            self::PROTOTYPE_BOWMANS => Bowmans::getPrototypeName(),
            self::PROTOTYPE_HORSEMANS => Horsemans::getPrototypeName(),
        ];
    }


    public function getName()
    {
        return $this->count . " " . mb_strtolower(static::getPrototypeName());
    }
    
    public function getFullName()
    {
        return $this->count . " " . mb_strtolower(static::getPrototypeName()) . " from " . $this->user->fullName;
    }
    
    public static function getPrototypeName()
    {
        throw new Exception("Method ".static::className()."::getPrototypeName() not overrided!");
    }

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
            [['userId', 'protoId', 'count', 'currentGroupId', 'currentHoldingId', 'spawned', 'lastSalary'], 'integer'],
            [['userId', 'protoId', 'currentGroupId', 'currentHoldingId'], 'unique', 'targetAttribute' => ['userId', 'protoId', 'currentGroupId', 'currentHoldingId'], 'message' => Yii::t('app','The combination of User ID, Proto ID, Current Group ID and Current Holding ID has already been taken.')]
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
            'currentHoldingId' => Yii::t('app', 'Current Holding ID'),
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
                'currentHoldingId',
                'lastSalary'
            ]);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentHolding()
    {
        return $this->hasOne(Holding::className(), ['id' => 'currentHoldingId']);
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
        
    public static function primaryKey()
    {
        return ['userId', 'protoId', 'currentGroupId', 'currentHoldingId'];
    }

}
