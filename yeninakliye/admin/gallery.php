<?php
$page_title = 'Galeri Yönetimi';
include 'includes/header.php';

// Galeri ekleme
if (isset($_POST['add_gallery'])) {
    $title = trim($_POST['title']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'gallery');

        if ($image) {
            $stmt = $db->prepare("INSERT INTO gallery (title, image, display_order, is_active) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$title, 'uploads/' . $image, $display_order, $is_active])) {
                setSuccess('Galeri resmi başarıyla eklendi.');
            } else {
                setError('Galeri resmi eklenirken hata oluştu.');
            }
        } else {
            setError('Resim yüklenirken hata oluştu.');
        }
    } else {
        setError('Lütfen tüm alanları doldurun.');
    }
    redirect('gallery.php');
}

// Silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("SELECT image FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if ($item) {
        $image_path = str_replace('uploads/', '', $item['image']);
        deleteFile($image_path);
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        if ($stmt->execute([$id])) {
            setSuccess('Galeri resmi başarıyla silindi.');
        }
    }
    redirect('gallery.php');
}

// Toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE gallery SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Galeri durumu güncellendi.');
    }
    redirect('gallery.php');
}

$stmt = $db->query("SELECT * FROM gallery ORDER BY display_order ASC, id DESC");
$gallery_items = $stmt->fetchAll();
?>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-image"></i> Galeri Resmi Ekle
    </h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Başlık *</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="0">
            </div>
        </div>

        <div class="form-group">
            <label for="image">Resim *</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" checked> Aktif
            </label>
        </div>

        <button type="submit" name="add_gallery" class="btn btn-primary">
            <i class="fas fa-plus"></i> Resim Ekle
        </button>
    </form>
</div>

<div class="content-box" style="margin-top: 20px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-list"></i> Galeri Resimleri
    </h2>

    <?php if (count($gallery_items) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Resim</th>
                    <th>Başlık</th>
                    <th style="width: 100px;">Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 150px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gallery_items as $item): ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $item['image']; ?>" alt="<?php echo clean($item['title']); ?>">
                        </td>
                        <td><?php echo clean($item['title']); ?></td>
                        <td><?php echo $item['display_order']; ?></td>
                        <td>
                            <?php if ($item['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?toggle=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz galeri resmi eklenmemiş.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
