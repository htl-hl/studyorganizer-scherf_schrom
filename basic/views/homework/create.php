<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

$this->title = 'Hausübung erstellen';
?>

<style>
    .hw-create-header {
        margin-top: 20px;
        margin-bottom: 4px;
    }
    .hw-create-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 2px;
    }
    .hw-create-header p {
        color: #888;
        font-size: 0.9rem;
        margin: 0;
    }
    body.light .hw-create-header h1 { color: #111; }
    body.light .hw-create-header p  { color: #777; }
</style>

<div class="homework-create">
    <div class="hw-create-header">
        <h1>📝 Hausübung erstellen</h1>
        <p>Füge eine neue Hausaufgabe hinzu</p>
    </div>

    <?= $this->render('_form', ['model' => $model]) ?>
</div>