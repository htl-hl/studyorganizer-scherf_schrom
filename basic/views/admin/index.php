<?php

/** @var yii\web\View $this */
/** @var array $faecher */
/** @var array $lehrer */
/** @var array $alleFaecher */
/** @var array $users */
/** @var string $sucheFach */
/** @var string $sucheLehrer */
/** @var string $sucheUser */
/** @var int|null $editFachId */
/** @var int|null $editLehrerId */
/** @var int|null $editUserId */
/** @var bool $showFachForm */
/** @var bool $showLehrerForm */
/** @var bool $showUserForm */
/** @var string|null $error */
/** @var string|null $success */

use yii\bootstrap5\Html;

$this->title = 'Admin';
$csrf = Yii::$app->request->csrfToken;

$base               = 'index.php?r=';
$urlIndex           = $base . 'admin%2Findex';
$urlCreateFach      = $base . 'admin%2Fcreate-fach';
$urlUpdateFach      = $base . 'admin%2Fupdate-fach';
$urlDeleteFach      = $base . 'admin%2Fdelete-fach';
$urlCreateLehrer    = $base . 'admin%2Fcreate-lehrer';
$urlUpdateLehrer    = $base . 'admin%2Fupdate-lehrer';
$urlToggleLehrer    = $base . 'admin%2Ftoggle-lehrer';
$urlCreateUser      = $base . 'admin%2Fcreate-user';
$urlUpdateUserRole  = $base . 'admin%2Fupdate-user-role';
$urlDeleteUser      = $base . 'admin%2Fdelete-user';
$urlLogout          = $base . 'site%2Flogout';

$editFachId   = $editFachId   ?? Yii::$app->request->get('editFachId');
$editLehrerId = $editLehrerId ?? Yii::$app->request->get('editLehrerId');
$editUserId   = $editUserId   ?? Yii::$app->request->get('editUserId');

$roleBadge = [
        'admin'   => 'badge-admin',
        'teacher' => 'badge-teacher',
        'student' => 'badge-student',
];
$roleLabel = [
        'admin'   => '⚙️ Admin',
        'teacher' => '🧑‍🏫 Teacher',
        'student' => '🎒 Student',
];
?>

<style>
    :root {
        --h:            36px;
        --radius:       8px;
        --font-size:    0.875rem;
        --border:       #d1d5db;
        --border-focus: #6366f1;
        --shadow-focus: 0 0 0 3px rgba(99,102,241,.18);

        --c-primary:    #6366f1;
        --c-primary-h:  #4f46e5;
        --c-danger:     #ef4444;
        --c-danger-h:   #dc2626;
        --c-neutral:    #6b7280;
        --c-text:       #111827;
        --c-muted:      #6b7280;
        --c-surface:    #ffffff;
        --c-bg:         #f9fafb;

        --badge-admin-bg:    #fef2f2; --badge-admin-fg:    #b91c1c;
        --badge-teacher-bg:  #eff6ff; --badge-teacher-fg:  #1d4ed8;
        --badge-student-bg:  #f0fdf4; --badge-student-fg:  #15803d;
        --badge-aktiv-bg:    #f0fdf4; --badge-aktiv-fg:    #15803d;
        --badge-inaktiv-bg:  #f9fafb; --badge-inaktiv-fg:  #6b7280;
    }

    body:not(.light) {
        --border:       #2e3347;
        --border-focus: #6366f1;

        --c-text:       #e8eaf0;
        --c-muted:      #9ca3af;
        --c-surface:    #1a1e28;
        --c-bg:         #13161d;

        --badge-admin-bg:    rgba(185,28,28,0.15);    --badge-admin-fg:    #fca5a5;
        --badge-teacher-bg:  rgba(29,78,216,0.15);    --badge-teacher-fg:  #93c5fd;
        --badge-student-bg:  rgba(21,128,61,0.15);    --badge-student-fg:  #86efac;
        --badge-aktiv-bg:    rgba(21,128,61,0.15);    --badge-aktiv-fg:    #86efac;
        --badge-inaktiv-bg:  rgba(107,114,128,0.15);  --badge-inaktiv-fg:  #9ca3af;
    }

    .admin-page { padding: 1.5rem; }

    .card        { background: var(--c-surface); border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden; }
    .card-header { padding: .875rem 1.25rem; background: var(--c-bg); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: .75rem; }
    .card-body   { padding: 1.5rem; }

    .ctrl,
    input.ctrl,
    select.ctrl,
    button.ctrl,
    a.ctrl {
        box-sizing:      border-box !important;
        display:         inline-flex !important;
        align-items:     center !important;
        height:          var(--h) !important;
        min-height:      var(--h) !important;
        max-height:      var(--h) !important;
        padding:         0 .875rem !important;
        padding-top:     0 !important;
        padding-bottom:  0 !important;
        border-radius:   var(--radius) !important;
        font-size:       var(--font-size) !important;
        font-family:     inherit !important;
        font-weight:     500 !important;
        line-height:     var(--h) !important;
        white-space:     nowrap;
        transition:      background .15s, border-color .15s, box-shadow .15s, color .15s;
        cursor:          pointer;
        vertical-align:  middle;
        margin:          0;
    }

    input.ctrl,
    select.ctrl {
        background:      var(--c-surface) !important;
        border:          1px solid var(--border) !important;
        color:           var(--c-text) !important;
        outline:         none !important;
        justify-content: flex-start !important;
        font-weight:     400 !important;
    }
    input.ctrl::placeholder { color: #9ca3af; }
    input.ctrl:focus,
    select.ctrl:focus {
        border-color: var(--border-focus) !important;
        box-shadow:   var(--shadow-focus) !important;
    }
    select.ctrl {
        padding-right:       2rem !important;
        appearance:          none;
        -webkit-appearance:  none;
        background-image:    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") !important;
        background-repeat:   no-repeat !important;
        background-position: right .6rem center !important;
        background-color:    var(--c-surface) !important;
    }

    input.ctrl-full {
        box-sizing:    border-box;
        display:       block;
        width:         100%;
        height:        var(--h);
        padding:       0 .875rem;
        border-radius: var(--radius);
        border:        1px solid var(--border);
        font-size:     var(--font-size);
        font-family:   inherit;
        background:    var(--c-surface);
        color:         var(--c-text);
        outline:       none;
        transition:    border-color .15s, box-shadow .15s;
    }
    input.ctrl-full::placeholder { color: #9ca3af; }
    input.ctrl-full:focus {
        border-color: var(--border-focus);
        box-shadow:   var(--shadow-focus);
    }

    a.ctrl, button.ctrl {
        text-decoration: none;
        border:          1px solid transparent !important;
        justify-content: center !important;
    }
    .btn-primary         { background: var(--c-primary);  color: #fff; border-color: var(--c-primary) !important; }
    .btn-primary:hover   { background: var(--c-primary-h); border-color: var(--c-primary-h) !important; color: #fff; }

    .btn-outline         { background: transparent; color: var(--c-neutral); border-color: var(--border) !important; }
    .btn-outline:hover   { background: var(--c-bg); color: var(--c-text); border-color: #9ca3af !important; }

    .btn-outline-primary       { background: transparent; color: var(--c-primary); border-color: var(--c-primary) !important; }
    .btn-outline-primary:hover { background: var(--c-primary); color: #fff; }

    .btn-danger          { background: var(--c-danger);  color: #fff; border-color: var(--c-danger) !important; }
    .btn-danger:hover    { background: var(--c-danger-h); border-color: var(--c-danger-h) !important; }

    .btn-icon {
        display:         inline-flex;
        align-items:     center;
        justify-content: center;
        width:           32px;
        height:          32px;
        border-radius:   var(--radius);
        border:          1px solid var(--border);
        background:      var(--c-surface);
        font-size:       .95rem;
        cursor:          pointer;
        transition:      background .15s, border-color .15s, transform .1s;
        text-decoration: none;
        padding:         0;
        box-sizing:      border-box;
    }
    .btn-icon:hover             { background: var(--c-bg); border-color: #9ca3af; transform: translateY(-1px); }
    .btn-icon.btn-icon-del      { border-color: #fca5a5; }
    .btn-icon.btn-icon-del:hover { background: rgba(239,68,68,0.12); border-color: var(--c-danger); }

    .inline-form {
        display:        flex;
        flex-direction: row;
        align-items:    center;
        gap:            .5rem;
        flex-wrap:      wrap;
    }

    .search-section        { margin-bottom: 1.5rem; }
    .search-section label  { display: block; font-size: .8125rem; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem; }

    .create-card    { border: 1px solid var(--c-primary) !important; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; background: var(--c-bg); }
    .create-card h6 { margin: 0 0 1rem; font-weight: 700; font-size: .9375rem; color: var(--c-text); }
    .create-row     { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
    .create-row .ctrl      { flex: 1 1 160px; min-width: 0; }
    .create-row select.ctrl { flex: 0 0 180px; }

    .admin-table       { width: 100%; border-collapse: collapse; }
    .admin-table thead th {
        padding:        .625rem 1rem;
        font-size:      .75rem;
        font-weight:    700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color:          var(--c-muted);
        border-bottom:  2px solid var(--border);
        background:     var(--c-bg);
    }
    .admin-table tbody tr        { border-bottom: 1px solid var(--border); transition: background .12s; }
    .admin-table tbody tr:hover  { background: var(--c-bg); }
    .admin-table tbody tr:last-child { border-bottom: none; }
    .admin-table tbody td        { padding: .75rem 1rem; font-size: var(--font-size); vertical-align: middle; color: var(--c-text); }
    .admin-table .edit-row       { background: var(--c-bg) !important; }
    .admin-table .edit-row td    { padding: .625rem 1rem; }

    .action-cell { display: flex; gap: .375rem; align-items: center; }

    .role-badge, .badge-aktiv, .badge-inaktiv {
        display:       inline-flex;
        align-items:   center;
        gap:           .3rem;
        padding:       3px 10px;
        border-radius: 20px;
        font-size:     .78rem;
        font-weight:   600;
    }
    .badge-admin    { background: var(--badge-admin-bg);    color: var(--badge-admin-fg); }
    .badge-teacher  { background: var(--badge-teacher-bg);  color: var(--badge-teacher-fg); }
    .badge-student  { background: var(--badge-student-bg);  color: var(--badge-student-fg); }
    .badge-aktiv    { background: var(--badge-aktiv-bg);    color: var(--badge-aktiv-fg); }
    .badge-inaktiv  { background: var(--badge-inaktiv-bg);  color: var(--badge-inaktiv-fg); border: 1px solid var(--border); }

    .section-title { font-size: 1rem; font-weight: 700; color: var(--c-text); margin: 0 0 1rem; }
</style>

<div class="admin-page">

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger mb-3"><?= Html::encode($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success mb-3"><?= Html::encode($success) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <a href="<?= $urlIndex ?>&showFachForm=1&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
               class="ctrl btn-primary">+ Create Fächer</a>
            <form method="post" action="<?= $urlLogout ?>" style="margin:0;">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <button type="submit" class="ctrl btn-outline">Logout</button>
            </form>
        </div>

        <div class="card-body">

            <div class="search-section">
                <form method="get" action="index.php">
                    <input type="hidden" name="r"           value="admin/index">
                    <input type="hidden" name="sucheLehrer" value="<?= Html::encode($sucheLehrer) ?>">
                    <label>Suche</label>
                    <input type="text" name="sucheFach" value="<?= Html::encode($sucheFach) ?>"
                           class="ctrl-full" placeholder="Fach suchen…">
                </form>
            </div>

            <?php if ($showFachForm || Yii::$app->request->get('showFachForm')): ?>
                <div class="create-card">
                    <h6>📚 Neues Fach erstellen</h6>
                    <form method="post" action="<?= $urlCreateFach ?>">
                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                        <div class="create-row">
                            <input type="text" name="name" class="ctrl" placeholder="Fachname" required autofocus>
                            <button type="submit" class="ctrl btn-primary">Speichern</button>
                            <a href="<?= $urlIndex ?>" class="ctrl btn-outline">✕ Abbrechen</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <p class="section-title">Fächer</p>
            <table class="admin-table">
                <tbody>
                <?php if (empty($faecher)): ?>
                    <tr><td style="color:var(--c-muted);padding:1rem;">Keine Fächer gefunden.</td></tr>
                <?php else: ?>
                    <?php foreach ($faecher as $fach): ?>
                        <?php if ((int)$editFachId === (int)$fach['id']): ?>
                            <tr class="edit-row">
                                <td colspan="2">
                                    <form method="post" action="<?= $urlUpdateFach ?>" class="inline-form">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id"   value="<?= $fach['id'] ?>">
                                        <input type="text" name="name"
                                               value="<?= Html::encode($fach['name']) ?>"
                                               class="ctrl" style="width:280px;" required autofocus>
                                        <button type="submit" class="ctrl btn-primary">Speichern</button>
                                        <a href="<?= $urlIndex ?>" class="ctrl btn-outline">Abbrechen</a>
                                    </form>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td><?= Html::encode($fach['name']) ?></td>
                                <td style="text-align:right;">
                                    <div class="action-cell" style="justify-content:flex-end;">
                                        <a href="<?= $urlIndex ?>&editFachId=<?= $fach['id'] ?>&sucheFach=<?= urlencode($sucheFach) ?>"
                                           class="btn-icon" title="Bearbeiten">✏️</a>
                                        <form method="post" action="<?= $urlDeleteFach ?>" style="display:contents;"
                                              onsubmit="return confirm('Fach „<?= Html::encode(addslashes($fach['name'])) ?>" wirklich löschen?')">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id"   value="<?= $fach['id'] ?>">
                                        <button type="submit" class="btn-icon btn-icon-del" title="Löschen">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <a href="<?= $urlIndex ?>&showLehrerForm=1&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
               class="ctrl btn-primary">+ Create Lehrer</a>
            <form method="post" action="<?= $urlLogout ?>" style="margin:0;">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <button type="submit" class="ctrl btn-outline">Logout</button>
            </form>
        </div>

        <div class="card-body">

            <div class="search-section">
                <form method="get" action="index.php">
                    <input type="hidden" name="r"         value="admin/index">
                    <input type="hidden" name="sucheFach" value="<?= Html::encode($sucheFach) ?>">
                    <label>Suche</label>
                    <input type="text" name="sucheLehrer" value="<?= Html::encode($sucheLehrer) ?>"
                           class="ctrl-full" placeholder="Lehrer suchen…">
                </form>
            </div>

            <?php if ($showLehrerForm || Yii::$app->request->get('showLehrerForm')): ?>
                <div class="create-card">
                    <h6>🧑‍🏫 Neuen Lehrer erstellen</h6>
                    <form method="post" action="<?= $urlCreateLehrer ?>">
                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                        <div class="create-row">
                            <input type="text" name="name" class="ctrl" placeholder="Name" required autofocus>
                            <select name="subject_id" class="ctrl" required>
                                <option value="">— Fach wählen —</option>
                                <?php foreach ($alleFaecher as $f): ?>
                                    <option value="<?= $f['id'] ?>"><?= Html::encode($f['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="ctrl btn-primary">Speichern</button>
                            <a href="<?= $urlIndex ?>" class="ctrl btn-outline">✕ Abbrechen</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <table class="admin-table">
                <thead>
                <tr>
                    <th>Lehrer</th>
                    <th>Fach</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($lehrer)): ?>
                    <tr><td colspan="4" style="color:var(--c-muted);padding:1rem;">Keine Lehrer gefunden.</td></tr>
                <?php else: ?>
                    <?php foreach ($lehrer as $l): ?>
                        <?php if ((int)$editLehrerId === (int)$l['id']): ?>
                            <tr class="edit-row">
                                <td colspan="4">
                                    <form method="post" action="<?= $urlUpdateLehrer ?>" class="inline-form">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id"   value="<?= $l['id'] ?>">
                                        <input type="text" name="name"
                                               value="<?= Html::encode($l['name']) ?>"
                                               class="ctrl" style="width:180px;" required autofocus>
                                        <select name="subject_id" class="ctrl" style="width:160px;" required>
                                            <option value="">— Fach —</option>
                                            <?php foreach ($alleFaecher as $f): ?>
                                                <option value="<?= $f['id'] ?>"
                                                        <?= (int)$f['id'] === (int)$l['fach_id'] ? 'selected' : '' ?>>
                                                    <?= Html::encode($f['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="ctrl btn-primary">Speichern</button>
                                        <a href="<?= $urlIndex ?>" class="ctrl btn-outline">Abbrechen</a>
                                    </form>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td><?= Html::encode($l['name']) ?></td>
                                <td><?= Html::encode($l['fach']) ?></td>
                                <td>
                                    <?= $l['aktiv']
                                            ? '<span class="badge-aktiv">✔ Aktiv</span>'
                                            : '<span class="badge-inaktiv">✘ Inaktiv</span>' ?>
                                </td>
                                <td>
                                    <div class="action-cell" style="justify-content:flex-end;">
                                        <form method="post" action="<?= $urlToggleLehrer ?>" style="display:contents;">
                                            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                            <input type="hidden" name="id"   value="<?= $l['id'] ?>">
                                            <button type="submit" class="ctrl btn-outline" style="font-size:.8rem;">
                                                <?= $l['aktiv'] ? 'Inaktivieren' : 'Aktivieren' ?>
                                            </button>
                                        </form>
                                        <a href="<?= $urlIndex ?>&editLehrerId=<?= $l['id'] ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                                           class="btn-icon" title="Bearbeiten">✏️</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>