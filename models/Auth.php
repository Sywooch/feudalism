<?php

namespace app\models;

use Yii,
    app\models\MyModel,
    app\models\User;

/**
 * This is the model class for table "auth".
 *
 * @property integer $userId
 * @property integer $source
 * @property string $sourceId
 * 
 * @property User $user
 */
class Auth extends MyModel
{
    
    const SOURCE_GOOGLE = 1;
    const SOURCE_VK = 2;
    const SOURCE_VKAPP = 3;
    const SOURCE_FACEBOOK = 4;
    const SOURCE_TWITTER = 5;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'source', 'sourceId'], 'required'],
            [['userId', 'source'], 'integer'],
            [['sourceId'], 'string'],
            [['source', 'sourceId'], 'unique', 'targetAttribute' => ['source', 'sourceId'], 'message' => 'The combination of Source and Source ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
            'source' => Yii::t('app', 'Source'),
            'sourceId' => Yii::t('app', 'Source ID'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
    
    /**
     * 
     * @param integer $source
     * @param array $attributes
     * @return Auth|User
     */
    public static function signUp($source, $attributes)
    {
        // TODO Переделать
        switch ($source) {
            case static::SOURCE_GOOGLE:
                $user = new User([
                    'name' => $attributes['displayName'],
                    'gender' => User::stringGenderToInt($attributes['gender']),
                    'balance' => 10                    
                ]);
                break;
            default:
                return null;
        }
        
        $transaction = $user->getDb()->beginTransaction();
        if ($user->save()) {
            $auth = new Auth([
                'userId' => $user->id,
                'source' => $source,
                'sourceId' => (string)(isset($attributes['id'])?$attributes['id']:$attributes['uid']),
            ]);
            if ($auth->save()) {
                $transaction->commit();
                Yii::$app->user->login($user);
            } else {
//                print_r($auth->getErrors());
            }
            return $auth;
        } else {
//            print_r($user->getErrors());
            return $user;
        }
    }
    
    /**
     * 
     * @param string $sourceName
     */
    public static function getSourceId($sourceName)
    {
        switch ($sourceName) {
            case 'google':
                return static::SOURCE_GOOGLE;
            case 'facebook':
                return static::SOURCE_FACEBOOK;
            case 'twitter':
                return static::SOURCE_TWITTER;
            case 'vkontakte':
                return static::SOURCE_VK;
            case 'vkapp':
                return static::SOURCE_VKAPP;
        }
    }
    
}
