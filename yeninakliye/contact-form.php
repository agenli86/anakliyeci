<?php
require_once 'config/database.php';
require_once 'config/functions.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validasyon
    if (empty($name)) {
        $response['message'] = 'Lütfen adınızı ve soyadınızı girin.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Lütfen geçerli bir e-posta adresi girin.';
    } elseif (empty($message)) {
        $response['message'] = 'Lütfen mesajınızı yazın.';
    } else {
        try {
            // Veritabanına kaydet
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $phone, $message])) {
                $response['success'] = true;
                $response['message'] = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
            } else {
                $response['message'] = 'Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.';
            }
        } catch(PDOException $e) {
            $response['message'] = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        }
    }
} else {
    $response['message'] = 'Geçersiz istek.';
}

// JSON response
if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Normal redirect
if ($response['success']) {
    $_SESSION['contact_success'] = $response['message'];
} else {
    $_SESSION['contact_error'] = $response['message'];
}

header('Location: index.php#contact');
exit;
?>
