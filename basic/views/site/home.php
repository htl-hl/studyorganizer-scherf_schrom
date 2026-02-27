<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Home';
?>

<div class="site-home">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <h1>Willkommen, <?= Html::encode(Yii::$app->user->identity->username) ?>! 👋</h1>
                <p>Du bist erfolgreich eingeloggt.</p>

                

            </div>
        </div>
    </div>
</div>
