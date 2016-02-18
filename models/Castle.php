<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\Unit,
    app\models\User;

/**
 * This is the model class for table "castles".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $name
 * @property integer $fort
 * @property double $lat
 * @property double $lng
 *
 * @property Unit[] $units
 * @property User $users
 * @property User[] $users0
 */
class Castle extends MyModel
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
            [['userId', 'name', 'lat', 'lng'], 'required'],
            [['userId', 'fort'], 'integer'],
            [['name'], 'string'],
            [['lat', 'lng'], 'number']
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
            'name' => Yii::t('app', 'Name'),
            'fort' => Yii::t('app', 'Fort'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
        ];
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
    public function getUsers()
    {
        return $this->hasOne(User::className(), ['capitalCastleId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(User::className(), ['currentCastleId' => 'id']);
    }
}
