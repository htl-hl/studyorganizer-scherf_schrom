<?php

/** @var yii\web\View $this */
/** @var app\models\Homework[] $pendingHomework */

use yii\bootstrap5\Html;

$this->title = 'Home';
?>

<div class="site-home">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-10">

            <!-- Dismissible Welcome Card -->
            <div class="card p-4 mb-4 welcome-card" id="welcomeCard">
                <button class="welcome-close" onclick="dismissWelcome()" title="Schließen">✖</button>
                <h1>Willkommen, <?= Html::encode(Yii::$app->user->identity->username) ?>! 👋</h1>
                <p class="mb-0">Du bist erfolgreich eingeloggt.</p>
            </div>

            <h4 class="mb-3">📋 Ausstehende Hausaufgaben</h4>

            <?php if (empty($pendingHomework)): ?>
                <div class="card p-4 text-center text-muted">
                    🎉 Keine ausstehenden Hausaufgaben – alles erledigt!
                </div>
            <?php else: ?>
                <div class="homework-grid">
                    <?php foreach ($pendingHomework as $hw): ?>
                        <div class="homework-card">
                            <div class="homework-card-header">
                                <span class="homework-subject"><?= Html::encode($hw->subject->name ?? '-') ?></span>
                                <span class="homework-due <?= (strtotime($hw->due_date) < time()) ? 'overdue' : '' ?>">
                                    📅 <?= Html::encode(date('d.m.Y', strtotime($hw->due_date))) ?>
                                </span>
                            </div>
                            <div class="homework-card-body">
                                <div class="homework-title"><?= Html::encode($hw->title) ?></div>
                                <?php if (!empty($hw->description)): ?>
                                    <div class="homework-description"><?= Html::encode($hw->description) ?></div>
                                <?php else: ?>
                                    <div class="homework-description text-muted fst-italic">Keine Beschreibung</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
    /* Welcome Card */
    .welcome-card {
        position: relative;
    }
    .welcome-close {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        font-size: 1rem;
        color: #999;
        cursor: pointer;
        line-height: 1;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background 0.15s, color 0.15s;
    }
    .welcome-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    /* Grid Layout */
    .homework-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    /* Homework Card */
    .homework-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        display: flex;
        flex-direction: column;
    }
    .homework-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .homework-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: linear-gradient(135deg, #6c63ff, #4e46d4);
        color: #fff;
        gap: 8px;
    }
    .homework-subject {
        font-weight: 700;
        font-size: 1rem;
    }
    .homework-due {
        font-size: 0.78rem;
        opacity: 0.9;
        white-space: nowrap;
    }
    .homework-due.overdue {
        color: #ffcdd2;
        font-weight: 700;
    }
    .homework-card-body {
        padding: 16px;
        flex: 1;
    }
    .homework-title {
        font-size: 1rem;
        font-weight: 600;
        color: #222;
        margin-bottom: 6px;
    }
    .homework-description {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.5;
    }

    /* Dark mode */
    body:not(.light) .homework-card {
        background: #1e1e2e;
    }
    body:not(.light) .homework-title {
        color: #eee;
    }
    body:not(.light) .homework-description {
        color: #aaa;
    }
    body:not(.light) .welcome-close:hover {
        background: #333;
        color: #eee;
    }
</style>

<script>
    // Welcome card: beim Laden prüfen ob bereits weggeklickt
    if (sessionStorage.getItem('welcomeDismissed')) {
        document.getElementById('welcomeCard').style.display = 'none';
    }

    function dismissWelcome() {
        const card = document.getElementById('welcomeCard');
        card.style.transition = 'opacity 0.3s ease';
        card.style.opacity = '0';
        setTimeout(() => {
            card.style.display = 'none';
            sessionStorage.setItem('welcomeDismissed', '1');
        }, 300);
    }
</script>