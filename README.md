## About

Aplikasi Ecommerce ini dibuat saya untuk skripsi saya. dengan bantuan midtrans sebagai payment gateway nya dan rajaongkir sebagai cek ongkir. mungkin masih banyak bug atau error. silakakn gunakan php versi 8++.

## Pre

1. Silakan daftar ke akun midtrans terlebih dahulu sebagai metode pembayaran, jika sudah silakan edit file App/Data/MidtransData. silakan edit beberapa yang di butuhkan.
2. Silakan daftar ke akun rajaongkir sebagai jasa ongkir. jika sudah silakan edit file App/Data/RajaongkirData. Silakan edit beberapa yang di butuhkan.
3. Silakan bikin kode buat email di gmail. karena untuk melakukan pendaftar pelanggan nanti akan ada notifikasi ke email pelanggan.
4. Silakan setup .env yang dibutuhkan... seperti database name, email,app url, dll
5. Jika sudah dan ingin menggunakan data dummy .. silakan buka gitbash,dll "php artisan migrate:fresh --seed"

### Library

Library yang daya tambahkan dan Silakan download

- **[PHP-JWT](https://github.com/firebase/php-jwt)**
- **[Midtrans-PHP](https://github.com/Midtrans/midtrans-php)**

jika ada eror ... silakan mail saya ke samidnatlus@gmail.com

## Tech

1. Laravel9 => php 8.1
2. bootstrap
3. JWT

note : JWT ini saya hanya coba dan implementasi mungkin kurang tepat dalam penggunaannya. auth untuk login masih manual tidak pakai bawaan laravel karena saya mau mencoba dulu yang manual.

