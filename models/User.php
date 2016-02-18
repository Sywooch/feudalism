<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\models\MyModel,
    app\models\Auth,
    app\models\Group,
    app\models\Invite,
    app\models\Unit,
    app\models\Castle,
    app\models\Group;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property integer $currentGroupId
 * @property integer $currentCastleId
 * @property integer $capitalCastleId
 * @property integer $magic Магия
 * @property integer $authority Авторитет (внушительность)
 * @property integer $education Образованность
 * @property integer $combat Боевые навыки
 * @property integer $magicBase Магия (без бонусов)
 * @property integer $authorityBase Авторитет (без бонусов)
 * @property integer $educationBase Образованность (без бонусов)
 * @property integer $combatBase Боевые навыки (без бонусов)
 *
 * @property Auth[] $auths
 * @property Group[] $groups
 * @property Invite $invite
 * @property Unit[] $units
 * @property Castle $capitalCastle
 * @property Castle $currentCastle
 * @property Group $currentGroup
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
            [['level', 'currentGroupId', 'currentCastleId', 'capitalCastleId', 'magic', 'authority', 'education', 'combat', 'magicBase', 'authorityBase', 'educationBase', 'combatBase'], 'integer'],
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
            'level' => Yii::t('app', 'Level'),
            'currentGroupId' => Yii::t('app', 'Current Group ID'),
            'currentCastleId' => Yii::t('app', 'Current Castle ID'),
            'capitalCastleId' => Yii::t('app', 'Capital Castle ID'),
            'magic' => Yii::t('app', 'Magic'),
            'authority' => Yii::t('app', 'Authority'),
            'education' => Yii::t('app', 'Education'),
            'combat' => Yii::t('app', 'Combat'),
            'magicBase' => Yii::t('app', 'Magic Base'),
            'authorityBase' => Yii::t('app', 'Authority Base'),
            'educationBase' => Yii::t('app', 'Education Base'),
            'combatBase' => Yii::t('app', 'Combat Base'),
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
        return $this->hasMany(Group::className(), ['userId' => 'id']);
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
    public function getCurrentGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'currentGroupId']);
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
        
    }

}
