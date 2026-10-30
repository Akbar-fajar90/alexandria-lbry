<p align="center">
  <a href="https://github.com/Akbar-fajar90/alexandria-lbry" target="_blank">
    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80" width="100%" alt="Alexandria Library Banner" style="border-radius: 8px; object-fit: cover; max-height: 350px;">
  </a>
</p>
<p align="center">
  <a href="https://github.com/Akbar-fajar90/alexandria-lbry/actions"><img src="https://img.shields.io/github/actions/workflow/status/Akbar-fajar90/alexandria-lbry/tests.yml?label=build&style=flat-square" alt="Build Status"></a>
  <a href="https://github.com/Akbar-fajar90/alexandria-lbry/stargazers"><img src="https://img.shields.io/github/stars/Akbar-fajar90/alexandria-lbry?style=flat-square" alt="Stars"></a>
  <a href="https://github.com/Akbar-fajar90/alexandria-lbry/network/members"><img src="https://img.shields.io/github/forks/Akbar-fajar90/alexandria-lbry?style=flat-square" alt="Forks"></a>
  <a href="https://github.com/Akbar-fajar90/alexandria-lbry/blob/main/LICENSE"><img src="https://img.shields.io/github/license/Akbar-fajar90/alexandria-lbry?style=flat-square" alt="License"></a>
</p>
---
 
## Tentang Alexandria Library (`alexandria-lbry`)
 
**Alexandria Library** adalah sistem manajemen informasi perpustakaan berbasis web yang dirancang khusus untuk memenuhi standar penilaian **UKK (Uji Keterampilan Kemampuan)**. Project ini dibangun menggunakan **Laravel Framework** dengan menerapkan arsitektur kode yang bersih, aman, dan mudah dirawat demi efisiensi pengelolaan sirkulasi buku.
 
Nama project ini terinspirasi dari *Perpustakaan Alexandria*, salah satu pusat pengetahuan paling besar dan legendaris di dunia kuno. Harapannya, sistem ini mampu menjadi pusat manajemen data literasi yang andal dan terorganisir untuk lingkungan sekolah maupun instansi.
 
### Fitur Utama
 
Sistem ini dirancang untuk mengakomodasi seluruh proses bisnis operasional perpustakaan secara digital, meliputi:
 
- **Autentikasi Multi-User:** Pembagian hak akses kustom yang jelas untuk Administrator dan Siswa.
- **Katalog Buku Terpusat:** Pengelolaan data buku, kategori, penerbit, serta pelacakan sisa stok buku secara *real-time*.
- **Sirkulasi Peminjaman Digital:** Manajemen pencatatan pinjaman, pengembalian mandiri, hingga otomatisasi kalkulasi denda keterlambatan.
- **Sistem Pencarian Cepat:** Memudahkan penemuan judul buku dan pelacakan riwayat sirkulasi transaksi.
- **Laporan Instan (Reporting):** Ekspor atau rekapitulasi data sirkulasi berkala untuk kebutuhan dokumentasi administrasi UKK.
---
 
## Spesifikasi Teknologi
 
| Komponen | Detail |
|---|---|
| **Core Framework** | Laravel 13.x (PHP >= 8.3) |
| **Database Engine** | MySQL / MariaDB |
| **Frontend Tooling** | Vite & Blade Templating Engine |
| **Package Manager** | Composer |
 
---
 
## Langkah Instalasi & Penggunaan
 
Ikuti panduan berikut untuk menjalankan project Alexandria Library di lingkungan lokal (*localhost*) Anda:
 
### 1. Kloning Repositori
 
```bash
git clone https://github.com/Akbar-fajar90/alexandria-lbry.git
cd alexandria-lbry
```
 
### 2. Instalasi Dependensi PHP
 
```bash
composer install
```
 
### 3. Konfigurasi Environment File
 
Salin file konfigurasi contoh menjadi file environment aktif:
 
```bash
cp .env.example .env
```
 
Buka file `.env` menggunakan teks editor Anda, lalu sesuaikan kredensial database:
 
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alexandria_lbry
DB_USERNAME=root
DB_PASSWORD=
```
 
### 4. Generate Application Key
 
```bash
php artisan key:generate
```
 
### 5. Migrasi Database & Data Awal
 
Gunakan perintah berikut untuk membuat seluruh tabel skema sistem ke database Anda secara otomatis:
 
```bash
php artisan migrate --seed
```
 
> **Catatan:** Jika Anda ingin menggunakan berkas database SQL manual, Anda juga dapat mengimpor file `alexandria-lbry.sql` yang terletak di root direktori langsung ke database MySQL Anda.
 
### 6. Jalankan Local Server
 
Nyalakan server development bawaan Laravel:
 
```bash
php artisan serve
```
 
Buka browser Anda dan akses sistem di alamat: **`http://127.0.0.1:8000`**
 
---
 
## Struktur Direktori Utama
 
```
alexandria-lbry/
├── app/                  # Core application logic & controllers
├── config/               # Application configuration files
├── database/             # Database schemas, migrations, and seeders
├── public/               # Public web root (CSS, JS, assets, images)
├── resources/            # Views and frontend assets
├── routes/               # Application routing definitions
└── README.md             # Project documentation
```
 
---
 
## Contributing
 
Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are greatly appreciated.
 
1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request
---
 
## Security Vulnerabilities
 
If you discover any security-related issues within Alexandria, please do not open a public issue. Instead, please reach out directly through the repository owner's profile or contact channels. All security vulnerabilities will be addressed promptly.
 
---
 
## Lisensi
 
Aplikasi sistem perpustakaan Alexandria Library ini didistribusikan di bawah naungan [Lisensi MIT](https://opensource.org/licenses/MIT). Anda dibebaskan penuh untuk memodifikasi, mengembangkan, atau menggunakannya kembali demi kebutuhan pembelajaran UKK maupun portofolio individu.
