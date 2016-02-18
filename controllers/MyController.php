<?php

namespace app\controllers;

use Yii,
    yii\web\Controller,
    yii\web\HttpException,
    app\models\User;

/**
 * Надстройка над Controller 
 * 
 * @property User $user залогиненый юзер
 */

class MyController extends Controller
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
        
        if ($result) {
            $this->result = $result;
            $this->error = false;
        }
        if ($this->error) {
            $this->result = 'error';
            if (is_array($this->error)) {
                $this->error = print_r($this->error,true);
            }
        }
        
        $ar = [
            'result' => $this->result,
            'error' => $this->error
        ];
        
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
        $this->result = "ok";
        return $this->_r();
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
        if (isset(Yii::$app->request->params['uid']) && isset(Yii::$app->request->params['key'])) {
            $viewer_id = intval(Yii::$app->request->params['uid']);
            $auth_key = Yii::$app->request->params['key'];
            if ($viewer_id > 0 && $auth_key) {
                $real_key = User::getRealKey($viewer_id);
                if (hash_equals($auth_key,$real_key)) {
                    $this->viewer_id = $viewer_id;
                    return true;
                }
            }
        } 
        if (isset($action->actionMethod)) {
            $action->actionMethod = 'actionInvalidAuthkey';
        }
        
        return true;
    }

    public function actionInvalidAuthkey()
    {
        if (Yii::$app->request->getIsAjax()) {
            return $this->renderJsonError("Invalid auth key");
        } else {
            throw new HttpException(403, "Invalid auth key");
        }
    }

    private $_user = null;    
    
    /**
     * Текущий юзер
     * @return User
     */
    protected function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = User::findByPk($this->viewer_id);
        }
        return $this->_user;
    }
}