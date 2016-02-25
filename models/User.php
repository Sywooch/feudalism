<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\models\MyModel,
    app\models\Auth,
    app\models\UnitGroup,
    app\models\Invite,
    app\models\Unit,
    app\models\Castle;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gender 
 * @property boolean $invited 
 * @property integer $level
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
 * @property integer $currentCastleId
 * @property integer $capitalCastleId
 *
 * @property Auth[] $auths
 * @property UnitGroup[] $groups
 * @property Invite $invite
 * @property Unit[] $units
 * @property Castle $capitalCastle
 * @property Castle $currentCastle
 * @property Castle[] $castles
 * @property Group $currentGroup
 * 
 * @property string $genderPrefix
 * @property string $genderedName
 */
class User extends MyModel implements IdentityInterface
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
            [['gender', 'level', 'magic', 'authority', 'education', 'combat', 'magicBase', 'authorityBase', 'educationBase', 'combatBase', 'currentGroupId', 'currentCastleId', 'capitalCastleId'], 'integer'],
            [['invited'], 'boolean'],
            [['balance'], 'number'],
            [['capitalCastleId'], 'unique']
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
            'currentCastleId' => Yii::t('app', 'Current Castle ID'),
            'capitalCastleId' => Yii::t('app', 'Capital Castle ID'),
        ];
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
    public function getCapitalCastle()
    {
        return $this->hasOne(Castle::className(), ['id' => 'capitalCastleId']);
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
    public function getCastles()
    {
        return $this->hasMany(Castle::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentGroup()
    {
        return $this->hasOne(UnitGroup::className(), ['id' => 'currentGroupId']);
    }

    public function getAuthKey()
    {
        return md5($this->id.Yii::$app->params['AUTH_KEY_SECRET']);
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

}
