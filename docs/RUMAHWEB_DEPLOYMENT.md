# Panduan Deploy COMP-KAIZEN ke Rumahweb

Panduan ini untuk domain `kaizenkreasi.com`. Aplikasi memakai Laravel 12, PHP 8.2 atau lebih baru, dan MySQL.

## 1. Persyaratan hosting

Pastikan paket Rumahweb menyediakan:

- PHP 8.2 atau PHP 8.3.
- MySQL/MariaDB.
- Ekstensi PHP `bcmath`, `ctype`, `fileinfo`, `filter`, `mbstring`, `openssl`, `pdo_mysql`, dan `tokenizer`.
- Access SSH lebih disarankan agar Composer dan Artisan dapat dijalankan.
- Kemampuan mengubah document root domain.

Aktifkan SSL dan pilih versi PHP 8.2 atau 8.3 dari panel hosting.

## 2. Buat database

Di cPanel Rumahweb, buat database MySQL beserta user database. Catat:

```text
Nama database
Username database
Password database
Nama host database
Port database, umumnya 3306
```

Berikan hak akses penuh kepada user database.

## 3. Upload aplikasi

Gunakan SSH bila tersedia. Dari komputer lokal, build terlebih dahulu:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

Unggah seluruh isi project ke folder di luar `public_html`, misalnya:

```text
/home/username/comp-kaizen/
```

Jangan unggah isi folder Laravel langsung ke `public_html`. Folder yang dapat dirender browser harus berasal dari folder `public`.

### Jika hosting mengizinkan document root

Atur document root domain `kaizenkreasi.com` ke:

```text
/home/username/comp-kaizen/public
```

### Jika document root tidak dapat diubah

Pindahkan seluruh isi folder `public` ke `public_html`, lalu pindahkan folder aplikasi ke satu tingkat di luar `public_html`.

Ubah isi `public_html/index.php` agar path aplikasi sesuai lokasi server:

```php
require __DIR__.'/../comp-kaizen/vendor/autoload.php';
$app = require_once __DIR__.'/../comp-kaizen/bootstrap/app.php';
```

Sesuaikan path sesuai nama folder project yang digunakan. Jangan memakai `index.php` Laravel lama tanpa mengubah path tersebut.

## 4. Buat file konfigurasi produksi

Salin `.env.example` menjadi `.env`, lalu gunakan konfigurasi berikut dan isi nilainya:

```dotenv
APP_NAME="Kaizen Kreasi Indonesia"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://kaizenkreasi.com
TRUSTED_PROXIES=IP_PROXY_DARI_PENYEDIA_HOSTING

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database

SESSION_DRIVER=database
SESSION_LIFETIME=120
FILESYSTEM_DISK=public
CACHE_STORE=database
QUEUE_CONNECTION=database

ADMIN_EMAIL=admin@kaizenkreasi.com
ADMIN_PASSWORD=PasswordAdminYangSangatKuat

COMPANY_NAME="Kaizen Kreasi Indonesia"
COMPANY_EMAIL=kaizenkreasi@gmail.com
COMPANY_PHONE=0811-1305-1007
COMPANY_WHATSAPP=6281113051007
COMPANY_MAPS_URL=https://maps.app.goo.gl/YJibJiqrCoqz95qG8?g_st=ac
```

Nama kolom database yang tersedia mungkin berbeda dari nama user atau database panel Rumahweb. Sesuaikan nilai setelah database dibuat.

Generate application key dengan SSH:

```bash
cd /home/username/comp-kaizen
php artisan key:generate
```

Jika SSH tidak tersedia, jalankan `php artisan key:generate --show` secara lokal lalu tempel hasilnya ke `APP_KEY`.

## 5. Siapkan database dan cache

Jalankan dari folder project:

```bash
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

Pastikan folder berikut dapat ditulis web server:

```text
storage/
bootstrap/cache/
```

Izin yang umum digunakan adalah `775` untuk folder dan `664` untuk file. Jangan memberikan izin `777`.

## 6. Aktifkan domain dan SSL

1. Atur DNS domain ke nameserver atau IP Rumahweb yang diberikan Rumahweb.
2. Pastikan domain tanpa `www` mengarah ke aplikasi.
3. Aktifkan SSL/Let's Encrypt untuk `kaizenkreasi.com`.
4. Aktifkan SSL untuk `www.kaizenkreasi.com`.
5. Redirect seluruh request `www` ke versi tanpa `www` dengan kode 301.

Contoh redirect di `.htaccess` jika document root berupa `public_html` dan SSL sudah aktif:

```apache
RewriteEngine On

RewriteCond %{HTTP_HOST} ^(?:www\.)?kaizenkreasi\.com$ [NC]
RewriteCond %{HTTPS} !=on [OR]
RewriteCond %{HTTP_HOST} ^www\.kaizenkreasi\.com$ [NC]
RewriteRule ^ https://kaizenkreasi.com%{REQUEST_URI} [R=301,L,NE]
```

Aturan ini juga tersedia di `public/.htaccess`. Pastikan SSL untuk domain utama dan `www` aktif sebelum menggunakannya.

Jika hosting meneruskan HTTPS melalui reverse proxy, Laravel harus mempercayai hanya alamat IP proxy yang benar. Isi `TRUSTED_PROXIES` dengan IP proxy dari penyedia hosting (pisahkan beberapa IP dengan koma); jangan gunakan `*`. Pastikan proxy mengirim `X-Forwarded-Proto: https` untuk koneksi HTTPS. Aturan `.htaccess` memeriksa header tersebut agar koneksi HTTPS yang diteruskan ke backend HTTP tidak diarahkan berulang kali.

## 7. Verifikasi pemasangan

Uji alamat berikut:

```text
https://kaizenkreasi.com
https://kaizenkreasi.com/robots.txt
https://kaizenkreasi.com/sitemap.xml
https://kaizenkreasi.com/admin
https://kaizenkreasi.com/kontak
https://kaizenkreasi.com/portofolio
https://kaizenkreasi.com/alamat-yang-tidak-ada
```

Contoh hasil yang benar:

- Homepage berstatus HTTP 200.
- Sitemap berisi URL domain `https://kaizenkreasi.com`.
- URL `http://kaizenkreasi.com` dan `https://www.kaizenkreasi.com` dialihkan permanen ke HTTPS tanpa `www`.
- Alamat yang tidak tersedia menghasilkan HTTP 404, bukan halaman sukses HTTP 200.
- Tidak ada halaman Laravel yang menampilkan debug error.
- Admin dapat login dengan `ADMIN_EMAIL` dan `ADMIN_PASSWORD`.
- Upload gambar pada admin berhasil dan gambar dapat dibuka.

## 8. Checklist update aplikasi

Sebelum deploy, ekspor/backup database lewat phpMyAdmin dan backup `storage/app/public`. Pastikan backup berhasil sebelum melanjutkan. Setelah itu jalankan:

```bash
php artisan down
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan optimize
php artisan up
```

Untuk membuat file WebP setelah backup file, gunakan `php artisan kaizen:optimize-images` bila hosting menyediakan PHP GD dengan dukungan WebP. Perintah ini mempertahankan file sumber; uji beberapa halaman dan gambar setelahnya.

## 9. Troubleshooting

### Error 500

- Pastikan PHP minimal 8.2.
- Pastikan `APP_KEY` terisi.
- Cek `storage/logs/laravel.log`.
- Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis.
- Jalankan `php artisan config:clear`.

### Halaman atau gambar tidak ditemukan

- Pastikan document root mengarah ke folder `public`.
- Pastikan file `public/.htaccess` tersedia dan `mod_rewrite` aktif.
- Jalankan `php artisan storage:link`.
- Pastikan link publik gambar mengarah ke `storage/app/public`.

### Error database

- Periksa `DB_HOST`, database, username, dan password.
- Pastikan database memberikan hak akses penuh kepada user.
- Jalankan `php artisan migrate --force`.

### Sitemap atau SEO masih menampilkan Railway

- Pastikan `APP_URL=https://kaizenkreasi.com`.
- Bersihkan cache dengan `php artisan config:clear && php artisan config:cache`.

## 10. Setelah online

1. Daftarkan dan verifikasi `kaizenkreasi.com` di Google Search Console.
2. Kirim sitemap: `https://kaizenkreasi.com/sitemap.xml`.
3. Tambahkan properti domain `www` jika diperlukan.
4. Uji canonical URL pada beberapa halaman produk dan portofolio.
5. Buat backup database dan folder `storage/app/public` secara rutin.
