<?php
// Güvenlik fonksiyonları ve yardımcı fonksiyonlar

// XSS koruması
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Admin girişi kontrol et
function checkAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

// Admin çıkışı
function adminLogout() {
    session_destroy();
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

// URL slug oluştur
function createSlug($text) {
    $turkish = array('ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','ç','Ç');
    $english = array('s','s','i','i','g','g','u','u','o','o','c','c');
    $text = str_replace($turkish, $english, $text);
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

// Dosya yükleme
function uploadFile($file, $folder = '') {
    if (!isset($file) || $file['error'] != 0) {
        return false;
    }

    $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    $upload_path = UPLOAD_DIR . $folder;
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $upload_path . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ($folder ? $folder . '/' : '') . $filename;
    }

    return false;
}

// Dosya silme
function deleteFile($filepath) {
    $fullpath = UPLOAD_DIR . $filepath;
    if (file_exists($fullpath)) {
        return unlink($fullpath);
    }
    return false;
}

// Sayfa başarı mesajı
function setSuccess($message) {
    $_SESSION['success'] = $message;
}

// Sayfa hata mesajı
function setError($message) {
    $_SESSION['error'] = $message;
}

// Başarı mesajını göster ve sil
function getSuccess() {
    if (isset($_SESSION['success'])) {
        $message = $_SESSION['success'];
        unset($_SESSION['success']);
        return $message;
    }
    return null;
}

// Hata mesajını göster ve sil
function getError() {
    if (isset($_SESSION['error'])) {
        $message = $_SESSION['error'];
        unset($_SESSION['error']);
        return $message;
    }
    return null;
}

// Tarih formatla
function formatDate($date) {
    return date('d.m.Y H:i', strtotime($date));
}

// Kısa metin oluştur
function excerpt($text, $length = 150) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

// Site ayarı getir
function getSetting($key, $default = '') {
    global $db;
    try {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch(PDOException $e) {
        return $default;
    }
}

// Site ayarı kaydet
function saveSetting($key, $value) {
    global $db;
    try {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                             ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch(PDOException $e) {
        return false;
    }
}

// Redirect
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
?>
