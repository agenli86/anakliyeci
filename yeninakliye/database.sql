-- Adana Nakliye Admin Panel Veritabanı
-- Veritabanı: adana_nakliye

CREATE DATABASE IF NOT EXISTS adana_nakliye CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
USE adana_nakliye;

-- Admin Kullanıcıları Tablosu
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan admin kullanıcısı (Kullanıcı: admin, Şifre: admin123)
INSERT INTO admin_users (username, password, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@adananakliye.tr', 'Admin Kullanıcı');

-- Slider Tablosu
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    image VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan slider verileri
INSERT INTO sliders (title, subtitle, image, display_order, is_active) VALUES
('Adana Nakliye', 'Profesyonel Çözümler', 'resimler/barajnakliyat.jpg', 1, 1),
('Sigortalı', 'Evden Eve Nakliyat', 'resimler/barajnakliye (2).png', 2, 1),
('Uygun Fiyatlı', 'Şehir İçi Taşımacılık', 'resimler/barak3.png', 3, 1);

-- Hizmetler Tablosu
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT NOT NULL,
    full_content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords TEXT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan hizmetler
INSERT INTO services (title, slug, short_description, full_content, image, meta_title, meta_description, display_order, is_active) VALUES
('Adana Evden Eve Nakliyat', 'adana-evden-eve-nakliyat', 'Adana Nakliye olarak, evden eve nakliyat hizmetinde uzman kadromuzla yanınızdayız.', '<h3>Adana Evden Eve Nakliyat</h3><p>Adana Nakliye olarak, evden eve nakliyat hizmetinde uzman kadromuzla yanınızdayız. Eşyalarınızın demontajı, hassas eşyalar için özel ambalajlama, güvenli yükleme ve yeni evinizde istediğiniz düzende montajı profesyonel ekibimiz tarafından yapılır.</p><p>Tüm nakliye süreçlerimiz sigorta güvencesi altındadır.</p>', 'resimler/nakliye.jpg', 'Adana Evden Eve Nakliyat - Profesyonel Taşımacılık', 'Adana evden eve nakliyat hizmeti. Sigortalı, güvenilir ve uygun fiyatlı taşımacılık çözümleri.', 1, 1),
('Adana Şehir İçi Nakliye', 'adana-sehir-ici-nakliye', 'Adana\'nın tüm ilçelerine hızlı ve etkin şehir içi nakliyat çözümleri sunuyoruz.', '<h3>Adana Şehir İçi Nakliye</h3><p>Adana\'nın Çukurova, Seyhan, Yüreğir, Sarıçam gibi tüm ilçelerine ve mahallelerine hızlı ve etkin şehir içi nakliyat çözümleri sunuyoruz.</p><p>Zamanlamanın ne kadar önemli olduğunu biliyor, bu nedenle söz verdiğimiz saatte adresinizde olup taşıma işlemini en verimli şekilde tamamlıyoruz.</p>', 'resimler/nakliyat.jpg', 'Adana Şehir İçi Nakliye - Hızlı Taşımacılık', 'Adana şehir içi nakliye hizmeti. Tüm ilçelere hızlı ve güvenilir taşımacılık.', 2, 1),
('Adana Asansörlü Nakliyat', 'adana-asansorlu-nakliyat', 'Yüksek katlarda oturuyor veya taşınıyorsanız, asansörlü nakliyat hizmetimiz tam size göre.', '<h3>Adana Asansörlü Nakliyat</h3><p>Yüksek katlarda oturuyor veya taşınıyorsanız, Adana asansörlü nakliyat hizmetimiz tam size göre. Bu modern taşımacılık yöntemi ile eşyalarınız bina içine veya merdivenlere sürtünmeden, hasar riski olmadan hızlı ve güvenli bir şekilde taşınır.</p><p>Asansörlü sistem, nakliye ücretleri açısından da zaman tasarrufu sağlayarak avantaj sunar.</p>', 'resimler/asansorlunakliyat.jpg', 'Adana Asansörlü Nakliyat - Güvenli Taşıma', 'Adana asansörlü nakliyat hizmeti. Yüksek katlara güvenli ve hızlı taşımacılık.', 3, 1);

-- Blog/Bölgeler Tablosu
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords TEXT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan blog yazıları
INSERT INTO blog_posts (title, slug, excerpt, content, image, meta_title, meta_description, display_order, is_active) VALUES
('Kozan Nakliyat', 'kozan-nakliyat', 'Kozan ilçesine özel nakliyat çözümlerimizle hizmetinizdeyiz.', '<h1>Kozan Nakliyat Hizmeti</h1><p>Kozan ilçesine özel nakliyat çözümlerimizle hizmetinizdeyiz. Hızlı, güvenilir ve ekonomik taşıma için bize ulaşın.</p><p>Kozan ve çevre köylere kadar uzanan geniş hizmet ağımızla, eşyalarınızı güvenle taşıyoruz.</p>', 'resimler/kozannakliyat.png', 'Kozan Nakliyat - Adana Nakliye', 'Kozan nakliyat hizmeti. Kozan ve çevre köylere profesyonel taşımacılık.', 1, 1),
('Çukurova Nakliyat', 'cukurova-nakliyat', 'Merkezimiz olan Çukurova\'da evden eve nakliyat hizmetleri sunuyoruz.', '<h1>Çukurova Nakliyat Hizmeti</h1><p>Merkezimiz olan Çukurova\'da evden eve nakliyat, asansörlü taşıma ve ofis taşımacılığı hizmetleri sunuyoruz.</p><p>Çukurova\'nın her noktasına hızlı ve güvenilir nakliye hizmeti veriyoruz.</p>', 'resimler/barajnakliye.png', 'Çukurova Nakliyat - Adana Nakliye', 'Çukurova nakliyat hizmeti. Çukurova\'da evden eve ve ofis taşımacılığı.', 2, 1),
('Karaisalı Nakliyat', 'karaisali-nakliyat', 'Karaisalı ve çevresindeki köylere kadar uzanan geniş hizmet ağımız.', '<h1>Karaisalı Nakliyat Hizmeti</h1><p>Karaisalı ve çevresindeki köylere kadar uzanan geniş hizmet ağımızla eşyalarınızı güvenle taşıyoruz.</p><p>Karaisalı bölgesinde yıllardır kesintisiz nakliye hizmeti vermekteyiz.</p>', 'resimler/karaisalınakliyat.png', 'Karaisalı Nakliyat - Adana Nakliye', 'Karaisalı nakliyat hizmeti. Karaisalı ve çevre köylere güvenilir taşımacılık.', 3, 1);

-- Galeri Tablosu
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan galeri resimleri
INSERT INTO gallery (title, image, display_order, is_active) VALUES
('Adana Nakliye', 'resimler/resim2.jpg', 1, 1),
('Adana Nakliye', 'resimler/resim3.jpg', 2, 1),
('Adana Nakliye', 'resimler/resim4.jpg', 3, 1);

-- Tablar Tablosu
CREATE TABLE IF NOT EXISTS tabs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan tab içerikleri
INSERT INTO tabs (title, content, display_order, is_active) VALUES
('Adana Nakliye Fiyatları', '<h3>Şeffaf ve Uygun Nakliye Ücretleri</h3><p>Adana nakliye fiyatları, birçok değişkene bağlı olarak farklılık gösterir. Firmamız, size en doğru ve net fiyatı sunmak için şeffaf bir fiyatlandırma politikası izler.</p>', 1, 1),
('Profesyonel Nakliye Süreci', '<h3>A\'dan Z\'ye Planlı Taşıma Süreci</h3><p>Başarılı bir taşımacılık deneyimi, doğru planlama ile başlar. Adana Nakliye olarak izlediğimiz adımlar ile size en iyi hizmeti sunuyoruz.</p>', 2, 1),
('Neden Adana Nakliye?', '<h3>Güven ve Tecrübenin Adresi</h3><p>Adana nakliye firmaları arasında bizi öne çıkaran birçok neden var. Tecrübeli ekibimiz, sigortalı taşımacılığımız ve müşteri memnuniyeti odaklı yaklaşımımızla yanınızdayız.</p>', 3, 1);

-- SSS Tablosu
CREATE TABLE IF NOT EXISTS sss (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan SSS
INSERT INTO sss (question, answer, display_order, is_active) VALUES
('Adana nakliye ücretleri nasıl belirleniyor?', 'Adana nakliye ücretleri; taşınacak eşyanın hacmi, iki adres arası mesafe, binalardaki kat sayıları ve asansörlü taşımacılık hizmeti gerekip gerekmediği gibi faktörlere göre hesaplanır.', 1, 1),
('Eşyalarım için sigortalı taşımacılık hizmeti sağlıyor musunuz?', 'Evet, kesinlikle. Tüm evden eve nakliye hizmetlerimiz, eşyalarınızı paketleme anından yeni evinize yerleştirilmesine kadar sigorta güvencesi altındadır.', 2, 1),
('Taşıma günü ekip kaçta gelir ve süreç ne kadar sürer?', 'Ekibimiz, anlaşılan gün ve saatte (genellikle sabah 08:00-08:30 arası) tam teçhizatlı olarak adresinizde olur. Standart bir 2+1 veya 3+1 dairenin nakliyat süreci genellikle aynı gün içerisinde tamamlanır.', 3, 1),
('Asansörlü nakliyat hizmetinin avantajları nelerdir?', 'Asansörlü nakliyat, özellikle yüksek katlı binalarda taşıma sürecini önemli ölçüde hızlandırır. Eşyaların dar merdiven boşluklarında veya kapılarda hasar görme riskini ortadan kaldırır.', 4, 1);

-- Site Ayarları Tablosu
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_type VARCHAR(50) DEFAULT 'text',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan site ayarları
INSERT INTO settings (setting_key, setting_value, setting_type) VALUES
('site_title', 'Adana Nakliye | Şehir İçi ve Şehirler Arası Taşımacılık', 'text'),
('site_description', 'Adana nakliye firması olarak profesyonel evden eve nakliyat, şehir içi taşımacılık ve asansörlü nakliye hizmetleri sunuyoruz.', 'textarea'),
('site_keywords', 'adana nakliye, adana nakliye firmaları, adana nakliye fiyatları, adana evden eve nakliyat', 'textarea'),
('company_name', 'Adana Nakliye', 'text'),
('phone', '03224095198', 'text'),
('phone2', '03224083133', 'text'),
('whatsapp', '905374092406', 'text'),
('email', 'info@adananakliye.tr', 'text'),
('address', 'Toros, 78035. Sk. No:5, 01170 Çukurova/Adana', 'text'),
('counter_vehicles', '3', 'number'),
('counter_elevator', '1', 'number'),
('counter_staff', '10', 'number'),
('counter_experience', '20', 'number'),
('google_analytics', 'AW-17382513923', 'text'),
('logo', 'resimler/logo.png', 'text'),
('favicon', 'resimler/favicon.png', 'text');
