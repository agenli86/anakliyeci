<?php
$page_title = 'Dashboard';
include 'includes/header.php';

// İstatistikler
$stats = [];

// Toplam slider sayısı
$stmt = $db->query("SELECT COUNT(*) as total FROM sliders WHERE is_active = 1");
$stats['sliders'] = $stmt->fetch()['total'];

// Toplam hizmet sayısı
$stmt = $db->query("SELECT COUNT(*) as total FROM services WHERE is_active = 1");
$stats['services'] = $stmt->fetch()['total'];

// Toplam blog sayısı
$stmt = $db->query("SELECT COUNT(*) as total FROM blog_posts WHERE is_active = 1");
$stats['blogs'] = $stmt->fetch()['total'];

// Toplam galeri sayısı
$stmt = $db->query("SELECT COUNT(*) as total FROM gallery WHERE is_active = 1");
$stats['gallery'] = $stmt->fetch()['total'];

// Mesaj istatistikleri
$stmt = $db->query("SELECT COUNT(*) as unread FROM contact_messages WHERE is_read = 0");
$stats['unread_messages'] = $stmt->fetch()['unread'];

// Son eklenen hizmetler
$stmt = $db->query("SELECT * FROM services ORDER BY created_at DESC LIMIT 5");
$recent_services = $stmt->fetchAll();

// Son eklenen blog yazıları
$stmt = $db->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5");
$recent_blogs = $stmt->fetchAll();
?>

<div class="dashboard-cards">
    <div class="dashboard-card card-red">
        <div class="dashboard-card-icon">
            <i class="fas fa-images"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $stats['sliders']; ?></h3>
            <p>Aktif Slider</p>
        </div>
    </div>

    <div class="dashboard-card card-blue">
        <div class="dashboard-card-icon">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $stats['services']; ?></h3>
            <p>Aktif Hizmet</p>
        </div>
    </div>

    <div class="dashboard-card card-green">
        <div class="dashboard-card-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $stats['blogs']; ?></h3>
            <p>Blog Yazısı</p>
        </div>
    </div>

    <div class="dashboard-card card-yellow">
        <div class="dashboard-card-icon">
            <i class="fas fa-image"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $stats['gallery']; ?></h3>
            <p>Galeri Resmi</p>
        </div>
    </div>

    <div class="dashboard-card <?php echo $stats['unread_messages'] > 0 ? 'card-red' : 'card-green'; ?>">
        <div class="dashboard-card-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $stats['unread_messages']; ?></h3>
            <p>Okunmamış Mesaj</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
    <div class="content-box">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">
            <i class="fas fa-cogs"></i> Son Eklenen Hizmetler
        </h2>

        <?php if (count($recent_services) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Başlık</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_services as $service): ?>
                        <tr>
                            <td><?php echo clean($service['title']); ?></td>
                            <td><?php echo formatDate($service['created_at']); ?></td>
                            <td>
                                <span class="badge badge-success">Aktif</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Henüz hizmet eklenmemiş.</p>
        <?php endif; ?>

        <div style="margin-top: 15px; text-align: right;">
            <a href="services.php" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-right"></i> Tümünü Gör
            </a>
        </div>
    </div>

    <div class="content-box">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">
            <i class="fas fa-map-marker-alt"></i> Son Eklenen Blog Yazıları
        </h2>

        <?php if (count($recent_blogs) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Başlık</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_blogs as $blog): ?>
                        <tr>
                            <td><?php echo clean($blog['title']); ?></td>
                            <td><?php echo formatDate($blog['created_at']); ?></td>
                            <td>
                                <span class="badge badge-success">Aktif</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Henüz blog yazısı eklenmemiş.</p>
        <?php endif; ?>

        <div style="margin-top: 15px; text-align: right;">
            <a href="blog.php" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-right"></i> Tümünü Gör
            </a>
        </div>
    </div>
</div>

<div class="content-box" style="margin-top: 20px;">
    <h2 style="margin-bottom: 15px; color: #2c3e50;">
        <i class="fas fa-info-circle"></i> Hızlı Erişim
    </h2>
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
        <a href="sliders.php" class="btn btn-primary" style="padding: 20px; text-align: center;">
            <i class="fas fa-images" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
            Slider Ekle
        </a>
        <a href="service-edit.php" class="btn btn-success" style="padding: 20px; text-align: center;">
            <i class="fas fa-plus-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
            Hizmet Ekle
        </a>
        <a href="blog-edit.php" class="btn btn-warning" style="padding: 20px; text-align: center; color: white;">
            <i class="fas fa-edit" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
            Blog Ekle
        </a>
        <a href="messages.php" class="btn btn-primary" style="padding: 20px; text-align: center; background: <?php echo $stats['unread_messages'] > 0 ? '#dc3545' : '#17a2b8'; ?>;">
            <i class="fas fa-envelope" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
            Mesajlar<?php if ($stats['unread_messages'] > 0): ?><br><small>(<?php echo $stats['unread_messages']; ?> yeni)</small><?php endif; ?>
        </a>
        <a href="settings.php" class="btn btn-primary" style="padding: 20px; text-align: center; background: #6c757d;">
            <i class="fas fa-cog" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
            Ayarlar
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
