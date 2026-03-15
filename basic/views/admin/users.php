<?php

/** @var yii\web\View $this */
/** @var array $users */
/** @var string $sucheUser */
/** @var int|null $editUserId */
/** @var int|null $changePasswordId */
/** @var bool $showUserForm */
/** @var string|null $error */
/** @var string|null $success */

use yii\bootstrap5\Html;

$this->title = 'User-Verwaltung';
$csrf = Yii::$app->request->csrfToken;

$base                  = 'index.php?r=';
$urlUsers              = $base . 'admin%2Fusers';
$urlCreateUser         = $base . 'admin%2Fcreate-user';
$urlUpdateUserRole     = $base . 'admin%2Fupdate-user-role';
$urlChangePassword     = $base . 'admin%2Fchange-password';
$urlDeleteUser         = $base . 'admin%2Fdelete-user';
$urlLogout             = $base . 'site%2Flogout';

$editUserId       = $editUserId       ?? Yii::$app->request->get('editUserId');
$changePasswordId = $changePasswordId ?? Yii::$app->request->get('changePasswordId');

$roleBadge = ['admin' => 'badge-admin', 'teacher' => 'badge-teacher', 'student' => 'badge-student'];
$roleLabel = ['admin' => '⚙️ Admin', 'teacher' => '🧑‍🏫 Teacher', 'student' => '🎒 Student'];
?>

<style>
    :root {
        --h:           36px;
        --radius:      8px;
        --font-size:   0.875rem;
        --border:      #d1d5db;
        --border-focus:#6366f1;
        --shadow-focus:0 0 0 3px rgba(99,102,241,.18);

        --c-primary:   #6366f1;
        --c-primary-h: #4f46e5;
        --c-danger:    #ef4444;
        --c-danger-h:  #dc2626;
        --c-warning:   #f59e0b;
        --c-warning-h: #d97706;
        --c-neutral:   #6b7280;
        --c-neutral-h: #4b5563;
        --c-text:      #111827;
        --c-muted:     #6b7280;
        --c-surface:   #ffffff;
        --c-bg:        #f9fafb;

        --badge-admin-bg:    #fef2f2; --badge-admin-fg:    #b91c1c;
        --badge-teacher-bg:  #eff6ff; --badge-teacher-fg:  #1d4ed8;
        --badge-student-bg:  #f0fdf4; --badge-student-fg:  #15803d;
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
        box-sizing:     border-box !important;
        display:        inline-flex !important;
        align-items:    center !important;
        height:         var(--h) !important;
        min-height:     var(--h) !important;
        max-height:     var(--h) !important;
        padding:        0 .875rem !important;
        border-radius:  var(--radius) !important;
        font-size:      var(--font-size) !important;
        font-family:    inherit !important;
        font-weight:    500 !important;
        line-height:    var(--h) !important;
        white-space:    nowrap;
        transition:     background .15s, border-color .15s, box-shadow .15s, color .15s;
        cursor:         pointer;
        vertical-align: middle;
        margin:         0;
    }

    input.ctrl,
    select.ctrl {
        background:      var(--c-surface) !important;
        border:          1px solid var(--border) !important;
        color:           var(--c-text) !important;
        outline:         none !important;
        justify-content: flex-start !important;
        font-weight:     400 !important;
        padding-top:     0 !important;
        padding-bottom:  0 !important;
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
        background-image:    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat:   no-repeat;
        background-position: right .6rem center;
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
        border: 1px solid transparent !important;
        justify-content: center !important;
    }

    .btn-primary         { background: var(--c-primary);  color: #fff; border-color: var(--c-primary) !important; }
    .btn-primary:hover   { background: var(--c-primary-h); border-color: var(--c-primary-h) !important; color: #fff; }

    .btn-danger          { background: var(--c-danger);   color: #fff; border-color: var(--c-danger) !important; }
    .btn-danger:hover    { background: var(--c-danger-h);  border-color: var(--c-danger-h) !important; color: #fff; }

    .btn-warning-outline         { background: transparent; color: var(--c-warning); border-color: var(--c-warning) !important; }
    .btn-warning-outline:hover   { background: var(--c-warning); color: #fff; }

    .btn-outline         { background: transparent; color: var(--c-neutral); border-color: var(--border) !important; }
    .btn-outline:hover   { background: var(--c-bg); color: var(--c-text); border-color: #9ca3af !important; }

    .btn-outline-primary         { background: transparent; color: var(--c-primary); border-color: var(--c-primary) !important; }
    .btn-outline-primary:hover   { background: var(--c-primary); color: #fff; }

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
    }
    .btn-icon:hover              { background: var(--c-bg); border-color: #9ca3af; transform: translateY(-1px); }
    .btn-icon.btn-icon-del       { border-color: #fca5a5; }
    .btn-icon.btn-icon-del:hover { background: rgba(239,68,68,0.12); border-color: var(--c-danger); }
    .btn-icon.btn-icon-pw:hover  { background: rgba(245,158,11,0.12); border-color: var(--c-warning); }

    .search-section { margin-bottom: 1.5rem; }
    .search-section label { display: block; font-size: .8125rem; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .4rem; }

    .create-card    { border: 1px solid var(--c-primary); border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; background: var(--c-bg); }
    .create-card h6 { margin: 0 0 1rem; font-weight: 700; font-size: .9375rem; color: var(--c-text); }
    .create-row     { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }
    .create-row .ctrl        { flex: 1 1 160px; min-width: 0; }
    .create-row select.ctrl  { flex: 0 0 160px; }
    .create-row .ctrl-actions { display: flex; gap: .5rem; flex-shrink: 0; }

    .admin-table { width: 100%; border-collapse: collapse; }
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
    .admin-table thead th:last-child { text-align: right; }
    .admin-table tbody tr            { border-bottom: 1px solid var(--border); transition: background .12s; }
    .admin-table tbody tr:hover      { background: var(--c-bg); }
    .admin-table tbody tr:last-child { border-bottom: none; }
    .admin-table tbody td            { padding: .75rem 1rem; font-size: var(--font-size); vertical-align: middle; color: var(--c-text); }
    .admin-table tbody td:last-child { text-align: right; }

    .edit-row    { background: var(--c-bg) !important; }
    .edit-row td { padding: .625rem 1rem; }
    .inline-form { display: flex; flex-direction: row; align-items: center; gap: .5rem; flex-wrap: wrap; }

    .action-cell { display: flex; gap: .375rem; justify-content: flex-end; align-items: center; }

    .role-badge {
        display:       inline-flex;
        align-items:   center;
        gap:           .3rem;
        padding:       3px 10px;
        border-radius: 20px;
        font-size:     .78rem;
        font-weight:   600;
    }
    .badge-admin   { background: var(--badge-admin-bg);   color: var(--badge-admin-fg); }
    .badge-teacher { background: var(--badge-teacher-bg); color: var(--badge-teacher-fg); }
    .badge-student { background: var(--badge-student-bg); color: var(--badge-student-fg); }
    .role-plain    { font-size: var(--font-size); color: var(--c-muted); }
</style>

<div class="admin-page">
    <div class="card">

        <div class="card-header">
            <div style="display:flex;gap:.5rem;align-items:center;">
                <a href="<?= $urlUsers ?>&showUserForm=1" class="ctrl btn-primary">+ Create User</a>
            </div>
            <form method="post" action="<?= $urlLogout ?>" style="margin:0;">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <button type="submit" class="ctrl btn-outline">Logout</button>
            </form>
        </div>

        <div class="card-body">

            <div class="search-section">
                <form method="get" action="index.php">
                    <input type="hidden" name="r" value="admin/users">
                    <label>Suche</label>
                    <input type="text" name="sucheUser"
                           value="<?= Html::encode($sucheUser) ?>"
                           class="ctrl-full"
                           placeholder="User suchen…">
                </form>
            </div>

            <?php if ($showUserForm || Yii::$app->request->get('showUserForm')): ?>
                <div class="create-card">
                    <h6>👤 Neuen User erstellen</h6>
                    <form method="post" action="<?= $urlCreateUser ?>">
                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                        <input type="hidden" name="redirect" value="users">
                        <div class="create-row">
                            <input  type="text"     name="username" class="ctrl" placeholder="Username"  required autofocus>
                            <input  type="password" name="password" class="ctrl" placeholder="Passwort"  required>
                            <select name="role" class="ctrl" required>
                                <option value="student">🎒 Student</option>
                                <option value="teacher">🧑‍🏫 Teacher</option>
                                <option value="admin">⚙️ Admin</option>
                            </select>
                            <div class="ctrl-actions">
                                <button type="submit" class="ctrl btn-primary">Speichern</button>
                                <a href="<?= $urlUsers ?>" class="ctrl btn-outline">✕ Abbrechen</a>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <table class="admin-table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Rolle</th>
                    <th>Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="3" style="color:var(--c-muted);padding:1.25rem 1rem;">Keine User gefunden.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>

                        <?php if ((int)$editUserId === (int)$u['id']): ?>
                            <tr class="edit-row">
                                <td><strong><?= Html::encode($u['username']) ?></strong></td>
                                <td colspan="2">
                                    <form method="post" action="<?= $urlUpdateUserRole ?>" class="inline-form">
                                        <input type="hidden" name="_csrf"    value="<?= $csrf ?>">
                                        <input type="hidden" name="id"       value="<?= $u['id'] ?>">
                                        <input type="hidden" name="redirect" value="users">
                                        <select name="role" class="ctrl" style="width:160px;" required>
                                            <option value="student" <?= $u['role']==='student'?'selected':'' ?>>🎒 Student</option>
                                            <option value="teacher" <?= $u['role']==='teacher'?'selected':'' ?>>🧑‍🏫 Teacher</option>
                                            <option value="admin"   <?= $u['role']==='admin'  ?'selected':'' ?>>⚙️ Admin</option>
                                        </select>
                                        <button type="submit" class="ctrl btn-outline-primary">Speichern</button>
                                        <a href="<?= $urlUsers ?>&sucheUser=<?= urlencode($sucheUser) ?>" class="ctrl btn-outline">Abbrechen</a>
                                    </form>
                                </td>
                            </tr>

                        <?php elseif ((int)$changePasswordId === (int)$u['id']): ?>
                            <tr class="edit-row">
                                <td><strong><?= Html::encode($u['username']) ?></strong></td>
                                <td colspan="2">
                                    <form method="post" action="<?= $urlChangePassword ?>" class="inline-form">
                                        <input type="hidden" name="_csrf"           value="<?= $csrf ?>">
                                        <input type="hidden" name="id"              value="<?= $u['id'] ?>">
                                        <input type="hidden" name="redirect"        value="users">
                                        <input type="password" name="password"         class="ctrl" style="width:160px;" placeholder="Neues Passwort"      required autofocus>
                                        <input type="password" name="password_confirm" class="ctrl" style="width:160px;" placeholder="Passwort bestätigen" required>
                                        <button type="submit" class="ctrl btn-warning-outline">Speichern</button>
                                        <a href="<?= $urlUsers ?>&sucheUser=<?= urlencode($sucheUser) ?>" class="ctrl btn-outline">Abbrechen</a>
                                    </form>
                                </td>
                            </tr>

                        <?php else: ?>
                            <tr>
                                <td><?= Html::encode($u['username']) ?></td>
                                <td>
                                    <?php if (isset($roleBadge[$u['role']])): ?>
                                        <span class="role-badge <?= $roleBadge[$u['role']] ?>">
                                            <?= $roleLabel[$u['role']] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="role-plain"><?= Html::encode($u['role']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <a href="<?= $urlUsers ?>&editUserId=<?= $u['id'] ?>&sucheUser=<?= urlencode($sucheUser) ?>"
                                           class="btn-icon" title="Rolle ändern">✏️</a>
                                        <a href="<?= $urlUsers ?>&changePasswordId=<?= $u['id'] ?>&sucheUser=<?= urlencode($sucheUser) ?>"
                                           class="btn-icon btn-icon-pw" title="Passwort ändern">🔑</a>
                                        <?php if ($u['username'] !== 'admin'): ?>
                                            <form method="post" action="<?= $urlDeleteUser ?>" style="display:contents;"
                                                  onsubmit="return confirm('User wirklich löschen?')">
                                                <input type="hidden" name="_csrf"    value="<?= $csrf ?>">
                                                <input type="hidden" name="id"       value="<?= $u['id'] ?>">
                                                <input type="hidden" name="redirect" value="users">
                                                <button type="submit" class="btn-icon btn-icon-del" title="Löschen">🗑️</button>
                                            </form>
                                        <?php endif; ?>
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