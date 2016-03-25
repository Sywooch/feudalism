<?php

namespace app\controllers;

use Yii,
    yii\web\Controller as YiiController,
    app\models\User;

/**
 * Надстройка над Controller 
 * 
 * @property User $user залогиненый юзер
 */

class Controller extends YiiController
{
    /**
     * Массив или объект, возвращаемый в поле result
     * @var mixed
     */
    protected $result = 'undefined';
    
    /**
     * Ошибка
     * @var string|boolean 
     */
    protected $error = false;
    
    /**
     * ID залогиненого юзера
     * 	Логин происходит в beforeAction
     *	Секретный ключ из которого генерируется auth_key — в app/config/params.php
     * @var integer 
     */
    protected $viewer_id = 0;

    /**
     * Рендерит стандартный для API JSON-объект
     *	{"result":%data%}
     *	Или ошибку
     *	{"result":"error","error":"%errorname%"}
     * 
     * @param mixed $result
     * @param array $addFields
     * @return array
     */
    protected function renderJson($result = null, $addFields = []) 
    {
        Yii::$app->response->format = 'json';
        
        if (!is_null($result)) {
            $this->result = $result;
            $this->error = false;
        }
        if ($this->error) {
            $this->result = 'error';
            if (is_array($this->error)) {
                
                $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->error));
                $this->error = [];
                foreach ($iterator as $key => $value) {                
                    $this->error[] = $value;
                }
                $this->error = implode(',', $this->error);
            }
        }
        
        $ar = ['result' => $this->result];
        if ($this->error) {            
            $ar['error'] = $this->error;
        }
        
        foreach ($addFields as $key => $val) {
            $ar[$key] = $val;
        }

        return $ar;
    }

    /**
     * Рендерит JSON-ok
     * 
     * @return array
     */
    protected function renderJsonOk()
    {
        $this->error = false;
        return $this->renderJson("ok");
    }
    
    /**
     * Рендерит JSON-Error
     * 
     * @param mixed $e Текст ошибки
     * @param array $addFields
     * @return array
     */
    protected function renderJsonError($e, $addFields = [])
    {
        $this->error = $e;
        return $this->renderJson(null, $addFields);
    }
    
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            if (Yii::$app->request->get('uid') && Yii::$app->request->get('key')) {
                $viewer_id = intval(Yii::$app->request->params['uid']);
                $auth_key = Yii::$app->request->params['key'];
                if ($viewer_id > 0 && $auth_key) {
                    $real_key = User::getRealKey($viewer_id);
                    if (hash_equals($auth_key,$real_key)) {
                        $this->viewer_id = $viewer_id;
                    }
                }
            } 
        } else {
            $this->viewer_id = Yii::$app->user->id;
            Yii::$app->language = 'ru';
        }
        
        return parent::beforeAction($action);
    }

    private $_user = null;    
    
    /**
     * Текущий юзер
     * @return User
     */
    protected function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }
}