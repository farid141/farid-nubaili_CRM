<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About This Project

Sebuah Website CRM (Customer Relation Management) untuk sebuah perusahaan. Dimana kita dapat menambahkan Customer (Lead) yang bisa diasosiasikan terhadap projek perusahaan. User yang mengoperasikan website ini memiliki level "Manager" atau "Sales".

Dalam sebuah projek, terdapat:

-   Satu customer
-   Satu Manager
-   Satu Sales (yang membuat)
-   Satu atau lebih produk

Beberapa fitur yang yang ada:

-   Halaman login
-   Halaman dan CRUD list calon customer (lead)
-   Halaman dan CRUD master produk (layanan)
-   Halaman dan CRUD project untuk memproses calon customer (lead) disertai approval manager
-   Pembuatan project dengan penghitungan total secara dinamis berdasarkan harga dan jumlah produk yang dibeli.
-   Filter halaman project by sales, manager, lead, status, etc.
-   Halaman customer yang sudah berlangganan disertai list layanannya (bisa melakukan filter di halaman lead)

## Tech Stack

Beberapa resource yang digunakan dalam projek ini:

-   Laravel 11
-   Bootstrap V5.3
-   jQuery
-   XAMPP (PHP 8.2 dan MySQL)
-   Zuramai Template Admin Panel

## Running This Project

-   Clone repository ini
-   jalankan php artisan key:generate
-   setting environment (database) pada file .env
-   jalankan php artisan migrate:seed untuk membuat struktur database dan mengisi data.
-   Untuk login dapat menggunakan salah satu user yang tergenerate dengan password = 'password'
