<?php

namespace app\models\titles;

use Yii,
    yii\base\Exception,
    app\models\ActiveRecord,
    app\models\Tile,
    app\models\User,
    app\models\holdings\Holding;

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
 * @property Holding[] $holdings
 * @property Tile[] $tiles
 * @property User $createdByUser
 * @property Title $suzerain
 * @property Title[] $vassals
 * @property User $user
 */
class Title extends ActiveRecord
{
	
    /**
     * Баронство
     */
    const LEVEL_BARONY = 1;
    
    const LEVEL = null;
    
    public static function getLeveledName()
    {
        throw new Exception(static::className()."::getLeveledName() not overrided!");
    }
    
    public static function getUserName()
    {
        throw new Exception(static::className()."::getUserName() not overrided!");
    }

    public function init()
    {
        $this->level = static::PROTOTYPE;
        parent::init();
    }

    public static function find()
    {
        return new TitleQuery(get_called_class(), ['level' => static::LEVEL]);
    }

    public function beforeSave($insert)
    {
        $this->level = static::PROTOTYPE;
        
        if ($insert) {
            $this->created = time();
        }
                
        if ($this->userId !== $this->oldAttributes['userId']) {
            $this->captured = $this->userId ? time() : null;
        }
                
        return parent::beforeSave($insert);
    }
    
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
    public function getHoldings()
    {
        return $this->hasMany(Holding::className(), ['titleId' => 'id']);
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
    
    /**
     * 
     * @return boolean
     */
    public function isOwner(User &$user)
    {
        return ($this->userId === $user->id);
    }
}
