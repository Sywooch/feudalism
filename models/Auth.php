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
                    'sex' => User::stringGenderToSex($attributes['gender']),
                    'photo' => $attributes['image']['url'],
                    'photo_big' => preg_replace("/sz=50/", "/sz=400", $attributes['image']['url']),
                    'money' => 200000                    
                ]);
                break;
            case static::SOURCE_FACEBOOK:
                $user = new User([
                    'name' => $attributes['name'],
                    'sex' => User::stringGenderToSex($attributes['gender']),
                    'photo' => "http://graph.facebook.com/{$attributes['id']}/picture",
                    'photo_big' => "http://graph.facebook.com/{$attributes['id']}/picture?width=400&height=800",
                    'money' => 100000                    
                ]);
                break;
            case static::SOURCE_VK:
            case static::SOURCE_VKAPP:
                $user = new User([
                    'name' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                    'sex' => intval($attributes['sex']),
                    'photo' => $attributes['photo_50'],
                    'photo_big' => (isset($attributes['photo_400_orig'])) ? $attributes['photo_400_orig'] : $attributes['photo_big'],
                    'money' => 100000                    
                ]);
                break;

        }
        
        $user->party_id = 0;
        $user->state_id = 0;
        $user->post_id = 0;
        $user->region_id = 0;
        
        $transaction = $user->getDb()->beginTransaction();
        if ($user->save()) {
            $auth = new Auth([
                'user_id' => $user->id,
                'source' => $source,
                'source_id' => (string)(isset($attributes['id'])?$attributes['id']:$attributes['uid']),
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
}
