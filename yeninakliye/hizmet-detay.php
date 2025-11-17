<?php
require_once 'config/database.php';
require_once 'config/functions.php';

// Slug parametresini al
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header('Location: index.php');
    exit;
}

// Hizmeti getir
$stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    header('Location: index.php');
    exit;
}

// Meta bilgileri
$meta_title = !empty($service['meta_title']) ? $service['meta_title'] : $service['title'];
$meta_description = !empty($service['meta_description']) ? $service['meta_description'] : $service['short_description'];
$meta_keywords = !empty($service['meta_keywords']) ? $service['meta_keywords'] : '';

// Site ayarları
$company_name = getSetting('company_name', 'Adana Nakliye');
$phone = getSetting('phone');
$phone2 = getSetting('phone2');
$whatsapp = getSetting('whatsapp');
$email = getSetting('email');
$address = getSetting('address');
$google_analytics = getSetting('google_analytics');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean($meta_title); ?></title>
    <meta name="description" content="<?php echo clean($meta_description); ?>">
    <?php if (!empty($meta_keywords)): ?>
    <meta name="keywords" content="<?php echo clean($meta_keywords); ?>">
    <?php endif; ?>
    <meta name="author" content="<?php echo clean($company_name); ?>">

    <meta property="og:title" content="<?php echo clean($meta_title); ?>">
    <meta property="og:description" content="<?php echo clean($meta_description); ?>">
    <meta property="og:image" content="<?php echo SITE_URL . '/' . $service['image']; ?>">
    <meta property="og:type" content="article">

    <link rel="icon" type="image/png" sizes="32x32" href="../resimler/favicon.png">
    <link rel="shortcut icon" href="../resimler/favicon.png" type="image/png">

<?php if (!empty($google_analytics)): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_analytics; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $google_analytics; ?>');
</script>
<?php endif; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --primary-color: #D50000;
            --secondary-color: #B71C1C;
            --accent-color: #FFC107;
            --text-color: #333;
            --light-color: #f9f9f9;
            --white-color: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; line-height: 1.6; color: var(--text-color); background-color: var(--white-color); }
        .container { max-width: 1200px; margin: auto; padding: 0 20px; }
        .sticky-icons-bottom-left { position: fixed; bottom: 20px; left: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 12px; }
        .sticky-icons-bottom-left a { width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white-color); font-size: 28px; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: transform 0.3s ease; }
        .sticky-icons-bottom-left a:hover { transform: scale(1.1); }
        .whatsapp-icon { background-color: #25D366; }
        .phone-icon { background-color: var(--secondary-color); }
        .navbar { background: var(--white-color); padding: 1rem 0; position: fixed; width: 100%; top: 0; z-index: 999; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar .container { display: flex; justify-content: space-between; align-items: center; }
        .navbar .logo { font-size: 1.8rem; font-weight: bold; color: var(--primary-color); text-decoration: none; }
        .navbar .nav-links { list-style: none; display: flex; align-items: center; }
        .navbar .nav-links li { margin-left: 20px; }
        .navbar .nav-links a { color: var(--primary-color); text-decoration: none; font-weight: bold; padding: 5px 10px; border-radius: 5px; transition: background-color 0.3s, color 0.3s; }
        .navbar .nav-links a:hover { background-color: var(--primary-color); color: var(--white-color); }
        .hamburger { display: none; cursor: pointer; font-size: 24px; color: var(--primary-color); }
        .page-header { padding-top: 100px; padding-bottom: 30px; background: var(--light-color); text-align: center; }
        .page-header h1 { color: var(--primary-color); font-size: 2.5rem; margin-bottom: 15px; }
        .breadcrumb { display: flex; justify-content: center; gap: 10px; font-size: 14px; color: #666; }
        .breadcrumb a { color: var(--primary-color); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .content-section { padding: 60px 0; }
        .service-image { width: 100%; max-width: 100%; height: auto; border-radius: 8px; margin: 30px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .service-content { max-width: 900px; margin: 0 auto; line-height: 1.8; font-size: 1.1rem; }
        .service-content h1, .service-content h2, .service-content h3 { color: var(--primary-color); margin-top: 30px; margin-bottom: 15px; }
        .service-content p { margin-bottom: 20px; }
        .service-content ul, .service-content ol { margin-left: 20px; margin-bottom: 20px; }
        .cta-box { background: var(--primary-color); color: white; padding: 40px; border-radius: 10px; text-align: center; margin: 40px 0; }
        .cta-box h2 { color: white; margin-bottom: 15px; }
        .cta-box a { display: inline-block; padding: 15px 30px; background: var(--accent-color); color: var(--primary-color); text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1.1rem; transition: background 0.3s; }
        .cta-box a:hover { background: #ffb300; }
        .footer { background: var(--primary-color); color: var(--white-color); padding: 40px 0; text-align: center; margin-top: 60px; }
        .footer-contact p { margin-bottom: 10px; }
        .footer-contact a { color: var(--accent-color); text-decoration: none; }
        @media(max-width: 768px) {
            .page-header h1 { font-size: 2rem; }
            .navbar .nav-links { display: none; flex-direction: column; width: 100%; position: absolute; top: 70px; left: 0; background: var(--white-color); }
            .navbar .nav-links.active { display: flex; }
            .navbar .nav-links li { margin: 0; width: 100%; text-align: center; }
            .navbar .nav-links a { padding: 15px; display: block; border-radius: 0; border-bottom: 1px solid #eee; }
            .hamburger { display: block; }
        }
    </style>
</head>
<body>
    <div class="sticky-icons-bottom-left">
        <?php if (!empty($whatsapp)): ?>
        <a href="https://wa.me/<?php echo $whatsapp; ?>?text=Merhaba,%20<?php echo urlencode($service['title']); ?>%20hakkında%20bilgi%20almak%20istiyorum." target="_blank" class="whatsapp-icon" aria-label="WhatsApp ile İletişime Geç"><i class="fab fa-whatsapp"></i></a>
        <?php endif; ?>
        <?php if (!empty($phone)): ?>
        <a href="tel:<?php echo $phone; ?>" class="phone-icon" aria-label="Telefon ile Ara"><i class="fas fa-phone"></i></a>
        <?php endif; ?>
    </div>

    <header class="navbar">
        <div class="container">
            <a href="index.php" class="logo"><?php echo clean($company_name); ?></a>
            <nav>
                <div class="hamburger"><i class="fas fa-bars"></i></div>
                <ul class="nav-links">
                    <li><a href="index.php">Ana Sayfa</a></li>
                    <li><a href="index.php#articles">Hizmetlerimiz</a></li>
                    <li><a href="index.php#gallery">Galeri</a></li>
                    <li><a href="index.php#contact">İletişim</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <h1><?php echo clean($service['title']); ?></h1>
            <div class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Ana Sayfa</a>
                <span>/</span>
                <span><?php echo clean($service['title']); ?></span>
            </div>
        </div>
    </div>

    <section class="content-section">
        <div class="container">
            <div class="service-content">
                <img src="<?php echo $service['image']; ?>" alt="<?php echo clean($service['title']); ?>" class="service-image">

                <?php echo $service['full_content']; ?>

                <div class="cta-box">
                    <h2><i class="fas fa-phone"></i> Ücretsiz Teklif Alın!</h2>
                    <p>Profesyonel nakliye hizmetlerimiz hakkında detaylı bilgi ve fiyat teklifi için hemen bizi arayın.</p>
                    <?php if (!empty($phone)): ?>
                    <a href="tel:<?php echo $phone; ?>"><i class="fas fa-phone-alt"></i> <?php echo $phone; ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-contact">
                <h3><?php echo clean($company_name); ?></h3>
                <?php if (!empty($address)): ?><p>Adres: <?php echo clean($address); ?></p><?php endif; ?>
                <?php if (!empty($phone2)): ?><p>Telefon: <a href="tel:<?php echo $phone2; ?>"><?php echo $phone2; ?></a></p><?php endif; ?>
                <?php if (!empty($email)): ?><p>E-posta: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p><?php endif; ?>
            </div>
            <div style="margin-top: 20px; border-top: 1px solid var(--secondary-color); padding-top: 20px;">
                <p>&copy; <?php echo date('Y'); ?> <?php echo clean($company_name); ?>. Tüm Hakları Saklıdır.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        if (hamburger) {
            hamburger.addEventListener('click', (e) => {
                e.stopPropagation();
                navLinks.classList.toggle('active');
            });
        }
    });
    </script>
</body>
</html>
