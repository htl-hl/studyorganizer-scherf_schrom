<?php

namespace app\controllers;

use app\models\Subject;
use app\models\Teacher;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class AdminController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }

        if (Yii::$app->user->identity->username !== 'admin') {
            throw new ForbiddenHttpException('Du hast keinen Zugriff auf diese Seite.');
        }

        return true;
    }

    public function actionIndex()
    {
        $sucheFach   = Yii::$app->request->get('sucheFach', '');
        $sucheLehrer = Yii::$app->request->get('sucheLehrer', '');

        $faecherQuery = Subject::find();
        if ($sucheFach !== '') {
            $faecherQuery->andWhere(['like', 'name', $sucheFach]);
        }
        $faecher = $faecherQuery->asArray()->all();

        $lehrerQuery = Teacher::find()->with('subject');
        if ($sucheLehrer !== '') {
            $lehrerQuery->andWhere(['like', 'name', $sucheLehrer]);
        }
        $lehrerModels = $lehrerQuery->all();

        $lehrer = array_map(function (Teacher $t) {
            return [
                'id'      => $t->id,
                'name'    => $t->name,
                'fach'    => $t->subject ? $t->subject->name : '—',
                'fach_id' => $t->subject_id,
                'aktiv'   => (bool)$t->is_active,
            ];
        }, $lehrerModels);

        $alleFaecher = Subject::find()->asArray()->all();

        return $this->render('index', [
            'faecher'      => $faecher,
            'lehrer'       => $lehrer,
            'alleFaecher'  => $alleFaecher,
            'sucheFach'    => $sucheFach,
            'sucheLehrer'  => $sucheLehrer,
            'editFachId'   => Yii::$app->session->getFlash('editFachId'),
            'editLehrerId' => Yii::$app->session->getFlash('editLehrerId'),
            'showFachForm' => Yii::$app->session->getFlash('showFachForm'),
            'showLehrerForm' => Yii::$app->session->getFlash('showLehrerForm'),
            'error'        => Yii::$app->session->getFlash('error'),
        ]);
    }

    // ── FACH ──────────────────────────────────────────────────────────────────

    public function actionCreateFach()
    {
        $name = trim(Yii::$app->request->post('name', ''));
        if ($name === '') {
            Yii::$app->session->setFlash('error', 'Name darf nicht leer sein.');
            Yii::$app->session->setFlash('showFachForm', true);
            return $this->redirect(['/admin/index']);
        }
        $subject = new Subject();
        $subject->name = $name;
        if (!$subject->save()) {
            Yii::$app->session->setFlash('error', implode(', ', $subject->getFirstErrors()));
            Yii::$app->session->setFlash('showFachForm', true);
        }
        return $this->redirect(['/admin/index']);
    }

    public function actionUpdateFach()
    {
        $id      = (int)Yii::$app->request->post('id');
        $name    = trim(Yii::$app->request->post('name', ''));
        $subject = Subject::findOne($id);
        if (!$subject) {
            return $this->redirect(['/admin/index']);
        }
        if ($name === '') {
            Yii::$app->session->setFlash('error', 'Name darf nicht leer sein.');
            Yii::$app->session->setFlash('editFachId', $id);
            return $this->redirect(['/admin/index']);
        }
        $subject->name = $name;
        if (!$subject->save()) {
            Yii::$app->session->setFlash('error', implode(', ', $subject->getFirstErrors()));
            Yii::$app->session->setFlash('editFachId', $id);
        }
        return $this->redirect(['/admin/index']);
    }

    public function actionDeleteFach()
    {
        $id = (int)Yii::$app->request->post('id');
        $subject = Subject::findOne($id);
        if ($subject) {
            $subject->delete();
        }
        return $this->redirect(['/admin/index']);
    }

    // ── LEHRER ────────────────────────────────────────────────────────────────

    public function actionCreateLehrer()
    {
        $name      = trim(Yii::$app->request->post('name', ''));
        $subjectId = (int)Yii::$app->request->post('subject_id');
        if ($name === '' || $subjectId === 0) {
            Yii::$app->session->setFlash('error', 'Name und Fach sind erforderlich.');
            Yii::$app->session->setFlash('showLehrerForm', true);
            return $this->redirect(['/admin/index']);
        }
        $teacher             = new Teacher();
        $teacher->name       = $name;
        $teacher->subject_id = $subjectId;
        $teacher->is_active  = 1;
        if (!$teacher->save()) {
            Yii::$app->session->setFlash('error', implode(', ', $teacher->getFirstErrors()));
            Yii::$app->session->setFlash('showLehrerForm', true);
        }
        return $this->redirect(['/admin/index']);
    }

    public function actionUpdateLehrer()
    {
        $id        = (int)Yii::$app->request->post('id');
        $name      = trim(Yii::$app->request->post('name', ''));
        $subjectId = (int)Yii::$app->request->post('subject_id');
        $teacher   = Teacher::findOne($id);
        if (!$teacher) {
            return $this->redirect(['/admin/index']);
        }
        if ($name === '' || $subjectId === 0) {
            Yii::$app->session->setFlash('error', 'Name und Fach sind erforderlich.');
            Yii::$app->session->setFlash('editLehrerId', $id);
            return $this->redirect(['/admin/index']);
        }
        $teacher->name       = $name;
        $teacher->subject_id = $subjectId;
        if (!$teacher->save()) {
            Yii::$app->session->setFlash('error', implode(', ', $teacher->getFirstErrors()));
            Yii::$app->session->setFlash('editLehrerId', $id);
        }
        return $this->redirect(['/admin/index']);
    }

    public function actionToggleLehrer()
    {
        $id      = (int)Yii::$app->request->post('id');
        $teacher = Teacher::findOne($id);
        if ($teacher) {
            $teacher->is_active = $teacher->is_active ? 0 : 1;
            $teacher->save();
        }
        return $this->redirect(['/admin/index']);
    }
}