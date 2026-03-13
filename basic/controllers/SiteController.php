<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\ContactForm;
use app\models\Homework;

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
            'error' => ['class' => 'yii\web\ErrorAction'],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->username === 'admin') {
                return $this->redirect(['/admin/index']);
            }
            return $this->redirect(['site/home']);
        }

        $loginModel = new LoginForm();
        if ($loginModel->load(Yii::$app->request->post()) && $loginModel->login()) {
            if (Yii::$app->user->identity->username === 'admin') {
                return $this->redirect(['/admin/index']);
            }
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

    public function actionHome()
    {
        // Nur die eigenen ausstehenden Hausaufgaben laden
        $pendingHomework = Homework::find()
            ->where([
                'user_id'     => Yii::$app->user->id,
                'is_finished' => 0,
            ])
            ->orderBy(['due_date' => SORT_ASC])
            ->all();

        return $this->render('home', [
            'pendingHomework' => $pendingHomework,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}