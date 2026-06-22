# Peristiwa Mobile List Design

## Tujuan

Mengubah section **Peristiwa Suku Sastra** di homepage versi mobile dari carousel horizontal menjadi list vertikal yang lebih mudah dipindai dan tidak mengganggu gesture scroll halaman.

## Ruang Lingkup

- Perubahan hanya berlaku pada viewport mobile, di bawah breakpoint `md` atau 48rem.
- Tampilan tablet dan desktop tetap menggunakan grid tiga kolom yang sudah ada.
- Query tetap menampilkan tiga post Peristiwa terbaru.
- Template `template-parts/cards/news-card.php` tetap menjadi sumber markup dan data kartu.

## Layout Mobile

Setiap post menjadi satu baris dengan komposisi:

- Kolom kiri fleksibel berisi kategori, judul, nama penulis, dan tanggal.
- Kolom kanan berisi thumbnail persegi berukuran 92px.
- Excerpt dan ikon panah diagonal disembunyikan.
- Setiap baris dipisahkan garis tipis.
- Carousel, horizontal overflow, drag scrolling, dan scroll snap dinonaktifkan pada mobile.

Urutan informasi:

1. Kategori `Peristiwa`.
2. Judul post.
3. Nama penulis dan tanggal pada baris metadata ringkas.
4. Thumbnail di sisi kanan.

## Styling

- Judul menggunakan Inter dengan ukuran `1rem` dan line-height `1.32`.
- Metadata menggunakan warna slate yang lebih tenang agar tidak bersaing dengan judul.
- Label kategori menggunakan crimson brand.
- Thumbnail memakai rasio `1:1`, `object-fit: cover`, dan radius 10px.
- Spasi vertikal antar-item cukup untuk sentuhan, tanpa kartu atau shadow tambahan.
- Dark mode mengikuti token warna yang sudah digunakan theme.

## Implementasi

- Tambahkan class khusus pada container dan item section Peristiwa di `front-page.php`.
- Tambahkan aturan responsive di `src/input.css`.
- Jangan mengubah `news-card.php` secara struktural; target elemen yang sudah ada melalui class section agar perubahan tidak memengaruhi penggunaan kartu di halaman lain.
- Build ulang `assets/css/theme.css`.

## Verifikasi

- Mobile 390px dan 430px: tiga post tampil sebagai list vertikal tanpa horizontal overflow.
- Scroll vertikal tidak tertahan oleh gesture carousel.
- Judul panjang membungkus dengan rapi dan tidak bertabrakan dengan thumbnail.
- Link gambar dan judul tetap dapat diklik.
- Tablet dan desktop tetap tampil dalam grid tiga kolom.
- PHP lint dan build CSS berhasil.
