<?php

use app\models\Subject;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */
/** @var yii\widgets\ActiveForm $form */

$subjects = ArrayHelper::map(Subject::find()->orderBy('name')->all(), 'id', 'name');
?>

<style>
    .hw-form-wrapper {
        background: var(--card-bg, #1e1e2e);
        border-radius: 16px;
        padding: 32px;
        margin-top: 10px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.3);
        max-width: 680px;
    }

    .hw-form-wrapper .form-group {
        margin-bottom: 20px;
    }

    .hw-form-wrapper label {
        color: #aaa;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: block;
    }

    .hw-form-wrapper input[type="text"],
    .hw-form-wrapper input[type="date"],
    .hw-form-wrapper textarea,
    .hw-form-wrapper select {
        width: 100%;
        background: #2a2a3e;
        border: 1px solid #3a3a55;
        border-radius: 10px;
        padding: 11px 14px;
        color: #fff;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
        -webkit-appearance: none;
    }

    .hw-form-wrapper select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
        cursor: pointer;
    }

    .hw-form-wrapper select option {
        background: #2a2a3e;
        color: #fff;
    }

    .hw-form-wrapper input:focus,
    .hw-form-wrapper textarea:focus,
    .hw-form-wrapper select:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.15);
    }

    .hw-form-wrapper textarea {
        resize: vertical;
        min-height: 120px;
    }

    .hw-form-wrapper .has-error input,
    .hw-form-wrapper .has-error textarea,
    .hw-form-wrapper .has-error select {
        border-color: #e05555;
    }

    .hw-form-wrapper .help-block {
        color: #e05555;
        font-size: 0.8rem;
        margin-top: 4px;
    }

    .btn-hw-save {
        background: #6c63ff;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 32px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 4px;
    }
    .btn-hw-save:hover {
        background: #574fd6;
    }

    body.light .hw-form-wrapper               { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
    body.light .hw-form-wrapper input,
    body.light .hw-form-wrapper textarea,
    body.light .hw-form-wrapper select        { background: #f5f5f5; border-color: #ddd; color: #111; }
    body.light .hw-form-wrapper select option { background: #f5f5f5; color: #111; }
    body.light .hw-form-wrapper label         { color: #666; }
</style>

<div class="hw-form-wrapper">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject_id')->dropDownList(
            $subjects,
            ['prompt' => '— Fach auswählen —']
    )->label('Fach') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Aufgabe') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 5])->label('Beschreibung') ?>

    <?= $form->field($model, 'due_date')->input('date')->label('Fälligkeitsdatum') ?>

    <div class="form-group">
        <?= Html::submitButton('💾 Speichern', ['class' => 'btn-hw-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>