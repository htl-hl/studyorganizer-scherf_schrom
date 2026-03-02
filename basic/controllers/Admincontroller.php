<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class AdminController extends Controller
{
    /**
     * Nur der 'admin' User darf diese Seite sehen
     */
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

    /**
     * Admin-Übersicht: Fächer + Lehrer auf einer Seite
     */
    public function actionIndex()
    {
        $faecher = [
            ['id' => 1, 'name' => 'Mathematik'],
            ['id' => 2, 'name' => 'Deutsch'],
            ['id' => 3, 'name' => 'Englisch'],
        ];

        $lehrer = [
            ['id' => 1, 'name' => 'Herr Schmidt', 'fach' => 'Mathematik', 'aktiv' => true],
            ['id' => 2, 'name' => 'Frau Müller',  'fach' => 'Deutsch',    'aktiv' => false],
            ['id' => 3, 'name' => 'Herr Wagner',  'fach' => 'Englisch',   'aktiv' => true],
        ];

        $sucheFach = Yii::$app->request->get('sucheFach', '');
        if ($sucheFach !== '') {
            $faecher = array_filter($faecher, function($f) use ($sucheFach) {
                return stripos($f['name'], $sucheFach) !== false;
            });
        }

        $sucheLehrer = Yii::$app->request->get('sucheLehrer', '');
        if ($sucheLehrer !== '') {
            $lehrer = array_filter($lehrer, function($l) use ($sucheLehrer) {
                return stripos($l['name'], $sucheLehrer) !== false;
            });
        }

        return $this->render('index', [
            'faecher'     => array_values($faecher),
            'lehrer'      => array_values($lehrer),
            'sucheFach'   => $sucheFach,
            'sucheLehrer' => $sucheLehrer,
        ]);
    }
}