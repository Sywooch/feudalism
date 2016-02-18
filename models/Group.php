<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\Unit,
    app\models\User;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $name
 * @property double $lat
 * @property double $lng
 *
 * @property User $user
 * @property Unit[] $units
 * @property User[] $users
 */
class Group extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'lat', 'lng'], 'required'],
            [['userId'], 'integer'],
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
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
        ];
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
    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['currentGroupId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['currentGroupId' => 'id']);
    }
}
