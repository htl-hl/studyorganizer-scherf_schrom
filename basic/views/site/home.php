<?php

/** @var yii\web\View $this */
/** @var app\models\Homework[] $pendingHomework */

use yii\bootstrap5\Html;

$this->title = 'Home';
?>

<div class="site-home">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">

            <div class="card p-4 mb-4">
                <h1>Willkommen, <?= Html::encode(Yii::$app->user->identity->username) ?>! 👋</h1>
                <p>Du bist erfolgreich eingeloggt.</p>
            </div>

            <!-- Ausstehende Hausaufgaben -->
            <div class="card p-4">
                <h4 class="mb-3">📋 Ausstehende Hausaufgaben</h4>

                <?php if (empty($pendingHomework)): ?>
                    <p class="text-muted">🎉 Keine ausstehenden Hausaufgaben – alles erledigt!</p>
                <?php else: ?>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>FACH</th>
                            <th>AUFGABE</th>
                            <th>FÄLLIGKEITSDATUM</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pendingHomework as $hw): ?>
                            <tr>
                                <td><strong><?= Html::encode($hw->subject->name ?? '-') ?></strong></td>
                                <td><?= Html::encode($hw->title) ?></td>
                                <td><?= Html::encode(date('d.m.Y', strtotime($hw->due_date))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>