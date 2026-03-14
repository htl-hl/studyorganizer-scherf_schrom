<?php

namespace app\controllers;

use Yii;
use app\models\Homework;
use app\models\HomeworkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class HomeworkController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                $role = Yii::$app->user->identity->role ?? null;
                                return in_array($role, ['student', 'teacher']);
                            },
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        if (Yii::$app->user->isGuest) {
                            return Yii::$app->response->redirect(['/site/login']);
                        }
                        throw new \yii\web\ForbiddenHttpException('Du hast keinen Zugriff auf diese Seite.');
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete'       => ['POST'],
                        'set-finished' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel  = new HomeworkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Nur eigene Hausaufgaben anzeigen
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Homework();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id     = Yii::$app->user->id;
                $model->is_finished = 0;

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->user_id = Yii::$app->user->id;

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionSetFinished($id, $value)
    {
        $model              = $this->findModel($id);
        $model->is_finished = (int)$value;
        $model->save(false);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        // Nur eigene Hausaufgaben findbar machen
        $model = Homework::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}