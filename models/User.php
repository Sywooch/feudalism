<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\components\Pricelist,
    app\components\ExperienceCalculator,
    app\models\ActiveRecord,
    app\models\Auth,
    app\models\UnitGroup,
    app\models\Invite,
    app\models\Unit,
    app\models\titles\Title,
    app\models\holdings\Holding;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gender 
 * @property boolean $invited 
 * @property integer $level
 * @property integer $experience
 * @property integer $primaryTitleId Основной титул
 * @property double $balance 
 * @property integer $magic Магия
 * @property integer $authority Авторитет (внушительность)
 * @property integer $education Образованность
 * @property integer $combat Боевые навыки
 * @property integer $magicBase Магия (без бонусов)
 * @property integer $authorityBase Авторитет (без бонусов)
 * @property integer $educationBase Образованность (без бонусов)
 * @property integer $combatBase Боевые навыки (без бонусов)
 * @property integer $currentGroupId
 * @property integer $currentHoldingId
 * @property integer $capitalHoldingId
 * @property integer $registration 
 * @property integer $lastActive 
 *
 * @property Auth[] $auths
 * @property UnitGroup[] $groups
 * @property Invite $invite
 * @property Unit[] $units
 * @property Holding $capitalHolding
 * @property Holding $currentHolding
 * @property Group $currentGroup
 * @property Title $primaryTitle
 * @property Title[] $titles
 * 
 * @property string $genderPrefix
 * @property string $genderedName
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['gender', 'level', 'experience', 'primaryTitleId', 'magic', 'authority', 'education', 'combat', 'magicBase', 'authorityBase', 'educationBase', 'combatBase', 'currentGroupId', 'currentHoldingId', 'capitalHoldingId', 'registration', 'lastActive'], 'integer'],
            [['invited'], 'boolean'],
            [['balance'], 'number'],
            [['capitalHoldingId'], 'unique']
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
            'gender' => Yii::t('app', 'Gender'), 
            'invited' => Yii::t('app', 'Invited'), 
            'level' => Yii::t('app', 'Level'),
            'experience' => Yii::t('app', 'XP'),
            'primaryTitleId' => Yii::t('app', 'Primary Title ID'),
            'balance' => Yii::t('app', 'Balance'),
            'magic' => Yii::t('app', 'Magic'),
            'authority' => Yii::t('app', 'Authority'),
            'education' => Yii::t('app', 'Education'),
            'combat' => Yii::t('app', 'Combat'),
            'magicBase' => Yii::t('app', 'Magic Base'),
            'authorityBase' => Yii::t('app', 'Authority Base'),
            'educationBase' => Yii::t('app', 'Education Base'),
            'combatBase' => Yii::t('app', 'Combat Base'),
            'currentGroupId' => Yii::t('app', 'Current Group ID'),
            'currentHoldingId' => Yii::t('app', 'Current Holding ID'),
            'capitalHoldingId' => Yii::t('app', 'Capital Holding ID'),
            'registration' => Yii::t('app', 'Registration'), 
            'lastActive' => Yii::t('app', 'Last Active'), 
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        $attributes = [
            'id',
            'name',
            'genderedName',
            'gender',
            'level',
            'primaryTitleId',
            'magic',
            'authority',
            'education',
            'combat',
            'capitalHoldingId'
        ];
        
        if ($owner) {
            $attributes = array_merge($attributes, [
                'experience',
                'balance',
                'magicBase',
                'authorityBase',
                'educationBase',
                'combatBase',
                'currentGroupId',
                'currentHoldingId',
                'registration',
                'lastActive'
            ]);
        }
        
        return $attributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(UnitGroup::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvite()
    {
        return $this->hasOne(Invite::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapitalHolding()
    {
        return $this->hasOne(Holding::className(), ['id' => 'capitalHoldingId']);
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
    public function getTitles()
    {
        return $this->hasMany(Title::className(), ['userId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitGroups()
    {
        return $this->hasMany(UnitGroup::className(), ['userId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryTitle()
    {
        return $this->hasOne(Title::className(), ['id' => 'primaryTitleId']);
    }

    public function getAuthKey()
    {
        return md5($this->id."wtf");
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        return hash_equals($this->getAuthKey(), $authKey);
    }

    /**
     * 
     * @param integer $id
     * @return static
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    
    const GENDER_UNDEFINED = 0;
    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;

    public static function stringGenderToInt($gender)
    {
        switch ($gender) {
            case 'male':
                return static::GENDER_MALE;
            case 'female':
                return static::GENDER_FEMALE;
            default:
                return static::GENDER_UNDEFINED;
        }
    }
    
    public function getGenderPrefix()
    {
        switch ($this->gender) {
            case static::GENDER_MALE:
                return Yii::t('app','Sir');
            case static::GENDER_FEMALE:
                return Yii::t('app','Lady');
            default:
                return '';
        }
    }
    
    public function getGenderedName()
    {
        return $this->genderPrefix.' '.$this->name;
    }
    
    /**
     * 
     * @param integer $category
     * @param integer $action
     * @param array $params
     * @return boolean
     */
    public function isHaveMoneyForAction($category, $action = 0, $params = [])
    {
        return $this->balance >= Pricelist::get($category, $action, $params);
    }
    
    /**
     * 
     * @param string $category
     * @param string $action
     * @param array $params
     * @param boolean $saveModel
     */
    public function payForAction($category, $action = 0, $params = [], $saveModel = false)
    {
        $this->balance -= Pricelist::get($category, $action, $params);
        if ($saveModel) {
            return $this->save();
        }
    }
    
    /**
     * Произошёл ли левелап через calcLevel
     * @var boolean 
     */
    public $isLevelUp = false;
    
    /**
     * Считает текущий уровень по опыту
     * @param boolean $save
     * @return boolean
     */
    public function calcLevel($save = false)
    {
        $oldLevel = $this->level;
        $this->level = ExperienceCalculator::getLevelByExperience($this->experience);
        $this->isLevelUp = ($oldLevel < $this->level);
        if ($save) {
            return $this->save();
        }
    }
    
    /**
     * Начисленное через addExperienceForAction число очков опыта
     * @var integer
     */
    public $experienceGained = 0;
    
    /**
     * Начисляет опыт за действие
     * @param string $category
     * @param string $action
     * @param array $params
     * @param boolean $save
     * @return boolean
     */
    public function addExperienceForAction($category, $action = 0, $params = [], $save = false)
    {
        $xp = ExperienceCalculator::get($category, $action, $params);
        $this->experience += $xp;
        $this->experienceGained += $xp;
        return $this->calcLevel($save);
    }
    
    /**
     * Меняет параметры юзера за совершённое действие (снимает деньги, начисляет опыт)
     * @TODO: добавить уведомления
     * @param string $category
     * @param string $action
     * @param array $params
     * @param boolean $save
     * @return boolean
     */
    public function makeAction($category, $action = 0, $params = [], $save = false)
    {
        $this->payForAction($category, $action, $params, false);
        return $this->addExperienceForAction($category, $action, $params, $save);
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->registration = time();
        }
        
        return parent::beforeSave($insert);
    }

}
