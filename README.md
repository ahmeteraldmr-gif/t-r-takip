# Tır Takip Sistemi

Laravel ile geliştirilmiş çok kullanıcılı tır takip sistemi.

## Özellikler

- **Tır Yönetimi**: Plaka, marka, model ile tır ekleme/düzenleme/silme
- **Sefer Takibi**: Çıkış tarihi, hedef, durum ile sefer oluşturma
- **Benzin Masrafları**: Litre, fiyat, kullanılan litre kaydı
- **Diğer Masraflar**: Yemek, otel, yol geçiş vb. kategorilerde masraf girişi
- **Olası Sorunlar**: Teker patlama, motor arızası, kaza vb. olay kaydı ve maliyeti
- **Komisyon**: Sefer başına sabit komisyon ücreti
- **Dashboard**: Özet istatistikler ve son seferler

## Gereksinimler

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (geliştirme) veya MySQL

## Kurulum

```bash
# Bağımlılıklar yüklü (Laravel + Breeze kurulu)
composer install
npm install && npm run build

# Veritabanı
php artisan migrate

# Uygulamayı çalıştır
php artisan serve
```

Tarayıcıda http://localhost:8000 adresine gidin. Yeni kullanıcı kaydı oluşturup giriş yapın.

## Kullanım

1. **Kayıt/Login**: Önce hesap oluşturun veya giriş yapın
2. **Tır Ekle**: Tırlar menüsünden plaka, marka, model ile tır ekleyin
3. **Sefer Başlat**: Tır detayında veya Seferler'den yeni sefer ekleyin (çıkış tarihi, hedef, komisyon)
4. **Masrafları Gir**: Sefer detay sayfasından benzin, diğer masraflar ve olası sorunları ekleyin
5. **Özet**: Her sefer için toplam masraf otomatik hesaplanır

## Teknolojiler

- Laravel 12
- Laravel Breeze (Blade + Tailwind)
- SQLite (varsayılan veritabanı)
