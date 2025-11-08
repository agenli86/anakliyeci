<?php
$page_title = 'Site Ayarları';
include 'includes/header.php';

// Ayarları kaydet
if (isset($_POST['save_settings'])) {
    $settings_to_save = [
        'site_title', 'site_description', 'site_keywords', 'company_name',
        'phone', 'phone2', 'whatsapp', 'email', 'address',
        'counter_vehicles', 'counter_elevator', 'counter_staff', 'counter_experience',
        'google_analytics'
    ];

    foreach ($settings_to_save as $key) {
        if (isset($_POST[$key])) {
            saveSetting($key, $_POST[$key]);
        }
    }

    setSuccess('Site ayarları başarıyla güncellendi.');
    redirect('settings.php');
}

// Mevcut ayarları getir
$current_settings = [];
$stmt = $db->query("SELECT setting_key, setting_value FROM settings");
while ($row = $stmt->fetch()) {
    $current_settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="content-box">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">
        <i class="fas fa-cog"></i> Site Ayarları
    </h2>

    <form method="POST">
        <h3 style="color: #2c3e50; margin-bottom: 15px; margin-top: 30px;">
            <i class="fas fa-globe"></i> Genel Ayarlar
        </h3>

        <div class="form-group">
            <label for="site_title">Site Başlığı</label>
            <input type="text" id="site_title" name="site_title" value="<?php echo isset($current_settings['site_title']) ? clean($current_settings['site_title']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="site_description">Site Açıklaması</label>
            <textarea id="site_description" name="site_description" rows="3"><?php echo isset($current_settings['site_description']) ? clean($current_settings['site_description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="site_keywords">Site Anahtar Kelimeleri</label>
            <textarea id="site_keywords" name="site_keywords" rows="2"><?php echo isset($current_settings['site_keywords']) ? clean($current_settings['site_keywords']) : ''; ?></textarea>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <h3 style="color: #2c3e50; margin-bottom: 15px;">
            <i class="fas fa-building"></i> Firma Bilgileri
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="company_name">Firma Adı</label>
                <input type="text" id="company_name" name="company_name" value="<?php echo isset($current_settings['company_name']) ? clean($current_settings['company_name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" value="<?php echo isset($current_settings['email']) ? clean($current_settings['email']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Telefon 1</label>
                <input type="text" id="phone" name="phone" value="<?php echo isset($current_settings['phone']) ? clean($current_settings['phone']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="phone2">Telefon 2</label>
                <input type="text" id="phone2" name="phone2" value="<?php echo isset($current_settings['phone2']) ? clean($current_settings['phone2']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="whatsapp">WhatsApp (905xxxxxxxxx formatında)</label>
                <input type="text" id="whatsapp" name="whatsapp" value="<?php echo isset($current_settings['whatsapp']) ? clean($current_settings['whatsapp']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="address">Adres</label>
                <input type="text" id="address" name="address" value="<?php echo isset($current_settings['address']) ? clean($current_settings['address']) : ''; ?>">
            </div>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <h3 style="color: #2c3e50; margin-bottom: 15px;">
            <i class="fas fa-chart-bar"></i> Sayaç Değerleri
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="counter_vehicles">Araç Sayısı</label>
                <input type="number" id="counter_vehicles" name="counter_vehicles" value="<?php echo isset($current_settings['counter_vehicles']) ? $current_settings['counter_vehicles'] : '3'; ?>">
            </div>

            <div class="form-group">
                <label for="counter_elevator">Asansör Sayısı</label>
                <input type="number" id="counter_elevator" name="counter_elevator" value="<?php echo isset($current_settings['counter_elevator']) ? $current_settings['counter_elevator'] : '1'; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="counter_staff">Personel Sayısı</label>
                <input type="number" id="counter_staff" name="counter_staff" value="<?php echo isset($current_settings['counter_staff']) ? $current_settings['counter_staff'] : '10'; ?>">
            </div>

            <div class="form-group">
                <label for="counter_experience">Yıllık Tecrübe</label>
                <input type="number" id="counter_experience" name="counter_experience" value="<?php echo isset($current_settings['counter_experience']) ? $current_settings['counter_experience'] : '20'; ?>">
            </div>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <h3 style="color: #2c3e50; margin-bottom: 15px;">
            <i class="fas fa-code"></i> Entegrasyonlar
        </h3>

        <div class="form-group">
            <label for="google_analytics">Google Analytics ID</label>
            <input type="text" id="google_analytics" name="google_analytics" value="<?php echo isset($current_settings['google_analytics']) ? clean($current_settings['google_analytics']) : ''; ?>">
            <small style="color: #666;">Örnek: AW-17382513923</small>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

        <button type="submit" name="save_settings" class="btn btn-success">
            <i class="fas fa-save"></i> Ayarları Kaydet
        </button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
