<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $loginModel */
/** @var app\models\RegistrationForm $registrationModel */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
?>
<div class="site-login text-center">
    <a href="/" style="position: absolute; top: 100px; left: 100px;">Zurück</a>

    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div id="login-form-container">
                <h2>Login</h2>
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($loginModel, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($loginModel, 'password')->passwordInput() ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div id="register-form-container" style="display: none;">
                <h2>Register</h2>
                <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($registrationModel, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($registrationModel, 'password')->passwordInput() ?>
                
                <?= $form->field($registrationModel, 'password_repeat')->passwordInput() ?>

                <?= $form->field($registrationModel, 'role')->dropDownList([
                    'student' => 'Schueler',
                    'teacher' => 'Lehrer',
                ]) ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="form-group mt-3">
                <button type="button" id="main-submit-button" class="btn btn-primary">Submit</button>
            </div>

            <div class="mt-3">
                <button id="show-register-form" class="btn btn-secondary">Register</button>
                <button id="show-login-form" class="btn btn-secondary" style="display: none;">Login</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    $('#show-register-form').on('click', function() {
        $('#login-form-container').hide();
        $('#register-form-container').show();
        $('#show-register-form').hide();
        $('#show-login-form').show();
    });

    $('#show-login-form').on('click', function() {
        $('#register-form-container').hide();
        $('#login-form-container').show();
        $('#show-login-form').hide();
        $('#show-register-form').show();
    });

    $('#main-submit-button').on('click', function() {
        if ($('#login-form-container').is(':visible')) {
            $('#login-form').submit();
        } else {
            $('#register-form').submit();
        }
    });
JS);
?>
