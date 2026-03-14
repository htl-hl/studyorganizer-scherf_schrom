<?php

namespace app\controllers;

use app\models\Subject;
use app\models\Teacher;
use app\models\User;
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

    // ── Admin Index ───────────────────────────────────────────────────────────

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
                'fach'    => $t->subject ? $t->subject->name : '-',
                'fach_id' => $t->subject_id,
                'aktiv'   => (bool)$t->is_active,
            ];
        }, $lehrerModels);

        $alleFaecher = Subject::find()->asArray()->all();

        return $this->render('index', [
            'faecher'        => $faecher,
            'lehrer'         => $lehrer,
            'alleFaecher'    => $alleFaecher,
            'sucheFach'      => $sucheFach,
            'sucheLehrer'    => $sucheLehrer,
            'editFachId'     => Yii::$app->session->getFlash('editFachId'),
            'editLehrerId'   => Yii::$app->session->getFlash('editLehrerId'),
            'showFachForm'   => Yii::$app->session->getFlash('showFachForm'),
            'showLehrerForm' => Yii::$app->session->getFlash('showLehrerForm'),
            'error'          => Yii::$app->session->getFlash('error'),
            'success'        => Yii::$app->session->getFlash('success'),
        ]);
    }

    // ── User-Verwaltung (eigene Seite) ────────────────────────────────────────

    public function actionUsers()
    {
        $sucheUser = Yii::$app->request->get('sucheUser', '');

        $userQuery = User::find();
        if ($sucheUser !== '') {
            $userQuery->andWhere(['like', 'username', $sucheUser]);
        }
        $users = $userQuery->asArray()->all();

        return $this->render('users', [
            'users'            => $users,
            'sucheUser'        => $sucheUser,
            'editUserId'       => Yii::$app->session->getFlash('editUserId'),
            'changePasswordId' => Yii::$app->session->getFlash('changePasswordId'),
            'showUserForm'     => Yii::$app->session->getFlash('showUserForm'),
            'error'            => Yii::$app->session->getFlash('error'),
            'success'          => Yii::$app->session->getFlash('success'),
        ]);
    }

    public function actionCreateUser()
    {
        $username = trim(Yii::$app->request->post('username', ''));
        $password = trim(Yii::$app->request->post('password', ''));
        $role     = Yii::$app->request->post('role', 'student');
        $redirect = Yii::$app->request->post('redirect', 'index');

        if ($username === '' || $password === '') {
            Yii::$app->session->setFlash('error', 'Username und Passwort sind erforderlich.');
            Yii::$app->session->setFlash('showUserForm', true);
            return $this->redirect(['/admin/' . $redirect]);
        }

        if (User::findOne(['username' => $username])) {
            Yii::$app->session->setFlash('error', 'Username existiert bereits.');
            Yii::$app->session->setFlash('showUserForm', true);
            return $this->redirect(['/admin/' . $redirect]);
        }

        $user = new User();
        $user->username = $username;
        $user->role     = $role;
        $user->setPassword($password);
        $user->generateAuthKey();

        if (!$user->save()) {
            Yii::$app->session->setFlash('error', implode(', ', $user->getFirstErrors()));
            Yii::$app->session->setFlash('showUserForm', true);
        } else {
            Yii::$app->session->setFlash('success', 'User wurde erfolgreich erstellt.');
        }
        return $this->redirect(['/admin/' . $redirect]);
    }

    public function actionUpdateUserRole()
    {
        $id       = (int)Yii::$app->request->post('id');
        $role     = Yii::$app->request->post('role', '');
        $redirect = Yii::$app->request->post('redirect', 'index');
        $user     = User::findOne($id);

        if (!$user) return $this->redirect(['/admin/' . $redirect]);

        if (!in_array($role, ['student', 'teacher', 'admin'])) {
            Yii::$app->session->setFlash('error', 'Ungueltige Rolle.');
            return $this->redirect(['/admin/' . $redirect]);
        }

        $user->role = $role;
        if (!$user->save(false)) {
            Yii::$app->session->setFlash('error', 'Rolle konnte nicht gespeichert werden.');
        } else {
            Yii::$app->session->setFlash('success', 'Rolle wurde gespeichert.');
        }
        return $this->redirect(['/admin/' . $redirect]);
    }

    public function actionChangePassword()
    {
        $id       = (int)Yii::$app->request->post('id');
        $password = Yii::$app->request->post('password', '');
        $confirm  = Yii::$app->request->post('password_confirm', '');
        $redirect = Yii::$app->request->post('redirect', 'users');
        $user     = User::findOne($id);

        if (!$user) return $this->redirect(['/admin/' . $redirect]);

        if (strlen($password) < 6) {
            Yii::$app->session->setFlash('error', 'Passwort muss mindestens 6 Zeichen lang sein.');
            Yii::$app->session->setFlash('changePasswordId', $id);
            return $this->redirect(['/admin/' . $redirect]);
        }

        if ($password !== $confirm) {
            Yii::$app->session->setFlash('error', 'Passwoerter stimmen nicht ueberein.');
            Yii::$app->session->setFlash('changePasswordId', $id);
            return $this->redirect(['/admin/' . $redirect]);
        }

        $user->setPassword($password);
        if (!$user->save(false)) {
            Yii::$app->session->setFlash('error', 'Passwort konnte nicht gespeichert werden.');
        } else {
            Yii::$app->session->setFlash('success', 'Passwort wurde erfolgreich geaendert.');
        }
        return $this->redirect(['/admin/' . $redirect]);
    }

    public function actionDeleteUser()
    {
        $id       = (int)Yii::$app->request->post('id');
        $redirect = Yii::$app->request->post('redirect', 'index');
        $user     = User::findOne($id);

        if ($user) {
            if ($user->username === 'admin') {
                Yii::$app->session->setFlash('error', 'Der Admin-User kann nicht geloescht werden.');
                return $this->redirect(['/admin/' . $redirect]);
            }
            $user->delete();
            Yii::$app->session->setFlash('success', 'User wurde geloescht.');
        }
        return $this->redirect(['/admin/' . $redirect]);
    }

    // ── Faecher ───────────────────────────────────────────────────────────────

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
        if (!$subject) return $this->redirect(['/admin/index']);
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
            Teacher::deleteAll(['subject_id' => $id]);
            $subject->delete();
        }
        return $this->redirect(['/admin/index']);
    }

    // ── Lehrer ────────────────────────────────────────────────────────────────

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
        if (!$teacher) return $this->redirect(['/admin/index']);
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