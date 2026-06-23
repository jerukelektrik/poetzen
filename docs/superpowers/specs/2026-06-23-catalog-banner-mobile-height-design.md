# Catalog Banner Mobile Height Design

## Tujuan

Meningkatkan tinggi banner Katalog Terbitan pada homepage versi mobile agar teks dan visual banner lebih mudah dibaca.

## Desain

- Banner menggunakan rasio `4 / 1` pada viewport di bawah 48rem.
- Gambar memenuhi area banner dengan `object-fit: cover` dan posisi tengah.
- Radius, border, shadow, dots, dan fungsi slider tetap mengikuti komponen saat ini.
- Tombol navigasi tetap berada di tengah vertikal.
- Tablet dan desktop tetap memakai rasio `1200 / 150`.
- Aturan berlaku konsisten untuk satu banner maupun slider dengan beberapa banner.

## Implementasi

- Tambahkan class pembatas yang sama pada wrapper banner tunggal dan slider di `front-page.php`.
- Pindahkan rasio inline slider ke CSS agar dapat dioverride secara responsive.
- Tambahkan aturan rasio desktop dan mobile di `src/input.css`.
- Build ulang `assets/css/theme.css`.

## Verifikasi

- Pada lebar konten 343px di viewport 393px, tinggi banner 86px.
- Gambar tidak gepeng dan isi banner tidak terpotong secara berlebihan.
- Slider, tombol panah, dan dots tetap berfungsi.
- Pada desktop, banner tetap memiliki rasio 8:1.
- Tidak ada overflow horizontal atau layout shift pada section Katalog Terbitan.
