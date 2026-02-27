<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => [
                        ['label' => 'Home', 'url' => ['/site/index']],
                        ['label' => 'About', 'url' => ['/site/about']],
                        ['label' => 'Contact', 'url' => ['/site/contact']],
                        Yii::$app->user->isGuest
                                ? ['label' => 'Login', 'url' => ['/site/index']]
                                : '<li class="nav-item">'
                                . Html::beginForm(['/site/logout'])
                                . Html::submitButton(
                                        'Logout (' . Yii::$app->user->identity->username . ')',
                                        ['class' => 'nav-link btn btn-link logout']
                                )
                                . Html::endForm()
                                . '</li>',

                        '<li class="nav-item d-flex align-items-center ms-2">
                <button class="toggle-btn" id="toggleBtn" onclick="toggleMode()">☀️ Light Mode</button>
            </li>',
                ]
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; studyorganizer <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer>

    <script>
        if (localStorage.getItem('mode') === 'light') {
            document.body.classList.add('light');
            document.getElementById('toggleBtn').textContent = '🌙 Dark Mode';
        }

        function toggleMode() {
            const body = document.body;
            const btn = document.getElementById('toggleBtn');

            if (body.classList.contains('light')) {
                body.classList.remove('light');
                btn.textContent = '☀️ Light Mode';
                localStorage.setItem('mode', 'dark');
            } else {
                body.classList.add('light');
                btn.textContent = '🌙 Dark Mode';
                localStorage.setItem('mode', 'light');
            }
        }
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>