<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\Castle,
    app\models\UnitGroup,
    app\models\User;

/**
 * This is the model class for table "units".
 *
 * @property integer $userId
 * @property integer $protoId
 * @property integer $currentGroupId
 * @property integer $currentCastleId
 *
 * @property Castle $currentCastle
 * @property UnitGroup $currentGroup
 * @property User $user
 */
class Unit extends MyModel
{
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
            [['userId', 'protoId', 'currentGroupId', 'currentCastleId'], 'integer']
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
            'currentGroupId' => Yii::t('app', 'Current Group ID'),
            'currentCastleId' => Yii::t('app', 'Current Castle ID'),
        ];
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
        return $this->hasOne(UnitGroup::className(), ['id' => 'currentGroupId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
