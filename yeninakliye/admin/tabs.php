<?php
$page_title = 'Tab İçerikleri';
include 'includes/header.php';

// Tab ekleme
if (isset($_POST['add_tab'])) {
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO tabs (title, content, display_order, is_active) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $content, $display_order, $is_active])) {
            setSuccess('Tab içeriği başarıyla eklendi.');
        } else {
            setError('Tab içeriği eklenirken hata oluştu.');
        }
    } else {
        setError('Lütfen tüm alanları doldurun.');
    }
    redirect('tabs.php');
}

// Tab silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM tabs WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Tab içeriği başarıyla silindi.');
    }
    redirect('tabs.php');
}

// Tab toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE tabs SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Tab durumu güncellendi.');
    }
    redirect('tabs.php');
}

// Tab düzenleme
$edit_tab = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM tabs WHERE id = ?");
    $stmt->execute([$id]);
    $edit_tab = $stmt->fetch();
}

// Tab güncelleme
if (isset($_POST['update_tab'])) {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && !empty($content)) {
        $stmt = $db->prepare("UPDATE tabs SET title = ?, content = ?, display_order = ?, is_active = ? WHERE id = ?");
        if ($stmt->execute([$title, $content, $display_order, $is_active, $id])) {
            setSuccess('Tab içeriği başarıyla güncellendi.');
        }
    }
    redirect('tabs.php');
}

$stmt = $db->query("SELECT * FROM tabs ORDER BY display_order ASC");
$tabs = $stmt->fetchAll();
?>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-<?php echo $edit_tab ? 'edit' : 'plus'; ?>"></i>
        <?php echo $edit_tab ? 'Tab İçeriği Düzenle' : 'Tab İçeriği Ekle'; ?>
    </h2>

    <form method="POST">
        <?php if ($edit_tab): ?>
            <input type="hidden" name="id" value="<?php echo $edit_tab['id']; ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Tab Başlığı *</label>
                <input type="text" id="title" name="title" value="<?php echo $edit_tab ? clean($edit_tab['title']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="<?php echo $edit_tab ? $edit_tab['display_order'] : '0'; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="content">İçerik *</label>
            <textarea id="content" name="content" class="tinymce-editor"><?php echo $edit_tab ? $edit_tab['content'] : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" <?php echo (!$edit_tab || $edit_tab['is_active']) ? 'checked' : ''; ?>> Aktif
            </label>
        </div>

        <button type="submit" name="<?php echo $edit_tab ? 'update_tab' : 'add_tab'; ?>" class="btn btn-success">
            <i class="fas fa-save"></i> <?php echo $edit_tab ? 'Güncelle' : 'Ekle'; ?>
        </button>
        <?php if ($edit_tab): ?>
            <a href="tabs.php" class="btn btn-danger">
                <i class="fas fa-times"></i> İptal
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="content-box" style="margin-top: 20px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-list"></i> Tab Listesi
    </h2>

    <?php if (count($tabs) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabs as $tab): ?>
                    <tr>
                        <td><strong><?php echo clean($tab['title']); ?></strong></td>
                        <td><?php echo $tab['display_order']; ?></td>
                        <td>
                            <?php if ($tab['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $tab['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?toggle=<?php echo $tab['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $tab['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz tab içeriği eklenmemiş.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
