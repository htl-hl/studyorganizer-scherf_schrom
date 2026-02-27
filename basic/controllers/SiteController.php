<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'error', 'about', 'contact', 'captcha'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['home', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login-Seite (= Startseite für nicht eingeloggte User)
     */
    public function actionIndex()
    {
        // Wenn bereits eingeloggt → direkt zur Home-Seite
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/home']);
        }

        $loginModel = new LoginForm();
        if ($loginModel->load(Yii::$app->request->post()) && $loginModel->login()) {
            // Nach erfolgreichem Login → Home-Seite
            return $this->redirect(['site/home']);
        }

        $loginModel->password = '';

        $registrationModel = new RegistrationForm();
        if ($registrationModel->load(Yii::$app->request->post()) && $registrationModel->register()) {
            Yii::$app->session->setFlash('success', 'Registrierung erfolgreich! Bitte melde dich an.');
            return $this->redirect(['site/index']);
        }

        return $this->render('index', [
            'loginModel' => $loginModel,
            'registrationModel' => $registrationModel,
        ]);
    }

    /**
     * Home-Seite (nur für eingeloggte User)
     * Nicht eingeloggte User werden automatisch zur Login-Seite weitergeleitet
     */
    public function actionHome()
    {
        return $this->render('home');
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }

    /**
     * Kontakt-Seite
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * About-Seite
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}