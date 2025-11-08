<?php
$page_title = 'Sıkça Sorulan Sorular';
include 'includes/header.php';

// SSS ekleme
if (isset($_POST['add_sss'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($question) && !empty($answer)) {
        $stmt = $db->prepare("INSERT INTO sss (question, answer, display_order, is_active) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$question, $answer, $display_order, $is_active])) {
            setSuccess('SSS başarıyla eklendi.');
        } else {
            setError('SSS eklenirken hata oluştu.');
        }
    } else {
        setError('Lütfen tüm alanları doldurun.');
    }
    redirect('sss.php');
}

// SSS silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM sss WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('SSS başarıyla silindi.');
    }
    redirect('sss.php');
}

// SSS toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE sss SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('SSS durumu güncellendi.');
    }
    redirect('sss.php');
}

// SSS düzenleme
$edit_sss = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM sss WHERE id = ?");
    $stmt->execute([$id]);
    $edit_sss = $stmt->fetch();
}

// SSS güncelleme
if (isset($_POST['update_sss'])) {
    $id = intval($_POST['id']);
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($question) && !empty($answer)) {
        $stmt = $db->prepare("UPDATE sss SET question = ?, answer = ?, display_order = ?, is_active = ? WHERE id = ?");
        if ($stmt->execute([$question, $answer, $display_order, $is_active, $id])) {
            setSuccess('SSS başarıyla güncellendi.');
        }
    }
    redirect('sss.php');
}

$stmt = $db->query("SELECT * FROM sss ORDER BY display_order ASC");
$sss_list = $stmt->fetchAll();
?>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-<?php echo $edit_sss ? 'edit' : 'plus'; ?>"></i>
        <?php echo $edit_sss ? 'SSS Düzenle' : 'SSS Ekle'; ?>
    </h2>

    <form method="POST">
        <?php if ($edit_sss): ?>
            <input type="hidden" name="id" value="<?php echo $edit_sss['id']; ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="question">Soru *</label>
                <input type="text" id="question" name="question" value="<?php echo $edit_sss ? clean($edit_sss['question']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="<?php echo $edit_sss ? $edit_sss['display_order'] : '0'; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="answer">Cevap *</label>
            <textarea id="answer" name="answer" rows="4" required><?php echo $edit_sss ? clean($edit_sss['answer']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" <?php echo (!$edit_sss || $edit_sss['is_active']) ? 'checked' : ''; ?>> Aktif
            </label>
        </div>

        <button type="submit" name="<?php echo $edit_sss ? 'update_sss' : 'add_sss'; ?>" class="btn btn-success">
            <i class="fas fa-save"></i> <?php echo $edit_sss ? 'Güncelle' : 'Ekle'; ?>
        </button>
        <?php if ($edit_sss): ?>
            <a href="sss.php" class="btn btn-danger">
                <i class="fas fa-times"></i> İptal
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="content-box" style="margin-top: 20px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-list"></i> SSS Listesi
    </h2>

    <?php if (count($sss_list) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Soru</th>
                    <th>Cevap</th>
                    <th style="width: 80px;">Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sss_list as $sss): ?>
                    <tr>
                        <td><strong><?php echo clean($sss['question']); ?></strong></td>
                        <td><?php echo excerpt($sss['answer'], 100); ?></td>
                        <td><?php echo $sss['display_order']; ?></td>
                        <td>
                            <?php if ($sss['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $sss['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?toggle=<?php echo $sss['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $sss['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz SSS eklenmemiş.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
