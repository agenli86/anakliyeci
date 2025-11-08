<?php
$page_title = 'Hizmet Düzenle';
include 'includes/header.php';

$service = null;
$is_edit = false;

// Düzenleme modu
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();

    if ($service) {
        $is_edit = true;
        $page_title = 'Hizmet Düzenle: ' . $service['title'];
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $short_description = trim($_POST['short_description']);
    $full_content = $_POST['full_content'];
    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && !empty($short_description) && !empty($full_content)) {
        $image = $is_edit ? $service['image'] : '';

        // Resim yükleme
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = uploadFile($_FILES['image'], 'services');
            if ($uploaded_image) {
                // Eski resmi sil
                if ($is_edit && !empty($service['image'])) {
                    $old_image = str_replace('uploads/', '', $service['image']);
                    deleteFile($old_image);
                }
                $image = 'uploads/' . $uploaded_image;
            }
        }

        if ($is_edit) {
            // Güncelleme
            $stmt = $db->prepare("UPDATE services SET title = ?, slug = ?, short_description = ?, full_content = ?, image = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, display_order = ?, is_active = ? WHERE id = ?");
            $result = $stmt->execute([$title, $slug, $short_description, $full_content, $image, $meta_title, $meta_description, $meta_keywords, $display_order, $is_active, $service['id']]);
        } else {
            // Yeni ekleme
            if (empty($image)) {
                setError('Lütfen bir resim seçin.');
                redirect('service-edit.php');
            }

            $stmt = $db->prepare("INSERT INTO services (title, slug, short_description, full_content, image, meta_title, meta_description, meta_keywords, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $slug, $short_description, $full_content, $image, $meta_title, $meta_description, $meta_keywords, $display_order, $is_active]);
        }

        if ($result) {
            setSuccess('Hizmet başarıyla ' . ($is_edit ? 'güncellendi' : 'eklendi') . '.');
            redirect('services.php');
        } else {
            setError('Hizmet kaydedilirken hata oluştu.');
        }
    } else {
        setError('Lütfen tüm zorunlu alanları doldurun.');
    }
}
?>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-<?php echo $is_edit ? 'edit' : 'plus'; ?>"></i>
            <?php echo $is_edit ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle'; ?>
        </h2>
        <a href="services.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Hizmet Başlığı *</label>
                <input type="text" id="title" name="title" value="<?php echo $is_edit ? clean($service['title']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">URL Slug (Boş bırakılırsa otomatik oluşur)</label>
                <input type="text" id="slug" name="slug" value="<?php echo $is_edit ? $service['slug'] : ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="short_description">Kısa Açıklama *</label>
            <textarea id="short_description" name="short_description" rows="3" required><?php echo $is_edit ? clean($service['short_description']) : ''; ?></textarea>
            <small style="color: #666;">Ana sayfada gösterilecek kısa açıklama</small>
        </div>

        <div class="form-group">
            <label for="full_content">Tam İçerik *</label>
            <textarea id="full_content" name="full_content" class="tinymce-editor"><?php echo $is_edit ? $service['full_content'] : ''; ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="image">Hizmet Resmi <?php echo !$is_edit ? '*' : ''; ?></label>
                <input type="file" id="image" name="image" accept="image/*" <?php echo !$is_edit ? 'required' : ''; ?>>
                <?php if ($is_edit && !empty($service['image'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="../<?php echo $service['image']; ?>" alt="Mevcut resim" style="max-width: 200px; border-radius: 5px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="<?php echo $is_edit ? $service['display_order'] : '0'; ?>">
            </div>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <h3 style="color: #2c3e50; margin-bottom: 20px;">
            <i class="fas fa-search"></i> SEO Ayarları
        </h3>

        <div class="form-group">
            <label for="meta_title">Meta Başlık</label>
            <input type="text" id="meta_title" name="meta_title" value="<?php echo $is_edit ? clean($service['meta_title']) : ''; ?>" maxlength="255">
            <small style="color: #666;">Boş bırakılırsa hizmet başlığı kullanılır. Önerilen: 50-60 karakter</small>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Açıklama</label>
            <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo $is_edit ? clean($service['meta_description']) : ''; ?></textarea>
            <small style="color: #666;">Arama motorlarında gösterilecek açıklama. Önerilen: 150-160 karakter</small>
        </div>

        <div class="form-group">
            <label for="meta_keywords">Meta Anahtar Kelimeler</label>
            <input type="text" id="meta_keywords" name="meta_keywords" value="<?php echo $is_edit ? clean($service['meta_keywords']) : ''; ?>">
            <small style="color: #666;">Virgülle ayırarak yazın. Örnek: adana nakliye, evden eve taşımacılık</small>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" <?php echo (!$is_edit || $service['is_active']) ? 'checked' : ''; ?>> Aktif
            </label>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> <?php echo $is_edit ? 'Güncelle' : 'Kaydet'; ?>
        </button>
        <a href="services.php" class="btn btn-danger">
            <i class="fas fa-times"></i> İptal
        </a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
