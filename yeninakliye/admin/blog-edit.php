<?php
$page_title = 'Blog Düzenle';
include 'includes/header.php';

$blog = null;
$is_edit = false;

// Düzenleme modu
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch();

    if ($blog) {
        $is_edit = true;
        $page_title = 'Blog Düzenle: ' . $blog['title'];
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $slug = !empty($_POST['slug']) ? createSlug($_POST['slug']) : createSlug($title);
    $excerpt = trim($_POST['excerpt']);
    $content = $_POST['content'];
    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $display_order = intval($_POST['display_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($title) && !empty($excerpt) && !empty($content)) {
        $image = $is_edit ? $blog['image'] : '';

        // Resim yükleme
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = uploadFile($_FILES['image'], 'blog');
            if ($uploaded_image) {
                // Eski resmi sil
                if ($is_edit && !empty($blog['image'])) {
                    $old_image = str_replace('uploads/', '', $blog['image']);
                    deleteFile($old_image);
                }
                $image = 'uploads/' . $uploaded_image;
            }
        }

        if ($is_edit) {
            // Güncelleme
            $stmt = $db->prepare("UPDATE blog_posts SET title = ?, slug = ?, excerpt = ?, content = ?, image = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, display_order = ?, is_active = ? WHERE id = ?");
            $result = $stmt->execute([$title, $slug, $excerpt, $content, $image, $meta_title, $meta_description, $meta_keywords, $display_order, $is_active, $blog['id']]);
        } else {
            // Yeni ekleme
            if (empty($image)) {
                setError('Lütfen bir resim seçin.');
                redirect('blog-edit.php');
            }

            $stmt = $db->prepare("INSERT INTO blog_posts (title, slug, excerpt, content, image, meta_title, meta_description, meta_keywords, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $slug, $excerpt, $content, $image, $meta_title, $meta_description, $meta_keywords, $display_order, $is_active]);
        }

        if ($result) {
            setSuccess('Blog yazısı başarıyla ' . ($is_edit ? 'güncellendi' : 'eklendi') . '.');
            redirect('blog.php');
        } else {
            setError('Blog yazısı kaydedilirken hata oluştu.');
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
            <?php echo $is_edit ? 'Blog Düzenle' : 'Yeni Blog Ekle'; ?>
        </h2>
        <a href="blog.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Blog Başlığı *</label>
                <input type="text" id="title" name="title" value="<?php echo $is_edit ? clean($blog['title']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">URL Slug (Boş bırakılırsa otomatik oluşur)</label>
                <input type="text" id="slug" name="slug" value="<?php echo $is_edit ? $blog['slug'] : ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Özet *</label>
            <textarea id="excerpt" name="excerpt" rows="3" required><?php echo $is_edit ? clean($blog['excerpt']) : ''; ?></textarea>
            <small style="color: #666;">Ana sayfada gösterilecek kısa özet</small>
        </div>

        <div class="form-group">
            <label for="content">İçerik *</label>
            <textarea id="content" name="content" class="tinymce-editor"><?php echo $is_edit ? $blog['content'] : ''; ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="image">Blog Resmi <?php echo !$is_edit ? '*' : ''; ?></label>
                <input type="file" id="image" name="image" accept="image/*" <?php echo !$is_edit ? 'required' : ''; ?>>
                <?php if ($is_edit && !empty($blog['image'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="../<?php echo $blog['image']; ?>" alt="Mevcut resim" style="max-width: 200px; border-radius: 5px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" value="<?php echo $is_edit ? $blog['display_order'] : '0'; ?>">
            </div>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <h3 style="color: #2c3e50; margin-bottom: 20px;">
            <i class="fas fa-search"></i> SEO Ayarları
        </h3>

        <div class="form-group">
            <label for="meta_title">Meta Başlık</label>
            <input type="text" id="meta_title" name="meta_title" value="<?php echo $is_edit ? clean($blog['meta_title']) : ''; ?>" maxlength="255">
            <small style="color: #666;">Boş bırakılırsa blog başlığı kullanılır. Önerilen: 50-60 karakter</small>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Açıklama</label>
            <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo $is_edit ? clean($blog['meta_description']) : ''; ?></textarea>
            <small style="color: #666;">Arama motorlarında gösterilecek açıklama. Önerilen: 150-160 karakter</small>
        </div>

        <div class="form-group">
            <label for="meta_keywords">Meta Anahtar Kelimeler</label>
            <input type="text" id="meta_keywords" name="meta_keywords" value="<?php echo $is_edit ? clean($blog['meta_keywords']) : ''; ?>">
            <small style="color: #666;">Virgülle ayırarak yazın. Örnek: kozan nakliyat, karaisalı nakliye</small>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" <?php echo (!$is_edit || $blog['is_active']) ? 'checked' : ''; ?>> Aktif
            </label>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> <?php echo $is_edit ? 'Güncelle' : 'Kaydet'; ?>
        </button>
        <a href="blog.php" class="btn btn-danger">
            <i class="fas fa-times"></i> İptal
        </a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
