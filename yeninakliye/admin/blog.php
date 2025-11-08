<?php
$page_title = 'Blog / Bölgeler';
include 'includes/header.php';

// Blog silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Önce resmi sil
    $stmt = $db->prepare("SELECT image FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch();

    if ($blog) {
        $image_path = str_replace('uploads/', '', $blog['image']);
        deleteFile($image_path);

        $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
        if ($stmt->execute([$id])) {
            setSuccess('Blog yazısı başarıyla silindi.');
        } else {
            setError('Blog yazısı silinirken hata oluştu.');
        }
    }
    redirect('blog.php');
}

// Blog durum güncelleme
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE blog_posts SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Blog durumu güncellendi.');
    }
    redirect('blog.php');
}

// Tüm blog yazılarını getir
$stmt = $db->query("SELECT * FROM blog_posts ORDER BY display_order ASC, id DESC");
$blogs = $stmt->fetchAll();
?>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-map-marker-alt"></i> Blog / Bölgeler
        </h2>
        <a href="blog-edit.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Yeni Blog Ekle
        </a>
    </div>

    <?php if (count($blogs) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Resim</th>
                    <th>Başlık</th>
                    <th>Slug</th>
                    <th>Özet</th>
                    <th style="width: 100px;">Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $blog['image']; ?>" alt="<?php echo clean($blog['title']); ?>">
                        </td>
                        <td><strong><?php echo clean($blog['title']); ?></strong></td>
                        <td><code><?php echo $blog['slug']; ?></code></td>
                        <td><?php echo excerpt($blog['excerpt'], 80); ?></td>
                        <td><?php echo $blog['display_order']; ?></td>
                        <td>
                            <?php if ($blog['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="blog-edit.php?id=<?php echo $blog['id']; ?>" class="btn btn-primary btn-sm" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?toggle=<?php echo $blog['id']; ?>" class="btn btn-warning btn-sm" title="Durumu Değiştir">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $blog['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirmDelete('Bu blog yazısını silmek istediğinizden emin misiniz?');"
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
        <p>Henüz blog yazısı eklenmemiş. <a href="blog-edit.php">İlk blog yazısını ekleyin</a>.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
