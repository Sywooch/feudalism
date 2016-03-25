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
            'tileId',
            'titleId',
            'name',
            'fortification',
            'quarters',
            'builded',
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
    
    /**
     * Строит новый замок со всеми необходимыми проверками
     * @param string $name
     * @param User $user
     * @param Tile $tile
     * @return self
     */
    public static function build($name, User &$user, Tile &$tile)
    {
        $model = new self();
        if (!$user->isHaveMoneyForAction('castle', 'build')) {
            $model->addError('userId', Yii::t('app','You haven`t money'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load([
            'name' => $name,
            'userId' => $user->id,
            'tileId' => $tile->id,
            'fortification' => 1,
            'quarters' => 1
        ],'') && $model->save()) {
            if ($user->makeAction('castle', 'build', [], true)) {
                $transaction->commit();
            } 
        }
        return $model;
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function fortificationIncrease(User &$user)
    {        
        // Юзер — владелец замка
        if ($this->userId !== $user->id) {
            $this->addError('userId', Yii::t('app','Action not allowed'));
            return false;
        }
            
        // Расширение фортификаций доступно для замка
        if (!$this->canFortificationIncreases) {            
            $this->addError('userId', Yii::t('app','Action not allowed'));
            return false;
        }
        
        $current = $this->fortification;
                
        // У юзера достаточно денег для расширения
        if (!$user->isHaveMoneyForAction('castle', 'fortification-increase', ['current' => $current])) {
            $this->addError('userId', Yii::t('app','You haven`t money'));
            return false;
        }
        
        $this->fortification++;
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->save()) {
            if ($user->makeAction('castle', 'fortification-increase', ['current' => $current], true)) {
                $transaction->commit();
                return true;
            }
        }        
        return false;
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function quartersIncrease(User &$user)
    {        
        // Юзер — владелец замка
        if ($this->userId !== $user->id) {
            $this->addError('userId', Yii::t('app','Action not allowed'));
            return false;
        }
            
        // Расширение фортификаций доступно для замка
        if (!$this->canQuartersIncreases) {            
            $this->addError('userId', Yii::t('app','Action not allowed'));
            return false;
        }
        
        $current = $this->quarters;
                
        // У юзера достаточно денег для расширения
        if (!$user->isHaveMoneyForAction('castle', 'quarters-increase', ['current' => $current])) {
            $this->addError('userId', Yii::t('app','You haven`t money'));
            return false;
        }
        
        $this->quarters++;
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->save()) {
            if ($user->makeAction('castle', 'quarters-increase', ['current' => $current], true)) {
                $transaction->commit();
                return true;
            }
        }        
        return false;
    }


    public function beforeSave($insert)
    {
        if ($insert) {
            $this->builded = time();
        }
        
        if ($this->userId !== $this->oldAttributes['userId']) {
            $this->captured = $this->userId ? time() : null;
        }
        
        return parent::beforeSave($insert);
    }

}
