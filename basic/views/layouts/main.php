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
        $svgImg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-day" viewBox="0 0 16 16">
                      <path d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43m.094 5.093h.672V7.418h-.672z"/>
                      <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                   </svg>';

        NavBar::begin([
                'brandLabel' =>$svgImg .'<span style="margin-left: 5px;">Study-Oranizer</span>',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => [
                        ['label' => 'Home', 'url' => ['/site/index']],
                        ['label' => 'About', 'url' => ['/site/about']],
                        ['label' => 'Contact', 'url' => ['/site/contact']],
                        ['label' => 'Hausaufgaben', 'url' => ['/homework/index']],
                        ['label' => 'Fächer', 'url' => ['/subject/index']],
                        ['label' => 'Lehrer', 'url' => ['/teacher/index']],
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