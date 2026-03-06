<?php

/** @var yii\web\View $this */
/** @var array $faecher */
/** @var array $lehrer */
/** @var array $alleFaecher */
/** @var string $sucheFach */
/** @var string $sucheLehrer */
/** @var int|null $editFachId */
/** @var int|null $editLehrerId */
/** @var bool $showFachForm */
/** @var bool $showLehrerForm */
/** @var string|null $error */

use yii\bootstrap5\Html;

$this->title = 'Admin';
$csrf = Yii::$app->request->csrfToken;

$base           = 'index.php?r=';
$urlIndex       = $base . 'admin%2Findex';
$urlCreateFach  = $base . 'admin%2Fcreate-fach';
$urlUpdateFach  = $base . 'admin%2Fupdate-fach';
$urlDeleteFach  = $base . 'admin%2Fdelete-fach';
$urlCreateLehrer = $base . 'admin%2Fcreate-lehrer';
$urlUpdateLehrer = $base . 'admin%2Fupdate-lehrer';
$urlToggleLehrer = $base . 'admin%2Ftoggle-lehrer';
$urlLogout      = $base . 'site%2Flogout';
?>

<div class="admin-page">

    <?php if ($error): ?>
        <div class="alert alert-danger mb-3"><?= Html::encode($error) ?></div>
    <?php endif; ?>

    <!-- ═══════════════════ FÄCHER CARD ═══════════════════ -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="<?= $urlIndex ?>&showFachForm=1&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
               class="btn btn-primary btn-sm">+ Create Fächer</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">Admin</span>
                <form method="post" action="<?= $urlLogout ?>" style="display:inline">
                    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <div class="card-body">

            <!-- Suche Fach -->
            <form method="get" action="index.php" class="mb-3">
                <input type="hidden" name="r" value="admin/index">
                <input type="hidden" name="sucheLehrer" value="<?= Html::encode($sucheLehrer) ?>">
                <label class="form-label">Suche</label>
                <input type="text" name="sucheFach" value="<?= Html::encode($sucheFach) ?>"
                       class="form-control" placeholder="Fach suchen...">
            </form>

            <!-- Create Fach Form -->
            <?php if ($showFachForm || Yii::$app->request->get('showFachForm')): ?>
                <div class="card mb-3" style="border:1px solid #6366f1;">
                    <div class="card-body">
                        <h6>Neues Fach erstellen</h6>
                        <form method="post" action="<?= $urlCreateFach ?>">
                            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control" placeholder="Fachname" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
                            <a href="<?= $urlIndex ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                               class="btn btn-outline-secondary btn-sm">Abbrechen</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <h5 class="mb-3">Fächer</h5>

            <table class="admin-table">
                <?php if (empty($faecher)): ?>
                    <tr>
                        <td colspan="2" class="text-muted" style="padding:12px;">Keine Fächer gefunden.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faecher as $fach): ?>
                        <tr>
                            <?php if ((int)$editFachId === (int)$fach['id']): ?>
                                <td colspan="2">
                                    <form method="post" action="<?= $urlUpdateFach ?>"
                                          class="d-flex gap-2 align-items-center">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id" value="<?= $fach['id'] ?>">
                                        <input type="text" name="name" value="<?= Html::encode($fach['name']) ?>"
                                               class="form-control form-control-sm" style="max-width:300px;" required autofocus>
                                        <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
                                        <a href="<?= $urlIndex ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                                           class="btn btn-outline-secondary btn-sm">Abbrechen</a>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td><?= Html::encode($fach['name']) ?></td>
                                <td class="action-btns">
                                    <a href="<?= $urlIndex ?>&editFachId=<?= $fach['id'] ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                                       class="btn-icon btn-edit" title="Bearbeiten">✏️</a>
                                    <form method="post" action="<?= $urlDeleteFach ?>" style="display:inline"
                                          onsubmit="return confirm('Fach wirklich löschen?')">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id" value="<?= $fach['id'] ?>">
                                        <button type="submit" class="btn-icon btn-delete" title="Löschen">🗑️</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- ═══════════════════ LEHRER CARD ═══════════════════ -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="<?= $urlIndex ?>&showLehrerForm=1&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
               class="btn btn-primary btn-sm">+ Create Lehrer</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">Admin</span>
                <form method="post" action="<?= $urlLogout ?>" style="display:inline">
                    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <div class="card-body">

            <!-- Suche Lehrer -->
            <form method="get" action="index.php" class="mb-3">
                <input type="hidden" name="r" value="admin/index">
                <input type="hidden" name="sucheFach" value="<?= Html::encode($sucheFach) ?>">
                <label class="form-label">Suche</label>
                <input type="text" name="sucheLehrer" value="<?= Html::encode($sucheLehrer) ?>"
                       class="form-control" placeholder="Lehrer suchen...">
            </form>

            <!-- Create Lehrer Form -->
            <?php if ($showLehrerForm || Yii::$app->request->get('showLehrerForm')): ?>
                <div class="card mb-3" style="border:1px solid #6366f1;">
                    <div class="card-body">
                        <h6>Neuen Lehrer erstellen</h6>
                        <form method="post" action="<?= $urlCreateLehrer ?>">
                            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control" placeholder="Name" required autofocus>
                            </div>
                            <div class="mb-2">
                                <select name="subject_id" class="form-control" required>
                                    <option value="">— Fach wählen —</option>
                                    <?php foreach ($alleFaecher as $f): ?>
                                        <option value="<?= $f['id'] ?>"><?= Html::encode($f['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
                            <a href="<?= $urlIndex ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                               class="btn btn-outline-secondary btn-sm">Abbrechen</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lehrer Tabelle -->
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Lehrer</th>
                    <th>Fach</th>
                    <th>Status</th>
                    <th>Bearbeiten</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($lehrer)): ?>
                    <tr>
                        <td colspan="4" class="text-muted" style="padding:12px;">Keine Lehrer gefunden.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lehrer as $l): ?>
                        <tr>
                            <?php if ((int)$editLehrerId === (int)$l['id']): ?>
                                <td colspan="4">
                                    <form method="post" action="<?= $urlUpdateLehrer ?>"
                                          class="d-flex gap-2 align-items-center flex-wrap">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                        <input type="text" name="name" value="<?= Html::encode($l['name']) ?>"
                                               class="form-control form-control-sm" style="max-width:200px;" required autofocus>
                                        <select name="subject_id" class="form-control form-control-sm" style="max-width:160px;" required>
                                            <option value="">— Fach —</option>
                                            <?php foreach ($alleFaecher as $f): ?>
                                                <option value="<?= $f['id'] ?>"
                                                        <?= (int)$f['id'] === (int)$l['fach_id'] ? 'selected' : '' ?>>
                                                    <?= Html::encode($f['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
                                        <a href="<?= $urlIndex ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                                           class="btn btn-outline-secondary btn-sm">Abbrechen</a>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td><?= Html::encode($l['name']) ?></td>
                                <td><?= Html::encode($l['fach']) ?></td>
                                <td>
                                    <?php if ($l['aktiv']): ?>
                                        <span class="badge-aktiv">✔ Aktiv</span>
                                    <?php else: ?>
                                        <span class="badge-inaktiv">✘ Inaktiv</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-btns">
                                    <form method="post" action="<?= $urlToggleLehrer ?>" style="display:inline">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                                        <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <?= $l['aktiv'] ? 'Inaktivieren' : 'Aktivieren' ?>
                                        </button>
                                    </form>
                                    <a href="<?= $urlIndex ?>&editLehrerId=<?= $l['id'] ?>&sucheFach=<?= urlencode($sucheFach) ?>&sucheLehrer=<?= urlencode($sucheLehrer) ?>"
                                       class="btn-icon btn-edit" title="Bearbeiten">✏️</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
