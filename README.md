# 🚄 KCIC Frequent Whoosher Card (FWC) Admin Panel

Sistem administrasi internal untuk pengelolaan **Frequent Whoosher Card (FWC)** yang digunakan di lingkungan kerja KCIC. Aplikasi ini dikembangkan untuk menghindari data inaccuracy kartu FWC, seperti penggunaan yang melebihi kuota yang telah ditentukan.

## 🎯 Tujuan
Aplikasi ini bertujuan untuk:
- Mempermudah pencatatan dan pemantauan penggunaan kartu FWC.
- Mendeteksi kuota FWC yang sudah habis/expired.
- Mengelola data pengguna dan kuota FWC secara sistematis.

## 🔒 Akses Terbatas
Aplikasi **tidak menyediakan fitur registrasi umum**. Seluruh proses input data dan manajemen dilakukan oleh admin internal. Pengguna yang ingin menggunakan FWC harus menghubungi admin terkait. Ini dilakukan demi menjaga keamanan dan integritas data penggunaan.

## 🧩 Fitur Utama

### 1. 📋 Registrasi FWC
Admin dapat:
- Menambahkan kartu FWC baru ke sistem.
- Memasukkan data penumpang, jenis relasi dan jenis FWC.
- Memastikan validitas dan keunikan data FWC berdasarkan ID.

### 2. 🧾 Redeem FWC
- Validasi otomatis apakah kartu FWC masih memiliki kuota & dalam masa berlaku.
- Pengisian otomatis data penumpang saat input ID FWC.
- Cegah penggunaan berlebih (misalnya, penggunaan ke-11 dari kuota 10).
- Mencatat tanggal penggunaan untuk pelacakan.

### 3. 👥 Manajemen User
- Role-based access: Admin (full access), User (create-read-only).
- Pengelolaan data pengguna internal yang memiliki akses ke sistem.

## ⚙️ Teknologi
- **Backend**: Laravel 12
- **Frontend**: Laravel Breeze + Tailwind CSS + SweetAlert2 (support Dark Mode)
- **Export Data**: PhpSpreadsheet
- **Database**: MySQL

## 🛠️ Cara Menjalankan
1. Clone repo:
   ```bash
   git clone https://github.com/srytmj/kcic-fwc.git
   cd kcic-fwc
   ```

2. Install dependency:
   ```bash
   composer install
   npm install && npm run build
   ```

3. Copy file `.env` dan konfigurasi:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set koneksi database di `.env`, lalu migrasi:
   ```bash
   php artisan migrate
   ```

5. Jalankan server:
   ```bash
   php artisan serve
   ```

6. Login menggunakan akun admin yang telah di migrate. (admin@mail.com/12341234)

## 🙈 Catatan Penutup
Mungkin kodenya masih agak berantakan, tapi yang penting **jalan, berfungsi, dan memenuhi kebutuhan** 😄  
Dibuat dengan penuh semangat, semua request dipenuhi sebisanya. Intinya: yang penting jadi dan berfungsi sesuai kebutuhan kantor 😎  
Kalau ada yang mau nyumbangin PR atau refactor, monggo banget 🙏
