<?php

namespace app\models\holdings;

use Yii,
    yii\base\Exception,
    app\models\ActiveRecord,
    app\models\holdings\HoldingQuery,
    app\models\titles\Title,
    app\models\Position,
    app\models\Unit,
    app\models\User,
    app\models\Tile;

/**
 * This is the model class for table "holdings".
 * Замки, города, крепости и т.п.
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $tileId 
 * @property integer $titleId
 * @property string $name
 * @property integer $population
 * @property integer $fortification
 * @property integer $quarters
 * @property integer $quartersUsed
 * @property integer $builded 
 * @property integer $buildedUserId
 * @property integer $captured 
 *
 * @property Unit[] $units
 * @property User[] $users
 * @property Tile $tile
 * @property Title $title
 * @property User $buildedUser
 * 
 * @property string $userName
 * @property integer $userLevel
 * @property string $character
 * 
 * @property boolean $canFortificationIncreases 
 * @property boolean $canQuartersIncreases
 * @property boolean $canSpawnUnit
 */
class Holding extends ActiveRecord implements Position
{
	
    /**
     * Замок
     */
    const PROTOTYPE_CASTLE = 1;
    
    const PROTOTYPE = null;
    const CHARACTER = null;

    public function init()
    {
        $this->protoId = static::PROTOTYPE;
        parent::init();
    }

    public static function find()
    {
        return new HoldingQuery(get_called_class(), ['protoId' => static::PROTOTYPE]);
    }

    public function beforeSave($insert)
    {
        $this->protoId = static::PROTOTYPE;
        
        if ($insert) {
            $this->builded = time();
        }
        
        return parent::beforeSave($insert);
    }

    public static function instantiate($row)
    {
            $className = "app\\models\\holdings\\";
            switch (intval($row["protoId"])) {
                case self::PROTOTYPE_CASTLE:
                    $className .= "Castle";
                    break;
                default:
                    throw new Exception("Invalid holding prototype ({$row['protoId']}) in model ID{$row['id']}!");
            }
        return new $className($row);
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holdings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tileId', 'titleId', 'population', 'fortification', 'quarters', 'quartersUsed', 'builded', 'buildedUserId', 'captured'], 'integer'],
            [['tileId', 'name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['tileId'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tileId' => Yii::t('app', 'Tile ID'), 
            'titleId' => Yii::t('app', 'Title ID'), 
            'name' => Yii::t('app', 'Name'),
            'population' => Yii::t('app', 'Population'),
            'fortification' => Yii::t('app', 'Fortification'),
            'quarters' => Yii::t('app', 'Quarters'),
            'quartersUsed' => Yii::t('app', 'Quarters Used'),
            'builded' => Yii::t('app', 'Builded'), 
            'buildedUserId' => Yii::t('app', 'Builded User ID'),
            'captured' => Yii::t('app', 'Captured'), 
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'tileId',
            'titleId',
            'name',
            'population',
            'fortification',
            'quarters',
            'builded',
            'buildedUserId',
            'captured'
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
    public function getBuildedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'buildedUserId']);
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
        return $this->title->userName;
    }
    
    public function getUserLevel()
    {
        return $this->title->user->level;
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
    
    /**
     * 
     * @return boolean
     */
    public function isOwner(User &$user)
    {
        return ($this->title && $this->title->userId === $user->id);
    }
        
    public function getCharacter()
    {
        return static::CHARACTER;
    }

}
