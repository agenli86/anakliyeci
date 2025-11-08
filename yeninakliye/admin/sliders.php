<?php
$page_title = 'Slider Yönetimi';
include 'includes/header.php';

// Slider ekleme
if (isset($_POST['add_slider'])) {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'sliders');

        if ($image) {
            $stmt = $db->prepare("INSERT INTO sliders (title, subtitle, image, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $subtitle, 'uploads/' . $image, $display_order, $is_active])) {
                setSuccess('Slider başarıyla eklendi.');
            } else {
                setError('Slider eklenirken hata oluştu.');
            }
        } else {
            setError('Resim yüklenirken hata oluştu.');
        }
    } else {
        setError('Lütfen tüm alanları doldurun.');
    }
    redirect('sliders.php');
}

// Slider silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Önce resmi sil
    $stmt = $db->prepare("SELECT image FROM sliders WHERE id = ?");
    $stmt->execute([$id]);
    $slider = $stmt->fetch();

    if ($slider) {
        $image_path = str_replace('uploads/', '', $slider['image']);
        deleteFile($image_path);

        $stmt = $db->prepare("DELETE FROM sliders WHERE id = ?");
        if ($stmt->execute([$id])) {
            setSuccess('Slider başarıyla silindi.');
        } else {
            setError('Slider silinirken hata oluştu.');
        }
    }
    redirect('sliders.php');
}

// Slider durum güncelleme
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE sliders SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Slider durumu güncellendi.');
    }
    redirect('sliders.php');
}

// Tüm sliderları getir
$stmt = $db->query("SELECT * FROM sliders ORDER BY display_order ASC, id DESC");
$sliders = $stmt->fetchAll();
?>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-images"></i> Slider Ekle
    </h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Başlık *</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="subtitle">Alt Başlık</label>
                <input type="text" id="subtitle" name="subtitle">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="image">Slider Resmi *</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <small style="color: #666;">Önerilen boyut: 1920x650px</small>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="0">
            </div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" checked> Aktif
            </label>
        </div>

        <button type="submit" name="add_slider" class="btn btn-primary">
            <i class="fas fa-plus"></i> Slider Ekle
        </button>
    </form>
</div>

<div class="content-box" style="margin-top: 20px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-list"></i> Slider Listesi
    </h2>

    <?php if (count($sliders) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Resim</th>
                    <th>Başlık</th>
                    <th>Alt Başlık</th>
                    <th style="width: 100px;">Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 150px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders as $slider): ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $slider['image']; ?>" alt="<?php echo clean($slider['title']); ?>">
                        </td>
                        <td><?php echo clean($slider['title']); ?></td>
                        <td><?php echo clean($slider['subtitle']); ?></td>
                        <td><?php echo $slider['display_order']; ?></td>
                        <td>
                            <?php if ($slider['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?toggle=<?php echo $slider['id']; ?>" class="btn btn-warning btn-sm" title="Durumu Değiştir">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $slider['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirmDelete();"
                                   title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz slider eklenmemiş.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
