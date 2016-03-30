<?php

namespace app\controllers;

use Yii,
    yii\filters\AccessControl,
    yii\filters\VerbFilter,
    yii\web\ErrorAction,
    yii\web\UploadedFile,
    yii\authclient\AuthAction,
    app\models\Auth,
    app\models\InviteForm,
    app\controllers\Controller;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ],
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['invite', 'logout'],
            'rules' => [
                [
                    'actions' => ['invite', 'logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ];

        return $behaviors;
    }
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            if (Yii::$app->user->identity->invited) {
                return $this->render('panel',[
                    'user' => Yii::$app->user->identity
                ]);
            } else {
                return $this->redirect(["invite"]);
            }
        }
    }
    
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => Auth::getSourceId($client->getId()),
            'sourceId' => $attributes['id'],
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($auth && $auth->user) { // login
                
                Yii::$app->user->login($auth->user, 30*24*60*60);
                if ($auth->user->invited) {
                    $this->redirect("/");
                } else {
                    $this->redirect("invite");
                }
            } else { // signup
                Auth::signUp(Auth::getSourceId($client->getId()), $attributes);
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'userId' => Yii::$app->user->id,
                    'source' => Auth::getSourceId($client->getId()),
                    'sourceId' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionInvite()
    {
        if ($this->user->invited) {
            return $this->redirect('/');
        }
        
        $model = new InviteForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->validate()) {
                $invite = $model->getInvite();
                if ($invite) {
                    $invite->activate($this->user);
                    $this->redirect('/');
                } else {
                    $model->addError('imageFile', 'Invalid invite');
                }
            }
        }

        return $this->render('invite', ['model' => $model]);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
