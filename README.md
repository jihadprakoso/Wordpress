# Wordpress (Jihad Portfolio)

Instalasi WordPress yang dipakai sebagai halaman blog portofolio, memakai theme kustom **Jihad WP Theme** (Bootstrap 5).

## Info Instalasi

| Item | Nilai |
| --- | --- |
| Versi WordPress | 7.0 |
| URL situs | `http://localhost/My_Porto/Wordpress` |
| Database | `db_wordpress_local` |
| Table prefix | `wp_` |
| Theme aktif | `jihad-wp-theme` |
| Plugin | Akismet (belum diaktifkan karena butuh API key) |

## Akun Demo

| Peran | Username | Password |
| --- | --- | --- |
| Administrator | `jihad` | `admin123` |

> Ganti password default ini sebelum dipakai di lingkungan publik.

## Konten Contoh

- 4 artikel blog seputar web development, database, keamanan, dan UI.
- Halaman **Tentang**.
- Menu navigasi **Primary Menu** (Home, Tentang, Blog) yang sudah terhubung ke lokasi menu `primary` theme.
- Permalink memakai format `/%year%/%monthnum%/%postname%/`.

## Theme Kustom: Jihad WP Theme

Berkas theme ada di `wp-content/themes/jihad-wp-theme/`.

| Berkas | Keterangan |
| --- | --- |
| `style.css` | Header deklarasi theme + seluruh gaya kustom. |
| `functions.php` | Enqueue Google Fonts, Bootstrap 5, FontAwesome; dukungan `title-tag`, thumbnail, custom logo; pendaftaran menu dan widget area. |
| `header.php` | Navbar responsif dengan menu dinamis dan tombol pencarian. |
| `footer.php` | Footer tiga kolom (brand, quick links, sosial media). |
| `index.php` | Arsip daftar artikel dalam bentuk kartu. |
| `single.php` | Halaman detail artikel. |
| `page.php` | Halaman statis. |
| `sidebar.php` | Area widget sidebar. |

## Keamanan

- 8 kunci dan salt autentikasi (`AUTH_KEY` dan kawan-kawan) sudah diganti nilai acak unik, tidak lagi memakai placeholder bawaan.
- `.htaccess` di root menolak akses langsung ke `wp-config.php`, `wp-config-sample.php`, `readme.html`, `license.txt`, dan `xmlrpc.php`.
- `Options -Indexes` mematikan directory listing.
- Header keamanan `X-Content-Type-Options`, `X-Frame-Options`, dan `Referrer-Policy` ditambahkan.

## Instalasi Ulang

1. Pastikan MySQL berjalan, lalu buat database kosong:

   ```bash
   mysql -uroot -e "CREATE DATABASE IF NOT EXISTS db_wordpress_local DEFAULT CHARACTER SET utf8mb4;"
   ```

2. Sesuaikan `DB_NAME` di `wp-config.php` bila perlu, lalu buka `http://localhost/My_Porto/Wordpress/` dan ikuti wizard instalasi.
3. Setelah masuk ke dashboard, aktifkan theme **Jihad WP Theme** pada menu Appearance → Themes.
