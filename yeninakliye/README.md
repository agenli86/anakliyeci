# Adana Nakliye - PHP Admin Panel Sistemi

Profesyonel nakliye firmaları için geliştirilmiş, tam özellikli PHP tabanlı içerik yönetim sistemi.

## Özellikler

### Admin Panel Modülleri
- ✅ **Slider Yönetimi** - Ana sayfa slider'ları ekle, düzenle, sil
- ✅ **Hizmetler** - SEO uyumlu hizmet sayfaları (Meta başlık, açıklama, anahtar kelime desteği)
- ✅ **Blog/Bölgeler** - SEO uyumlu blog yazıları ve bölge sayfaları
- ✅ **Galeri** - Resim galerisi yönetimi
- ✅ **Tab İçerikleri** - Ana sayfadaki sekme içeriklerini düzenle
- ✅ **SSS** - Sıkça sorulan sorular yönetimi
- ✅ **Site Ayarları** - Genel site ayarları, iletişim bilgileri, sayaç değerleri

### Frontend Özellikleri
- 📱 Responsive (Mobil uyumlu) tasarım
- 🔍 SEO dostu URL yapısı
- 🎨 Modern ve profesyonel arayüz
- ⚡ Hızlı yükleme
- 📝 TinyMCE WYSIWYG editör desteği
- 🖼️ Lightbox galeri görüntüleme
- 📊 Animasyonlu sayaçlar
- 🎠 Otomatik slider geçişleri

## Kurulum

### Gereksinimler
- PHP 7.4 veya üzeri
- MySQL 5.7 veya üzeri
- Apache/Nginx web sunucusu
- mod_rewrite aktif olmalı

### Adım 1: Veritabanı Kurulumu
1. phpMyAdmin veya benzeri bir araçla yeni veritabanı oluşturun
2. `database.sql` dosyasını import edin
3. Veritabanı bilgileri:
   - Varsayılan veritabanı adı: `adana_nakliye`
   - Admin kullanıcı adı: `admin`
   - Admin şifresi: `admin123`

### Adım 2: Yapılandırma
1. `config/database.php` dosyasını açın
2. Veritabanı bağlantı bilgilerini güncelleyin:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'veritabani_kullanici_adi');
define('DB_PASS', 'veritabani_sifresi');
define('DB_NAME', 'adana_nakliye');
```

3. Site URL'sini güncelleyin:
```php
define('SITE_URL', 'http://siteniz.com');
```

### Adım 3: Klasör İzinleri
`uploads/` klasörüne yazma izni verin:
```bash
chmod 755 uploads/
```

### Adım 4: Mevcut Resimleri Taşıma
Mevcut `resimler/` klasöründeki resimleri kullanmak için:
```bash
cp -r adananakliye.tr\ \(4\)/adananakliye.tr/resimler/* uploads/
```

### Adım 5: Admin Panele Erişim
- Admin Panel: `http://siteniz.com/admin`
- Kullanıcı Adı: `admin`
- Şifre: `admin123`

**ÖNEMLİ:** İlk girişten sonra mutlaka admin şifrenizi değiştirin!

## Kullanım

### Admin Panel Menüsü
1. **Dashboard** - Genel istatistikler ve hızlı erişim
2. **Slider Yönetimi** - Ana sayfa slider'ları
3. **Hizmetler** - Hizmet sayfaları ekleme/düzenleme
4. **Bölgeler/Blog** - Blog yazıları ve bölge sayfaları
5. **Galeri** - Resim galerisi
6. **Tab İçerikleri** - Ana sayfa tab'ları
7. **SSS** - Sıkça sorulan sorular
8. **Site Ayarları** - Genel ayarlar

### Yeni Hizmet Ekleme
1. Admin panelden **Hizmetler** > **Yeni Hizmet Ekle**
2. Hizmet başlığı, açıklama ve detayları girin
3. **SEO Ayarları** bölümünden meta başlık ve açıklama ekleyin
4. Resim yükleyin
5. Kaydet butonuna tıklayın

Hizmet detay sayfası otomatik olarak şu formatta oluşur:
`http://siteniz.com/hizmet/hizmet-adi`

### Yeni Blog Yazısı Ekleme
1. Admin panelden **Blog/Bölgeler** > **Yeni Blog Ekle**
2. Başlık, özet ve içerik girin
3. SEO ayarlarını yapın
4. Resim yükleyin
5. Kaydet

Blog detay sayfası:
`http://siteniz.com/blog/blog-adi`

### Site Ayarları
**Site Ayarları** menüsünden düzenleyebileceğiniz bilgiler:
- Site başlığı ve açıklaması
- Firma bilgileri (ad, adres, telefon, e-posta)
- WhatsApp numarası
- Sayaç değerleri (araç, personel, tecrübe)
- Google Analytics ID

## Güvenlik

### Önemli Güvenlik Notları
1. ✅ Admin şifrenizi mutlaka değiştirin
2. ✅ `config/` klasöründeki dosyalar `.htaccess` ile korunmaktadır
3. ✅ SQL injection koruması aktif (PDO prepared statements)
4. ✅ XSS koruması (`htmlspecialchars` fonksiyonu)
5. ✅ Dosya yükleme güvenliği (tip ve boyut kontrolü)

### Şifre Değiştirme
Admin şifrenizi değiştirmek için veritabanında şu sorguyu çalıştırın:
```sql
UPDATE admin_users SET password = '$2y$10$yeni_hash' WHERE username = 'admin';
```

Yeni hash oluşturmak için PHP:
```php
echo password_hash('yeni_sifre', PASSWORD_DEFAULT);
```

## Teknolojiler

- **Backend:** PHP 7.4+, MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Editör:** TinyMCE 6
- **İkonlar:** Font Awesome 6
- **Veritabanı:** PDO (MySQL)

## Klasör Yapısı

```
yeninakliye/
├── admin/                  # Admin panel dosyaları
│   ├── includes/          # Header ve footer
│   ├── index.php          # Dashboard
│   ├── login.php          # Giriş sayfası
│   ├── sliders.php        # Slider yönetimi
│   ├── services.php       # Hizmetler listesi
│   ├── service-edit.php   # Hizmet ekleme/düzenleme
│   ├── blog.php           # Blog listesi
│   ├── blog-edit.php      # Blog ekleme/düzenleme
│   ├── gallery.php        # Galeri yönetimi
│   ├── tabs.php           # Tab yönetimi
│   ├── sss.php            # SSS yönetimi
│   └── settings.php       # Site ayarları
├── config/                # Yapılandırma dosyaları
│   ├── database.php       # Veritabanı bağlantısı
│   └── functions.php      # Yardımcı fonksiyonlar
├── uploads/               # Yüklenen dosyalar
│   ├── sliders/
│   ├── services/
│   ├── blog/
│   └── gallery/
├── resimler/              # Statik resimler (logo, favicon)
├── index.php              # Ana sayfa
├── hizmet-detay.php       # Hizmet detay sayfası
├── blog-detay.php         # Blog detay sayfası
├── .htaccess              # URL rewrites
├── database.sql           # Veritabanı yapısı
└── README.md              # Bu dosya
```

## Sık Karşılaşılan Sorunlar

### Resimler Yüklenmiyor
- `uploads/` klasörünün yazma izni olduğundan emin olun (chmod 755)
- PHP upload_max_filesize değerini kontrol edin

### .htaccess Çalışmıyor
- Apache mod_rewrite modülünün aktif olduğundan emin olun
- AllowOverride All ayarının yapıldığından emin olun

### Veritabanı Bağlantı Hatası
- `config/database.php` dosyasındaki bilgileri kontrol edin
- MySQL servisinin çalıştığından emin olun

## Destek ve İletişim

Herhangi bir sorun veya soru için:
- 📧 E-posta: info@adananakliye.tr
- 📱 Telefon: (0322) 409 51 98

## Lisans

Bu proje özel olarak Adana Nakliye için geliştirilmiştir.

---

**Geliştirme Tarihi:** 2025
**Versiyon:** 1.0.0
