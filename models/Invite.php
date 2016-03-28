<?php

namespace app\models;

use app\models\ActiveRecord,
    app\models\User;

/**
 * This is the model class for table "invites".
 *
 * @property integer $id
 * @property string $hash
 * @property integer $userId
 * @property integer $time
 * 
 * @property User $user
 */
class Invite extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash'], 'required'],
            [['hash'], 'string'],
            [['userId', 'time'], 'integer'],
            [['userId'], 'unique'], 
            [['hash'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hash' => Yii::t('app', 'Hash'),
            'userId' => Yii::t('app', 'User ID'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [];
    }
    
    /**
     * Использован ли инвайт
     * @return boolean
     */
    public function isUsed()
    {
        return !!$this->userId;
    }

    /**
     * 
     * @param string $hash
     * @return Invite
     */
    public static function findByHash($hash)
    {
        return static::find()->where(['hash' => $hash])->one();
    }
    
    /**
     * 
     * @param yii\web\IdentityInterface $user
     */
    public function activate($user, $fieldName = 'invited')
    {
        $transaction = $this->getDB()->beginTransaction();
        
        $this->userId = $user->getId();
        $this->time = time();
        $this->save();
        
        $user->$fieldName = 1;
        $user->save();
        
        $transaction->commit();
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
}
