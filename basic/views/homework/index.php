<?php

use app\models\Homework;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\HomeworkSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Hausaufgaben');
?>

<style>
    .hw-wrapper {
        background: var(--card-bg, #1e1e2e);
        border-radius: 16px;
        padding: 24px;
        margin-top: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }

    .hw-top-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .btn-create-hw {
        flex: 1;
        background: #6c63ff;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
        display: block;
    }
    .btn-create-hw:hover {
        background: #574fd6;
        color: #fff;
        text-decoration: none;
    }

    .btn-logout-hw {
        background: transparent;
        border: 1px solid #555;
        color: #aaa;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.9rem;
        cursor: pointer;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-logout-hw:hover {
        border-color: #888;
        color: #fff;
    }

    .hw-search {
        margin-bottom: 18px;
    }
    .hw-search label {
        color: #aaa;
        font-size: 0.85rem;
        margin-bottom: 4px;
        display: block;
    }
    .hw-search input {
        width: 100%;
        background: #2a2a3e;
        border: 1px solid #3a3a55;
        border-radius: 8px;
        padding: 10px 14px;
        color: #fff;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .hw-search input:focus {
        border-color: #6c63ff;
    }

    .hw-table-head {
        display: grid;
        grid-template-columns: 2fr 2fr 1.5fr 1fr 1fr;
        padding: 8px 12px;
        color: #888;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .hw-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1.5fr 1fr 1fr;
        align-items: center;
        padding: 12px 14px;
        border-radius: 10px;
        margin-bottom: 6px;
        background: #2a2a3e;
        transition: background 0.15s;
    }
    .hw-row:hover {
        background: #32324a;
    }

    .hw-row-none    { border-left: 3px solid transparent; }
    .hw-row-info    { border-left: 3px solid #4a9eff; }
    .hw-row-warning { border-left: 3px solid #f5c842; }
    .hw-row-danger  { border-left: 3px solid #e05555; }
    .hw-row-done    { border-left: 3px solid #3ecf6e; }
    .hw-row-guest   { border-left: 3px solid #555; }

    .hw-cell {
        color: #ccc;
        font-size: 0.92rem;
    }
    .hw-cell-subject {
        font-weight: 600;
        color: #fff;
    }

    .hw-status-guest {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(120, 120, 120, 0.15);
        color: #888;
        border: 1px solid rgba(120, 120, 120, 0.35);
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .hw-toggle-wrap {
        display: flex;
        gap: 5px;
    }
    .hw-toggle-btn {
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid transparent;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .hw-toggle-true {
        background: rgba(62, 207, 110, 0.12);
        color: #3ecf6e;
        border-color: rgba(62, 207, 110, 0.35);
    }
    .hw-toggle-true:hover, .hw-toggle-true.active {
        background: rgba(62, 207, 110, 0.3);
        color: #3ecf6e;
        border-color: #3ecf6e;
    }
    .hw-toggle-false {
        background: rgba(224, 85, 85, 0.1);
        color: #e05555;
        border-color: rgba(224, 85, 85, 0.3);
    }
    .hw-toggle-false:hover, .hw-toggle-false.active {
        background: rgba(224, 85, 85, 0.25);
        color: #e05555;
        border-color: #e05555;
    }
    .hw-toggle-btn.active {
        font-weight: 700;
        box-shadow: 0 0 0 2px currentColor;
    }

    .hw-status-done {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(62, 207, 110, 0.12);
        color: #3ecf6e;
        border: 1px solid rgba(62, 207, 110, 0.35);
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .hw-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .hw-btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.85rem;
        transition: opacity 0.15s;
        cursor: pointer;
    }
    .hw-btn-icon:hover { opacity: 0.75; }
    .hw-btn-edit   { background: #2e5aac; color: #7eb8ff; }
    .hw-btn-delete { background: #5a2e2e; color: #ff7e7e; }

    .hw-empty {
        text-align: center;
        padding: 40px 20px;
        color: #666;
        font-size: 0.95rem;
    }

    body.light .hw-wrapper       { background: #ffffff; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
    body.light .hw-search input  { background: #f5f5f5; border-color: #ddd; color: #333; }
    body.light .hw-table-head    { color: #999; }
    body.light .hw-row           { background: #f8f8f8; }
    body.light .hw-row:hover     { background: #efefef; }
    body.light .hw-cell          { color: #444; }
    body.light .hw-cell-subject  { color: #111; }
    body.light .btn-logout-hw    { color: #555; border-color: #ccc; }
    body.light .btn-logout-hw:hover { color: #111; }
</style>

<div class="homework-index" style="max-width: 900px; margin: 0 auto; padding: 10px 0 40px;">

    <div class="hw-wrapper">

        <div class="hw-top-bar">
            <?= Html::a('Hausübung erstellen', ['create'], ['class' => 'btn-create-hw']) ?>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::beginForm(['/site/logout']) ?>
                <?= Html::submitButton('Logout', ['class' => 'btn-logout-hw']) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>

        <div class="hw-search">
            <label>Suche</label>
            <input type="text" id="hwSearchInput" placeholder="Hausübung suchen..." oninput="filterRows()">
        </div>

        <div class="hw-table-head">
            <span>Fach</span>
            <span>Aufgabe</span>
            <span>Fälligkeitsdatum</span>
            <span>Status</span>
            <span>Bearbeiten</span>
        </div>

        <div id="hwList">
            <?php
            $models   = $dataProvider->getModels();
            $isGuest  = Yii::$app->user->isGuest;
            $now      = new DateTime();

            if (empty($models)): ?>
                <div class="hw-empty">Keine Hausaufgaben vorhanden.</div>
            <?php else:
                foreach ($models as $model):
                    $isDone  = (int)$model->is_finished === 1;
                    $subject = $model->subject ? Html::encode($model->subject->name) : 'Fach #' . $model->subject_id;
                    $dueDate = $model->due_date ? Yii::$app->formatter->asDate($model->due_date, 'dd.MM.yyyy') : '—';

                    if ($isGuest) {
                        $urgencyClass = 'hw-row-guest';
                    } elseif ($isDone) {
                        $urgencyClass = 'hw-row-done';
                    } elseif ($model->due_date) {
                        $due      = new DateTime($model->due_date);
                        $daysLeft = ($due->getTimestamp() - $now->getTimestamp()) / 86400;

                        if ($daysLeft < 0) {
                            $urgencyClass = 'hw-row-danger';
                        } elseif ($daysLeft < 1) {
                            $urgencyClass = 'hw-row-danger';
                        } elseif ($daysLeft < 7) {
                            $urgencyClass = 'hw-row-warning';
                        } elseif ($daysLeft < 14) {
                            $urgencyClass = 'hw-row-info';
                        } else {
                            $urgencyClass = 'hw-row-none';
                        }
                    } else {
                        $urgencyClass = 'hw-row-none';
                    }
                    ?>
                    <div class="hw-row <?= $urgencyClass ?>" data-search="<?= strtolower(Html::encode($model->title) . ' ' . $subject) ?>">
                        <div class="hw-cell hw-cell-subject"><?= $subject ?></div>
                        <div class="hw-cell"><?= Html::encode($model->title) ?></div>
                        <div class="hw-cell"><?= $dueDate ?></div>
                        <div class="hw-cell">
                            <?php if ($isGuest): ?>
                                <span class="hw-status-guest">● Unbekannt</span>
                            <?php else: ?>
                                <div class="hw-toggle-wrap">
                                    <?= Html::a('✓ Erledigt',
                                            ['set-finished', 'id' => $model->id, 'value' => 1],
                                            [
                                                    'class' => 'hw-toggle-btn hw-toggle-true' . ($isDone ? ' active' : ''),
                                                    'data'  => ['method' => 'post'],
                                                    'title' => 'Als erledigt markieren',
                                            ]
                                    ) ?>
                                    <?= Html::a('✗ Nicht erledigt',
                                            ['set-finished', 'id' => $model->id, 'value' => 0],
                                            [
                                                    'class' => 'hw-toggle-btn hw-toggle-false' . (!$isDone ? ' active' : ''),
                                                    'data'  => ['method' => 'post'],
                                                    'title' => 'Als offen markieren',
                                            ]
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="hw-cell hw-actions">
                            <?php if (!$isGuest): ?>
                                <?= Html::a('✏️', ['update', 'id' => $model->id], ['class' => 'hw-btn-icon hw-btn-edit', 'title' => 'Bearbeiten']) ?>
                                <?= Html::a('🗑', ['delete', 'id' => $model->id], [
                                        'class' => 'hw-btn-icon hw-btn-delete',
                                        'title' => 'Löschen',
                                        'data'  => [
                                                'confirm' => 'Hausübung wirklich löschen?',
                                                'method'  => 'post',
                                        ],
                                ]) ?>
                            <?php else: ?>
                                <span style="color:#555; font-size:0.8rem;">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>

    </div>
</div>

<script>
    function filterRows() {
        const q = document.getElementById('hwSearchInput').value.toLowerCase();
        document.querySelectorAll('#hwList .hw-row').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    }
</script>