# Design — SEO & Landing Page Network (tema `nexustech-seo`)

Tanggal: 2026-10-23

## 1. Tujuan

Melengkapi project ke-9 pada portofolio `My_Porto` yang sebelumnya hanya berupa kartu di
`index.html` tanpa folder project. Targetnya: sebuah **tema WordPress kustom** yang
mendemonstrasikan rekayasa SEO dan arsitektur *content network* (pillar ↔ cluster)
secara nyata, tanpa bergantung pada plugin SEO apa pun.

## 2. Keputusan yang disetujui

| Topik | Keputusan |
|---|---|
| Bentuk project | Tema WordPress mandiri (classic PHP, bukan block theme) |
| Isolasi | Instalasi WordPress **kedua**, terpisah total dari `../Wordpress/` |
| Database | `db_nexustech_seo_local` (baru, tidak menumpang `db_wordpress_local`) |
| URL | `http://localhost/My_Porto/SEO-Landing` |
| Niche konten | Agensi digital **NexusTech** (lanjutan cerita project `Static`) |
| Luas konten | 3 landing page + 3 pillar + 9 cluster + kategori/tag/penulis/arsip |
| Stack | Bootstrap 5.3 + Font Awesome 6.4 + font Inter/Outfit (mengikuti `jihad-wp-theme`) |

**Prinsip non-destruktif:** folder `Wordpress/` (tema `jihad-wp-theme`, DB
`db_wordpress_local`) tidak boleh berubah sama sekali. Kedua tema hidup berdampingan
di instalasi masing-masing.

## 3. Arsitektur tema

Folder tema: `SEO-Landing/wp-content/themes/nexustech-seo/`

```
nexustech-seo/
├── style.css                 # header tema + style
├── functions.php             # bootstrap: require inc/*, theme supports
├── header.php footer.php sidebar.php
├── front-page.php            # landing utama NexusTech
├── index.php page.php single.php
├── archive.php category.php tag.php author.php search.php 404.php
├── page-templates/
│   ├── template-landing.php  # "Landing Page (Jasa)" — dipakai 3 halaman layanan
│   └── template-pillar.php   # "Pillar / Hub" — dipakai 3 halaman panduan
├── inc/
│   ├── setup.php             # supports, menu, sidebar, image size
│   ├── enqueue.php           # aset + preconnect font
│   ├── seo-meta.php          # title, description, canonical, OG, Twitter, robots
│   ├── schema.php            # JSON-LD
│   ├── breadcrumbs.php       # breadcrumb visual + BreadcrumbList
│   ├── internal-links.php    # relasi pillar↔cluster + artikel terkait
│   ├── meta-boxes.php        # meta box SEO + relasi pillar + FAQ
│   └── sitemap.php           # /sitemap.xml & /robots.txt virtual
├── template-parts/
│   ├── card-post.php hero.php cta.php faq.php breadcrumb.php
└── assets/
    ├── css/theme.css
    └── js/theme.js
```

### Tanggung jawab modul

- **seo-meta.php** — override `<title>` via `pre_get_document_title`; cetak
  `meta description`, `link canonical`, OG + Twitter Card, dan `robots` (noindex
  untuk hasil pencarian & 404). Deskripsi diambil dari meta box, fallback ke excerpt
  lalu ke potongan konten (maks ±155 karakter).
- **schema.php** — JSON-LD di `wp_head`:
  `Organization` + `WebSite`(SearchAction) di semua halaman; `BlogPosting` di
  `single`; `Service` + `FAQPage` di landing page; `CollectionPage` di pillar;
  `BreadcrumbList` di semua halaman non-front.
- **breadcrumbs.php** — fungsi `nexustech_breadcrumbs()` untuk tampilan; skema
  BreadcrumbList ikut di sini agar konsisten dengan tampilan.
- **internal-links.php** — inti "network": fungsi `nexustech_pillar_of()`,
  `nexustech_clusters_of()`, `nexustech_related_posts()`.
- **sitemap.php** — rewrite rule untuk `/sitemap.xml` (daftar home, pages, posts,
  kategori) dan `/robots.txt` yang menunjuk ke sitemap tersebut.

## 4. Kontrak data (WAJIB dipatuhi bersama oleh tema & seeder)

Meta key (diawali `_` agar tidak tampil di Custom Fields UI):

| Meta key | Tipe | Dipakai di | Arti |
|---|---|---|---|
| `_nexustech_meta_description` | string | page & post | Override meta description |
| `_nexustech_focus_keyword` | string | page & post | Focus keyword (tampil sebagai badge di editor) |
| `_nexustech_pillar` | int (post ID) | **post saja** | Pillar induk dari artikel cluster |
| `_nexustech_faq` | array `[['q'=>..,'a'=>..], ...]` | **page saja** | FAQ → dirender + FAQPage schema |

Nama template halaman (dipakai seeder via `_wp_page_template`):

- `page-templates/template-landing.php`
- `page-templates/template-pillar.php`

## 5. Rencana konten demo

**Halaman**

| Slug | Judul | Template |
|---|---|---|
| `home` | Beranda | `front-page.php` (page_on_front) |
| `blog` | Blog | arsip (page_for_posts) |
| `jasa-web-development` | Jasa Web Development | landing |
| `jasa-seo` | Jasa SEO Profesional | landing |
| `jasa-mobile-app` | Jasa Pembuatan Mobile App | landing |
| `panduan-web-development` | Panduan Lengkap Web Development untuk Bisnis | pillar |
| `panduan-seo` | Panduan SEO 2026: On-Page, Technical & Off-Page | pillar |
| `panduan-mobile-app` | Panduan Membangun Aplikasi Mobile untuk UMKM | pillar |

**Artikel cluster (9)** — masing-masing menunjuk 1 pillar via `_nexustech_pillar`

- Kategori `web-development`: `memilih-stack-frontend-2026`,
  `checklist-keamanan-website`, `biaya-membuat-website-bisnis`
- Kategori `seo`: `riset-kata-kunci-sederhana`, `core-web-vitals-lcp-inp-cls`,
  `struktur-internal-link-seo`
- Kategori `mobile-app`: `native-vs-cross-platform`,
  `estimasi-biaya-aplikasi-mobile`, `tahapan-rilis-aplikasi`

**Taksonomi & navigasi:** 3 kategori di atas, ±9 tag, menu `primary` (6 item, datar)
dan menu `footer` (4 item), halaman penulis otomatis dari author `admin`.

## 6. Alur data "network"

```
Landing page (Service schema)
      │ CTA + daftar panduan
      ▼
Pillar page ──── otomatis mendaftar semua cluster-nya (query _nexustech_pillar)
      ▲
      │ _nexustech_pillar
Cluster post ──── blok "Bagian dari panduan" + "Artikel terkait" (kategori sama)
```

## 7. Verifikasi

Semua URL mengembalikan HTTP 200:
`/`, `/blog/`, 3 landing, 3 pillar, 9 artikel, 1 kategori, 1 tag, author, `sitemap.xml`,
`robots.txt`, dan `404` mengembalikan HTTP 404 dengan halaman kustom.
Tambahan: `php -l` bersih untuk semua file PHP tema, JSON-LD ter-parse sebagai JSON
valid, dan `../Wordpress/` tetap 200 tanpa perubahan.

## 8. Cara membuat ulang demo

```
/Applications/XAMPP/xamppfiles/bin/php tools/setup-site.php
/Applications/XAMPP/xamppfiles/bin/php tools/seed-demo.php
```

Keduanya idempoten: seeder memeriksa dulu apakah slug sudah ada
(`get_page_by_path($slug, OBJECT, 'post'|'page')`) sebelum membuat data baru,
sehingga aman dijalankan berulang.

## 9. Di luar cakupan (YAGNI)

Tanpa plugin SEO, tanpa WooCommerce, tanpa block theme/FSE, tanpa multi-bahasa,
tanpa integrasi Google Analytics nyata (hanya placeholder dokumentasi).
