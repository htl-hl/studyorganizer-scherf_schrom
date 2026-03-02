<?php

/** @var yii\web\View $this */
/** @var array $faecher */
/** @var array $lehrer */
/** @var string $sucheFach */
/** @var string $sucheLehrer */

use yii\bootstrap5\Html;

$this->title = 'Admin';
?>

    <div class="admin-page">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <button class="btn btn-primary btn-sm">+ Create Fächer</button>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Admin</span>
                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('Logout', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>

            <div class="card-body">

                <form method="get" action="" class="mb-3">
                    <input type="hidden" name="sucheLehrer" value="<?= Html::encode($sucheLehrer) ?>">
                    <label class="form-label">Suche</label>
                    <input type="text"
                           name="sucheFach"
                           value="<?= Html::encode($sucheFach) ?>"
                           class="form-control"
                           placeholder="Fach suchen...">
                </form>

                <h5 class="mb-3">Fäch</h5>

                <table class="admin-table">
                    <?php if (empty($faecher)): ?>
                        <tr>
                            <td colspan="2" class="text-muted" style="padding: 12px;">Keine Fächer gefunden.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($faecher as $fach): ?>
                            <tr>
                                <td><?= Html::encode($fach['name']) ?></td>
                                <td class="action-btns">
                                    <button class="btn-icon btn-edit" title="Bearbeiten">✏️</button>
                                    <button class="btn-icon btn-delete" title="Löschen">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <button class="btn btn-primary btn-sm">+ Create Lehrer</button>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Admin</span>
                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('Logout', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>

            <div class="card-body">
                <!-- Suche Lehrer -->
                <form method="get" action="" class="mb-3">
                    <input type="hidden" name="sucheFach" value="<?= Html::encode($sucheFach) ?>">
                    <label class="form-label">Suche</label>
                    <input type="text"
                           name="sucheLehrer"
                           value="<?= Html::encode($sucheLehrer) ?>"
                           class="form-control"
                           placeholder="Lehrer suchen...">
                </form>

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
                            <td colspan="4" class="text-muted" style="padding: 12px;">Keine Lehrer gefunden.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lehrer as $l): ?>
                            <tr>
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
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <?= $l['aktiv'] ? 'Inaktivieren' : 'Aktivieren' ?>
                                    </button>
                                    <button class="btn-icon btn-edit" title="Bearbeiten">✏️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<?php

$css = <<<CSS
.admin-page {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 16px;
}

.card-header {
    background-color: #1a1e28;
    border-bottom: 1px solid #252a37;
    padding: 14px 20px;
    border-radius: 16px 16px 0 0;
}

.card-body {
    padding: 20px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    text-align: left;
    font-size: 13px;
    color: #9ca3af;
    padding: 8px 12px;
    border-bottom: 1px solid #252a37;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #252a37;
    font-size: 14px;
    vertical-align: middle;
}

.action-btns {
    text-align: right;
    white-space: nowrap;
}

.btn-icon {
    background: none;
    border: 1px solid #252a37;
    border-radius: 6px;
    padding: 5px 8px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 4px;
    transition: background 0.2s;
}

.btn-icon:hover {
    background-color: #252a37;
}

.badge-aktiv {
    color: #34d399;
    font-size: 13px;
    font-weight: 500;
}

.badge-inaktiv {
    color: #f87171;
    font-size: 13px;
    font-weight: 500;
}
CSS;
$this->registerCss($css);
?>