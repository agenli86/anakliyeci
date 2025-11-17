<?php
$page_title = 'İletişim Mesajları';
include 'includes/header.php';

// Mesajı okundu olarak işaretle
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Mesaj okundu olarak işaretlendi.');
    }
    redirect('messages.php');
}

// Mesajı sil
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
    if ($stmt->execute([$id])) {
        setSuccess('Mesaj başarıyla silindi.');
    } else {
        setError('Mesaj silinirken hata oluştu.');
    }
    redirect('messages.php');
}

// Tüm mesajları okundu olarak işaretle
if (isset($_GET['mark_all_read'])) {
    $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1");
    if ($stmt->execute()) {
        setSuccess('Tüm mesajlar okundu olarak işaretlendi.');
    }
    redirect('messages.php');
}

// Mesaj detayı
$detail_message = null;
if (isset($_GET['view'])) {
    $id = intval($_GET['view']);
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $detail_message = $stmt->fetch();

    // Otomatik okundu işaretle
    if ($detail_message && !$detail_message['is_read']) {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// İstatistikler
$stmt = $db->query("SELECT COUNT(*) as total FROM contact_messages");
$total_messages = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as unread FROM contact_messages WHERE is_read = 0");
$unread_messages = $stmt->fetch()['unread'];

// Mesajları getir (filtre)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = '';
if ($filter == 'unread') {
    $where = 'WHERE is_read = 0';
} elseif ($filter == 'read') {
    $where = 'WHERE is_read = 1';
}

$stmt = $db->query("SELECT * FROM contact_messages $where ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="dashboard-card card-blue">
        <div class="dashboard-card-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $total_messages; ?></h3>
            <p>Toplam Mesaj</p>
        </div>
    </div>

    <div class="dashboard-card card-red">
        <div class="dashboard-card-icon">
            <i class="fas fa-envelope-open"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo $unread_messages; ?></h3>
            <p>Okunmamış Mesaj</p>
        </div>
    </div>

    <div class="dashboard-card card-green">
        <div class="dashboard-card-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="dashboard-card-content">
            <h3><?php echo ($total_messages - $unread_messages); ?></h3>
            <p>Okunmuş Mesaj</p>
        </div>
    </div>
</div>

<?php if ($detail_message): ?>
<div class="content-box" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-envelope-open"></i> Mesaj Detayı
        </h2>
        <a href="messages.php" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
            <div>
                <strong style="color: #666;">Ad Soyad:</strong><br>
                <span style="font-size: 1.1rem;"><?php echo clean($detail_message['name']); ?></span>
            </div>
            <div>
                <strong style="color: #666;">E-posta:</strong><br>
                <a href="mailto:<?php echo $detail_message['email']; ?>" style="font-size: 1.1rem; color: #D50000;">
                    <?php echo clean($detail_message['email']); ?>
                </a>
            </div>
            <?php if (!empty($detail_message['phone'])): ?>
            <div>
                <strong style="color: #666;">Telefon:</strong><br>
                <a href="tel:<?php echo $detail_message['phone']; ?>" style="font-size: 1.1rem; color: #D50000;">
                    <?php echo clean($detail_message['phone']); ?>
                </a>
            </div>
            <?php endif; ?>
            <div>
                <strong style="color: #666;">Tarih:</strong><br>
                <span style="font-size: 1.1rem;"><?php echo formatDate($detail_message['created_at']); ?></span>
            </div>
        </div>

        <div style="border-top: 2px solid #dee2e6; padding-top: 20px;">
            <strong style="color: #666; display: block; margin-bottom: 10px;">Mesaj:</strong>
            <div style="background: white; padding: 20px; border-radius: 5px; line-height: 1.8;">
                <?php echo nl2br(clean($detail_message['message'])); ?>
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <a href="mailto:<?php echo $detail_message['email']; ?>" class="btn btn-success">
                <i class="fas fa-reply"></i> E-posta ile Yanıtla
            </a>
            <?php if (!empty($detail_message['phone'])): ?>
            <a href="tel:<?php echo $detail_message['phone']; ?>" class="btn btn-primary">
                <i class="fas fa-phone"></i> Telefon ile Ara
            </a>
            <?php endif; ?>
            <a href="?delete=<?php echo $detail_message['id']; ?>" class="btn btn-danger" onclick="return confirmDelete('Bu mesajı silmek istediğinizden emin misiniz?');">
                <i class="fas fa-trash"></i> Sil
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin: 0;">
            <i class="fas fa-inbox"></i> İletişim Mesajları
        </h2>
        <div style="display: flex; gap: 10px;">
            <a href="?filter=all" class="btn btn-sm <?php echo $filter == 'all' ? 'btn-primary' : 'btn-warning'; ?>">
                Tümü (<?php echo $total_messages; ?>)
            </a>
            <a href="?filter=unread" class="btn btn-sm <?php echo $filter == 'unread' ? 'btn-primary' : 'btn-warning'; ?>">
                Okunmamış (<?php echo $unread_messages; ?>)
            </a>
            <a href="?filter=read" class="btn btn-sm <?php echo $filter == 'read' ? 'btn-primary' : 'btn-warning'; ?>">
                Okunmuş (<?php echo ($total_messages - $unread_messages); ?>)
            </a>
            <?php if ($unread_messages > 0): ?>
            <a href="?mark_all_read=1" class="btn btn-success btn-sm" onclick="return confirm('Tüm mesajları okundu olarak işaretlemek istediğinizden emin misiniz?');">
                <i class="fas fa-check-double"></i> Tümünü Okundu Yap
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (count($messages) > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Mesaj</th>
                    <th style="width: 150px;">Tarih</th>
                    <th style="width: 180px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr style="<?php echo !$msg['is_read'] ? 'background: #fff3cd; font-weight: bold;' : ''; ?>">
                        <td style="text-align: center;">
                            <?php if (!$msg['is_read']): ?>
                                <i class="fas fa-envelope" style="color: #D50000;"></i>
                            <?php else: ?>
                                <i class="fas fa-envelope-open" style="color: #999;"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo clean($msg['name']); ?></td>
                        <td><?php echo clean($msg['email']); ?></td>
                        <td><?php echo clean($msg['phone']); ?></td>
                        <td><?php echo excerpt($msg['message'], 60); ?></td>
                        <td><?php echo formatDate($msg['created_at']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="?view=<?php echo $msg['id']; ?>" class="btn btn-primary btn-sm" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (!$msg['is_read']): ?>
                                <a href="?mark_read=<?php echo $msg['id']; ?>" class="btn btn-success btn-sm" title="Okundu İşaretle">
                                    <i class="fas fa-check"></i>
                                </a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Bu mesajı silmek istediğinizden emin misiniz?');" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: #999;">
            <i class="fas fa-inbox" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
            <p style="font-size: 1.2rem;">
                <?php
                if ($filter == 'unread') {
                    echo 'Okunmamış mesaj bulunmuyor.';
                } elseif ($filter == 'read') {
                    echo 'Okunmuş mesaj bulunmuyor.';
                } else {
                    echo 'Henüz mesaj gelmemiş.';
                }
                ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
