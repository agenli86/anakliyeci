<?php
require_once 'config/database.php';
require_once 'config/functions.php';

// Site ayarlarını al
$site_title = getSetting('site_title', 'Adana Nakliye');
$site_description = getSetting('site_description');
$site_keywords = getSetting('site_keywords');
$company_name = getSetting('company_name', 'Adana Nakliye');
$phone = getSetting('phone');
$phone2 = getSetting('phone2');
$whatsapp = getSetting('whatsapp');
$email = getSetting('email');
$address = getSetting('address');
$google_analytics = getSetting('google_analytics');

// Slider'ları al
$stmt = $db->query("SELECT * FROM sliders WHERE is_active = 1 ORDER BY display_order ASC LIMIT 5");
$sliders = $stmt->fetchAll();

// Hizmetleri al
$stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC LIMIT 3");
$services = $stmt->fetchAll();

// Blog yazılarını al
$stmt = $db->query("SELECT * FROM blog_posts WHERE is_active = 1 ORDER BY display_order ASC LIMIT 3");
$blogs = $stmt->fetchAll();

// Tab içeriklerini al
$stmt = $db->query("SELECT * FROM tabs WHERE is_active = 1 ORDER BY display_order ASC");
$tabs = $stmt->fetchAll();

// Galeri resimlerini al
$stmt = $db->query("SELECT * FROM gallery WHERE is_active = 1 ORDER BY display_order ASC LIMIT 6");
$gallery = $stmt->fetchAll();

// SSS'leri al
$stmt = $db->query("SELECT * FROM sss WHERE is_active = 1 ORDER BY display_order ASC");
$sss_list = $stmt->fetchAll();

// Sayaç değerleri
$counter_vehicles = getSetting('counter_vehicles', '3');
$counter_elevator = getSetting('counter_elevator', '1');
$counter_staff = getSetting('counter_staff', '10');
$counter_experience = getSetting('counter_experience', '20');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean($site_title); ?></title>
    <meta name="description" content="<?php echo clean($site_description); ?>">
    <meta name="keywords" content="<?php echo clean($site_keywords); ?>">
    <meta name="author" content="<?php echo clean($company_name); ?>">

    <meta property="og:title" content="<?php echo clean($site_title); ?>">
    <meta property="og:description" content="<?php echo clean($site_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo clean($company_name); ?>">

    <link rel="icon" type="image/png" sizes="32x32" href="resimler/favicon.png">
    <link rel="shortcut icon" href="resimler/favicon.png" type="image/png">

<?php if (!empty($google_analytics)): ?>
<!-- Google tag (gtag.js) -->
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
        html { scroll-behavior: smooth; }
        body { font-family: 'Roboto', sans-serif; line-height: 1.6; color: var(--text-color); background-color: var(--white-color); }
        .container { max-width: 1200px; margin: auto; padding: 0 20px; }
        section { padding: 60px 0; }
        h1, h2, h3 { color: var(--primary-color); text-align: center; }
        h1 { font-size: 2.8rem; }
        h2 { margin-bottom: 40px; font-size: 2.5rem; }
        h3 { font-size: 1.8rem; margin-bottom: 15px; }
        .article h3 { color: var(--secondary-color); }
        .tab-content h3 { text-align: left; color: var(--secondary-color); }
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
        .navbar .nav-links a { color: var(--primary-color); text-decoration: none; font-weight: bold; padding: 5px 10px; border-radius: 5px; transition: background-color 0.3s, color 0.3s; display: flex; align-items: center; gap: 5px; }
        .navbar .nav-links a:hover, .navbar .nav-links a.active { background-color: var(--primary-color); color: var(--white-color); }
        .hamburger { display: none; cursor: pointer; font-size: 24px; color: var(--primary-color); }
        .hero { padding-top: 70px; height: 650px; position: relative; overflow: hidden; color: var(--white-color); }
        .slider-container { width: 100%; height: 100%; position: relative; }
        .slide { display: none; width: 100%; height: 100%; animation: fadeEffect 1.5s ease-in-out; position: relative; }
        .slide::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.1); z-index: 1; }
        .slide img { width: 100%; height: 100%; object-fit: cover; }
        .slide.active { display: block; }
        @keyframes fadeEffect { from { opacity: 0.4; } to { opacity: 1; } }
        .slide-text { position: absolute; bottom: 40px; right: 40px; z-index: 2; color: var(--white-color); text-align: right; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); }
        .slide-text h2 { font-size: 2.8rem; font-weight: bold; margin: 0; line-height: 1.2; color: var(--white-color); text-align: right; }
        .slider-nav { position: absolute; top: 70%; width: 100%; display: flex; justify-content: space-between; transform: translateY(-50%); padding: 0 20px; z-index: 3; }
        .slider-nav button { background-color: rgba(0, 0, 0, 0.5); color: white; border: none; padding: 15px; font-size: 20px; cursor: pointer; border-radius: 50%; width: 50px; height: 50px; transition: background-color 0.3s; }
        .slider-nav button:hover { background-color: var(--primary-color); }
        .scrolling-text-banner { background-color: var(--secondary-color); padding: 12px 0; overflow: hidden; white-space: nowrap; }
        .scrolling-text-content { display: inline-block; animation: scroll-left 25s linear infinite; }
        .scrolling-text-content span { font-size: 1.1rem; font-weight: 500; color: var(--white-color); margin-right: 50px;}
        .scrolling-text-content span a { color: var(--accent-color); font-weight: bold; text-decoration: none; }
        @keyframes scroll-left { from { transform: translateX(100%); } to { transform: translateX(-100%); } }
        #intro-h1 { background: var(--white-color); padding-top: 60px; padding-bottom: 30px; }
        #intro-h1 p { max-width: 800px; margin: 20px auto 0 auto; text-align: center; font-size: 1.1rem; color: #555; }
        #articles { background: var(--light-color); }
        .articles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .article { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center; }
        .article img { max-width: 100%; width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 20px; }
        .article p { text-align: center; }
        #tabs-section { background: var(--white-color); }
        .tab-container { width: 100%; max-width: 900px; margin: 0 auto; }
        .tab-buttons { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; }
        .tab-btn { padding: 12px 25px; cursor: pointer; border: 2px solid var(--primary-color); background: var(--white-color); color: var(--primary-color); border-radius: 30px; font-size: 1rem; font-weight: bold; transition: all 0.3s ease; }
        .tab-btn.active, .tab-btn:hover { background: var(--primary-color); color: var(--white-color); }
        .tab-content { display: none; padding: 30px; border-radius: 8px; background: var(--light-color); text-align: left; }
        .tab-content.active { display: block; animation: fadeInContent 0.5s; }
        .tab-content p, .tab-content ul { margin-bottom: 15px; line-height: 1.7; }
        .tab-content ul { list-style-position: inside; padding-left: 10px; }
        @keyframes fadeInContent { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        #blog { background-color: var(--light-color); }
        .blog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .blog-card { background: var(--white-color); border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; text-align: left; transition: transform 0.3s, box-shadow 0.3s; }
        .blog-card:hover { transform: translateY(-10px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
        .blog-card img { width: 100%; height: 200px; object-fit: cover; }
        .blog-card-content { padding: 20px; }
        .blog-card-content h3 { text-align: left; font-size: 1.4rem; margin-bottom: 10px; }
        .blog-card p { font-size: 1rem; margin-bottom: 20px; color: #555; }
        .blog-card .read-more { color: var(--primary-color); text-decoration: none; font-weight: bold; }
        .blog-card .read-more:hover { text-decoration: underline; }
        #counter-section { background-color: var(--secondary-color); color: var(--white-color); padding: 80px 0; }
        .counter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
        .counter-item i { font-size: 3rem; color: var(--accent-color); margin-bottom: 15px; }
        .counter-item .counter-number { font-size: 3rem; font-weight: bold; }
        .counter-item .counter-title { font-size: 1.2rem; }
        #gallery { background-color: var(--white-color); }
        .gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .gallery-item { position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .gallery-item img.gallery-image { width: 100%; height: 250px; object-fit: cover; display: block; transition: transform 0.3s ease; cursor: pointer; }
        .gallery-item:hover img.gallery-image { transform: scale(1.05); }
        .lightbox-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); display: none; align-items: center; justify-content: center; z-index: 2000; padding: 20px; }
        .lightbox-content { position: relative; max-width: 90%; max-height: 90%; }
        .lightbox-content img { width: 100%; height: 100%; object-fit: contain; }
        .close-lightbox { position: absolute; top: -10px; right: 0px; color: white; font-size: 40px; font-weight: bold; cursor: pointer; transition: color 0.3s; }
        .close-lightbox:hover { color: var(--primary-color); }
        #contact { background: var(--light-color); }
        .contact-form { max-width: 700px; margin: 0 auto; background: var(--white-color); padding: 30px; border-radius: 10px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: var(--primary-color); text-align: left; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem; }
        .form-group textarea { resize: vertical; }
        .submit-btn { display: block; width: 100%; padding: 15px; border: none; background: var(--accent-color); color: var(--primary-color); font-size: 1.2rem; font-weight: bold; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; }
        .submit-btn:hover { background-color: #ffb300; }
        #sss-section { background-color: var(--light-color); }
        .accordion { max-width: 800px; margin: 0 auto; border-top: 1px solid #ddd; }
        .accordion-item { border-bottom: 1px solid #ddd; }
        .accordion-question { width: 100%; background: none; border: none; padding: 20px 10px; text-align: left; font-size: 1.2rem; font-weight: bold; color: var(--text-color); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background-color 0.2s; }
        .accordion-question:hover { background-color: #f0f0f0; }
        .accordion-question i { font-size: 1.1rem; color: var(--primary-color); transition: transform 0.3s ease; }
        .accordion-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out, padding 0.3s ease-out; background-color: var(--white-color); }
        .accordion-answer p { padding: 10px 15px 20px 15px; margin: 0; line-height: 1.7; color: #555; }
        .accordion-question.active + .accordion-answer { padding-top: 10px; }
        .accordion-question.active i { transform: rotate(45deg); }
        .footer { background: var(--primary-color); color: var(--white-color); padding: 40px 0; text-align: center; }
        .footer .container { display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .footer-contact p { margin-bottom: 10px; }
        .footer-contact a { color: var(--accent-color); text-decoration: none; }
        .footer-bottom { margin-top: 20px; border-top: 1px solid var(--secondary-color); padding-top: 20px; width: 100%; }
        @media(max-width: 992px) {
            .articles-grid, .blog-grid, .counter-grid, .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
        }
        @media(max-width: 768px) {
            h1 { font-size: 2.2rem; }
            h2 { font-size: 2rem; }
            .articles-grid, .blog-grid, .gallery-grid { grid-template-columns: 1fr; }
            .counter-grid { grid-template-columns: repeat(2, 1fr); }
            .navbar .nav-links { display: none; flex-direction: column; width: 100%; position: absolute; top: 70px; left: 0; background: var(--white-color); }
            .navbar .nav-links.active { display: flex; }
            .navbar .nav-links li { margin: 0; width: 100%; text-align: center; }
            .navbar .nav-links a { padding: 15px; display: block; border-radius: 0; border-bottom: 1px solid #eee; justify-content: center; }
            .hamburger { display: block; }
            .hero { height: 400px; }
            .slide-text h2 { font-size: 1.8rem; }
            /* Mobilde sticky icons ayarları */
            .sticky-icons-bottom-left { bottom: 15px; left: 15px; gap: 10px; z-index: 9999; }
            .sticky-icons-bottom-left a { width: 50px; height: 50px; font-size: 24px; }
            /* Mobilde telefon numarası bölümü */
            .contact-phone-box {
                flex-direction: column !important;
                padding: 20px 15px !important;
                gap: 10px !important;
            }
            .phone-label {
                font-size: 18px !important;
                text-align: center;
            }
            .phone-button {
                font-size: 20px !important;
                padding: 12px 20px !important;
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="sticky-icons-bottom-left">
        <?php if (!empty($whatsapp)): ?>
        <a href="https://wa.me/<?php echo $whatsapp; ?>?text=Merhaba,%20web%20sitenizden%20ulaşıyorum." target="_blank" class="whatsapp-icon" aria-label="WhatsApp ile İletişime Geç"><i class="fab fa-whatsapp"></i></a>
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
                    <li><a href="index.php" class="active">Ana Sayfa</a></li>
                    <li><a href="#articles">Hizmetlerimiz</a></li>
                    <li><a href="#gallery">Galeri</a></li>
                    <li><a href="#contact">İletişim</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="hero" class="hero">
            <div class="slider-container">
                <?php foreach ($sliders as $index => $slider): ?>
                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo $slider['image']; ?>" alt="<?php echo clean($slider['title']); ?>">
                    <div class="slide-text">
                        <h2><?php echo clean($slider['title']); ?><?php echo !empty($slider['subtitle']) ? '<br>' . clean($slider['subtitle']) : ''; ?></h2>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($sliders) > 1): ?>
            <div class="slider-nav">
                <button id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                <button id="nextBtn"><i class="fas fa-chevron-right"></i></button>
            </div>
            <?php endif; ?>
        </section>

        <div class="scrolling-text-banner">
            <div class="scrolling-text-content">
                <span><?php echo clean($company_name); ?>'ye Hoşgeldiniz. Profesyonel taşımacılık için bize ulaşın. <?php if (!empty($phone)): ?>Numaramız: <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a><?php endif; ?> &nbsp;&nbsp; ★ &nbsp;&nbsp;</span>
                <span><?php echo clean($company_name); ?>'ye Hoşgeldiniz. Profesyonel taşımacılık için bize ulaşın. <?php if (!empty($phone)): ?>Numaramız: <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a><?php endif; ?> &nbsp;&nbsp; ★ &nbsp;&nbsp;</span>
            </div>
        </div>

        <section id="intro-h1">
            <div class="container">
               <h1><?php echo clean($site_title); ?></h1>
               <p><?php echo clean($site_description); ?></p>
            </div>
        </section>

        <?php if (count($services) > 0): ?>
        <section id="articles">
            <div class="container">
                <h2>Kapsamlı Adana Nakliye Hizmetlerimiz</h2>
                <div class="articles-grid">
                    <?php foreach ($services as $service): ?>
                    <article class="article">
                        <img src="<?php echo $service['image']; ?>" alt="<?php echo clean($service['title']); ?>">
                        <h3><?php echo clean($service['title']); ?></h3>
                        <p><?php echo clean($service['short_description']); ?></p>
                        <a href="hizmet/<?php echo $service['slug']; ?>" class="read-more" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">Devamını Oku →</a>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (count($tabs) > 0): ?>
        <section id="tabs-section">
            <div class="container">
                  <h2>Adana Nakliye Fiyatları ve Süreç Yönetimi</h2>
                  <div class="tab-container">
                        <div class="tab-buttons">
                              <?php foreach ($tabs as $index => $tab): ?>
                              <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-tab="tab<?php echo $tab['id']; ?>"><?php echo clean($tab['title']); ?></button>
                              <?php endforeach; ?>
                        </div>
                        <?php foreach ($tabs as $index => $tab): ?>
                        <div id="tab<?php echo $tab['id']; ?>" class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                              <?php echo $tab['content']; ?>
                        </div>
                        <?php endforeach; ?>
                  </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (count($blogs) > 0): ?>
        <section id="blog">
            <div class="container">
                <h2>Blog Köşesi</h2>
                <div class="blog-grid">
                    <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card">
                        <img src="<?php echo $blog['image']; ?>" alt="<?php echo clean($blog['title']); ?>">
                        <div class="blog-card-content">
                            <h3><?php echo clean($blog['title']); ?></h3>
                            <p><?php echo clean($blog['excerpt']); ?></p>
                            <a href="blog/<?php echo $blog['slug']; ?>" class="read-more">Devamını Oku →</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section id="counter-section">
            <div class="container">
                <div class="counter-grid">
                    <div class="counter-item"><i class="fas fa-truck"></i><div class="counter-number" data-target="<?php echo $counter_vehicles; ?>">0</div><div class="counter-title">Araç Sayısı</div></div>
                    <div class="counter-item"><i class="fas fa-building"></i><div class="counter-number" data-target="<?php echo $counter_elevator; ?>">0</div><div class="counter-title">Asansör</div></div>
                    <div class="counter-item"><i class="fas fa-users"></i><div class="counter-number" data-target="<?php echo $counter_staff; ?>">0</div><div class="counter-title">Personel</div></div>
                    <div class="counter-item"><i class="fas fa-star"></i><div class="counter-number" data-target="<?php echo $counter_experience; ?>">0</div><div class="counter-title">Yıllık Tecrübe</div></div>
                </div>
            </div>
        </section>

        <?php if (count($gallery) > 0): ?>
        <section id="gallery">
            <div class="container">
                <h2>Galeri</h2>
                <div class="gallery-grid">
                    <?php foreach ($gallery as $item): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $item['image']; ?>" class="gallery-image" alt="<?php echo clean($item['title']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section id="contact">
            <div class="container">
                <h2>Bizimle İletişime Geçin</h2>

                <?php if (isset($_SESSION['contact_success'])): ?>
                    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border-left: 4px solid #28a745;">
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['contact_error'])): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border-left: 4px solid #dc3545;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?>
                    </div>
                <?php endif; ?>

                <form action="contact-form.php" method="POST" class="contact-form">
                    <div class="form-group"><label for="name">Adınız Soyadınız</label><input type="text" id="name" name="name" required></div>
                    <div class="form-group"><label for="email">E-posta Adresiniz</label><input type="email" id="email" name="email" required></div>
                    <div class="form-group"><label for="phone">Telefon Numaranız</label><input type="tel" id="phone" name="phone"></div>
                    <div class="form-group"><label for="message">Mesajınız</label><textarea id="message" name="message" rows="5" required></textarea></div>
                    <button type="submit" class="submit-btn">Teklif İste</button>
                </form>

                <?php if (!empty($phone2)): ?>
                <div class="contact-phone-box" style="text-align: center; margin-top: 30px; padding: 20px; background: #f0f0f0; border-radius: 8px; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 15px;">
                    <span class="phone-label" style="font-size: 22px; font-weight: bold; color: #34495e;">
                        Telefon Numaramız:
                    </span>
                    <a href="tel:<?php echo $phone2; ?>" class="phone-button" style="display: inline-block; padding: 15px 30px; background-color: #34495e; color: #ffffff; font-size: 24px; font-weight: bold; text-decoration: none; border-radius: 8px; transition: all 0.3s ease-in-out;">
                        <?php echo $phone2; ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div id="lightbox-overlay" class="lightbox-overlay">
        <span id="close-lightbox" class="close-lightbox">&times;</span>
        <div class="lightbox-content"><img id="lightbox-image" src="" alt="Büyük Resim"></div>
    </div>

    <?php if (count($sss_list) > 0): ?>
    <section id="sss-section">
        <div class="container">
            <h2>Adana Nakliye Hakkında Sıkça Sorulan Sorular</h2>
            <div class="accordion">
                <?php foreach ($sss_list as $sss): ?>
                <div class="accordion-item">
                    <button class="accordion-question"><?php echo clean($sss['question']); ?><i class="fas fa-plus"></i></button>
                    <div class="accordion-answer"><p><?php echo clean($sss['answer']); ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <footer class="footer">
        <div class="container">
            <div class="footer-contact">
                <h3><?php echo clean($company_name); ?></h3>
                <?php if (!empty($address)): ?><p>Adres: <?php echo clean($address); ?></p><?php endif; ?>
                <?php if (!empty($phone2)): ?><p>Telefon: <a href="tel:<?php echo $phone2; ?>"><?php echo $phone2; ?></a></p><?php endif; ?>
                <?php if (!empty($email)): ?><p>E-posta: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p><?php endif; ?>
            </div>
            <div class="footer-bottom"><p>&copy; <?php echo date('Y'); ?> <?php echo clean($company_name); ?>. Tüm Hakları Saklıdır.</p></div>
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

        const slides = document.querySelectorAll('.slide');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        let currentSlide = 0;
        let slideInterval;
        function nextSlide() { goToSlide(currentSlide + 1); }
        function prevSlide() { goToSlide(currentSlide - 1); }
        function goToSlide(n) {
            if (slides.length === 0) return;
            slides[currentSlide].classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        if (slides.length > 1) {
             slideInterval = setInterval(nextSlide, 5000);
             if(nextBtn && prevBtn){
                nextBtn.addEventListener('click', () => { nextSlide(); clearInterval(slideInterval); slideInterval = setInterval(nextSlide, 8000); });
                prevBtn.addEventListener('click', () => { prevSlide(); clearInterval(slideInterval); slideInterval = setInterval(nextSlide, 8000); });
            }
        }

        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                tabContents.forEach(content => {
                    content.classList.toggle('active', content.id === targetTab);
                });
            });
        });

        const accordionQuestions = document.querySelectorAll('.accordion-question');
        accordionQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const wasActive = this.classList.contains('active');
                accordionQuestions.forEach(q => {
                    q.classList.remove('active');
                    q.nextElementSibling.style.maxHeight = null;
                });
                if (!wasActive) {
                    this.classList.add('active');
                    const answer = this.nextElementSibling;
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });

        const galleryImages = document.querySelectorAll('.gallery-image');
        const lightboxOverlay = document.getElementById('lightbox-overlay');
        const lightboxImage = document.getElementById('lightbox-image');
        const closeLightbox = document.getElementById('close-lightbox');
        if (lightboxOverlay) {
            galleryImages.forEach(image => {
                image.addEventListener('click', function() {
                    const imageUrl = this.getAttribute('src');
                    lightboxImage.setAttribute('src', imageUrl);
                    lightboxOverlay.style.display = 'flex';
                });
            });
            const closeFunction = () => { lightboxOverlay.style.display = 'none'; };
            if(closeLightbox) closeLightbox.addEventListener('click', closeFunction);
            lightboxOverlay.addEventListener('click', function(e) {
                if (e.target === lightboxOverlay) { closeFunction(); }
            });
        }

        const counterSection = document.getElementById('counter-section');
        if(counterSection){
            const counters = document.querySelectorAll('.counter-number');
            let started = false;
            const startCounter = () => {
                if (started) return;
                started = true;
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const speed = 200;
                    const increment = target / speed;
                    const updateCount = () => {
                        const count = +counter.innerText;
                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                });
            };
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    startCounter();
                    observer.disconnect();
                }
            }, { threshold: 0.2 });
            observer.observe(counterSection);
        }
    });
    </script>
</body>
</html>
