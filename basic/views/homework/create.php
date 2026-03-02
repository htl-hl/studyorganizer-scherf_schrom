<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

$this->title = Yii::t('app', 'Create Homework');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Homeworks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="homework-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
