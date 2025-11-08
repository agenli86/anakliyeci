<?php
$page_title = 'Hizmetler';
include 'includes/header.php';

// Hizmet silme
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Önce resmi sil
    $stmt = $db->prepare("SELECT image FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();

    if ($service) {
        $image_path = str_replace('uploads/', '', $service['image']);
        deleteFile($image_path);

        $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
        if ($stmt->execute([$id])) {
            setSuccess('Hizmet başarıyla silindi.');
        } else {
            setError('Hizmet silinirken hata oluştu.');
        }
    }
    redirect('services.php');
}

// Hizmet durum güncelleme
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $db->prepare("UPDATE services SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Hizmet durumu güncellendi.');
    }
    redirect('services.php');
}

// Tüm hizmetleri getir
$stmt = $db->query("SELECT * FROM services ORDER BY display_order ASC, id DESC");
$services = $stmt->fetchAll();
?>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-cogs"></i> Hizmetler
        </h2>
        <a href="service-edit.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Yeni Hizmet Ekle
        </a>
    </div>

    <?php if (count($services) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Resim</th>
                    <th>Başlık</th>
                    <th>Slug</th>
                    <th>Kısa Açıklama</th>
                    <th style="width: 100px;">Sıra</th>
                    <th style="width: 100px;">Durum</th>
                    <th style="width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $service['image']; ?>" alt="<?php echo clean($service['title']); ?>">
                        </td>
                        <td><strong><?php echo clean($service['title']); ?></strong></td>
                        <td><code><?php echo $service['slug']; ?></code></td>
                        <td><?php echo excerpt($service['short_description'], 80); ?></td>
                        <td><?php echo $service['display_order']; ?></td>
                        <td>
                            <?php if ($service['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Pasif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="service-edit.php?id=<?php echo $service['id']; ?>" class="btn btn-primary btn-sm" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?toggle=<?php echo $service['id']; ?>" class="btn btn-warning btn-sm" title="Durumu Değiştir">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                                <a href="?delete=<?php echo $service['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirmDelete('Bu hizmeti silmek istediğinizden emin misiniz?');"
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
        <p>Henüz hizmet eklenmemiş. <a href="service-edit.php">İlk hizmeti ekleyin</a>.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
