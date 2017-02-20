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
 * @property integer $lastTaxrent
 *
 * @property Holding[] $holdings
 * @property Tile[] $tiles
 * @property User $createdByUser
 * @property Title $suzerain
 * @property Title[] $vassals
 * @property User $user
 * 
 * @property integer $userLevel
 * @property string $userName
 * @property string $fullName
 * @property integer $nextTaxrent
 * @property boolean $isCanBeTaxrented
 */
class Title extends ActiveRecord
{
	
    /**
     * Баронство
     */
    const LEVEL_BARONY = 1;
    
    const LEVEL_COUNT = 2;
    
    const LEVEL_DUKE = 3;
    
    const LEVEL_KING = 4;
    
    const LEVEL_EMPEROR = 5;
    
    const LEVEL = null;
    
    public function getFullName()
    {
        throw new Exception(static::className()."::getFullName() not overrided!");
    }
    
    public function getUserName()
    {
        throw new Exception(static::className()."::getUserName() not overrided!");
    }

    public function init()
    {
        $this->level = static::LEVEL;
        parent::init();
    }

    public static function find()
    {
        return new TitleQuery(get_called_class(), ['level' => static::LEVEL]);
    }

    public function beforeSave($insert)
    {
        $this->level = static::LEVEL;
        
        if ($insert) {
            $this->created = time();
        }
                
        if (!(isset($this->oldAttributes['userId'])) || $this->userId !== $this->oldAttributes['userId']) {
            $this->captured = $this->userId ? time() : null;
        }
                
        return parent::beforeSave($insert);
    }
    
    public static function instantiate($row)
    {
            $className = "app\\models\\titles\\";
            switch (intval($row["level"])) {
                case self::LEVEL_BARONY:
                    $className .= "Barony";
                    break;
                default:
                    throw new Exception("Invalid title level ({$row['level']}) in model ID{$row['id']}!");
            }
        return new $className($row);
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
            [['name', 'level'], 'required'],
            [['level', 'userId', 'suzerainId', 'created', 'createdByUserId', 'captured', 'lastTaxrent'], 'integer'],
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
            'lastTaxrent' => Yii::t('app', 'Last Tax Rent'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'name',
            'fullName',
            'level',
            'userId',
            'suzerainId',
            'created',
            'createdByUserId',
            'captured'
        ];
        
        if ($owner) {
            $attributes[] = 'lastTaxrent';
        }
        
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
    
    public function getUserLevel()
    {
        return $this->user->level;
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function isPrimaryTitle(User &$user)
    {
        return $this->id === $user->primaryTitleId;
    }
    
    /**
     * 
     * @throws Exception
     * @return Tile[]
     */
    public function getClaimedTerritory()
    {
        throw new Exception("Method ".static::className()."::claimTerritory() not overrided!");
    }
    
    public function calcTaxrent()
    {
        throw new Exception("Method ".static::className()."::calcTaxrent() not overrided!"); 
    }
    
    public function getPolygon()
    {
        $filename = Yii::$app->basePath.'/data/polygons/'.$this->id.'.json';
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            return '[]';
        }
    }
    
    public function getNextTaxrent() : int
    {
        return (int)$this->lastTaxrent + 24*60*60;
    }
    
    public function getIsCanBeTaxrented() : bool
    {
        return $this->nextTaxrent < time();
    }
    
}
