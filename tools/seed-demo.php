<?php
/**
 * Seeder konten demo untuk project "SEO & Landing Page Network" (tema nexustech-seo).
 *
 * Membuat: halaman (beranda, blog, 3 landing, 3 pillar), 9 artikel cluster,
 * kategori, tag, dan menu navigasi. Mengikuti kontrak meta key pada
 * docs/DESIGN.md (Bagian 4) dan rencana konten (Bagian 5).
 *
 * Sifat: idempoten — setiap entitas diperiksa keberadaannya berdasarkan slug
 * sebelum dibuat, sehingga aman dijalankan berulang.
 *
 * Jalankan:
 *   /Applications/XAMPP/xamppfiles/bin/php tools/seed-demo.php
 *
 * @package nexustech-seo
 */

if ( PHP_SAPI !== 'cli' ) {
	http_response_code( 403 );
	exit( 'Skrip ini hanya boleh dijalankan dari command line.' );
}

require_once dirname( __DIR__ ) . '/wp-load.php';

// ------------------------------------------------------------------
// Helper
// ------------------------------------------------------------------

/**
 * Cetak pesan progress.
 *
 * @param string $message Pesan.
 * @return void
 */
function nexustech_seed_log( $message ) {
	echo $message . "\n";
}

/**
 * Ambil post/page berdasarkan slug.
 *
 * @param string $slug Slug.
 * @param string $type 'post'|'page'.
 * @return WP_Post|null
 */
function nexustech_seed_find( $slug, $type ) {
	$found = get_page_by_path( $slug, OBJECT, $type );

	return $found instanceof WP_Post ? $found : null;
}

/**
 * Buat atau perbarui post/page (upsert berdasarkan slug).
 *
 * @param array $data Data post: slug, type, title, content, excerpt, template, menu_order.
 * @return int ID post.
 */
function nexustech_seed_post( array $data ) {
	$existing = nexustech_seed_find( $data['slug'], $data['type'] );

	$args = array(
		'post_type'    => $data['type'],
		'post_title'   => $data['title'],
		'post_name'    => $data['slug'],
		'post_content' => $data['content'],
		'post_excerpt' => isset( $data['excerpt'] ) ? $data['excerpt'] : '',
		'post_status'  => 'publish',
		'post_author'  => isset( $data['author'] ) ? (int) $data['author'] : 1,
		'menu_order'   => isset( $data['menu_order'] ) ? (int) $data['menu_order'] : 0,
	);

	if ( isset( $data['date'] ) ) {
		$args['post_date']     = $data['date'];
		$args['post_date_gmt'] = get_gmt_from_date( $data['date'] );
	}

	if ( $existing instanceof WP_Post ) {
		$args['ID'] = $existing->ID;
		$post_id    = wp_update_post( $args, true );
		$action     = 'diperbarui';
	} else {
		$post_id = wp_insert_post( $args, true );
		$action  = 'dibuat';
	}

	if ( is_wp_error( $post_id ) ) {
		nexustech_seed_log( '  GAGAL ' . $data['slug'] . ': ' . $post_id->get_error_message() );

		return 0;
	}

	nexustech_seed_log( '  - ' . ucfirst( $data['type'] ) . ' ' . $action . ': ' . $data['slug'] . ' (#' . $post_id . ')' );

	return (int) $post_id;
}

/**
 * Simpan meta kontrak (meta description + focus keyword + optional lainnya).
 *
 * @param int   $post_id ID post.
 * @param array $meta    Pasangan meta_key => value.
 * @return void
 */
function nexustech_seed_meta( $post_id, array $meta ) {
	foreach ( $meta as $key => $value ) {
		if ( null === $value || '' === $value || array() === $value ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}
}

/**
 * Buat term bila belum ada.
 *
 * @param string $name     Nama term.
 * @param string $taxonomy 'category'|'post_tag'.
 * @return int term_id (0 bila gagal).
 */
function nexustech_seed_term( $name, $taxonomy ) {
	$slug = sanitize_title( $name );
	$term = get_term_by( 'slug', $slug, $taxonomy );

	if ( $term instanceof WP_Term ) {
		return (int) $term->term_id;
	}

	$result = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );

	if ( is_wp_error( $result ) ) {
		$term = get_term_by( 'name', $name, $taxonomy );

		return $term instanceof WP_Term ? (int) $term->term_id : 0;
	}

	return (int) $result['term_id'];
}

// ------------------------------------------------------------------
// 0. Prasyarat
// ------------------------------------------------------------------

if ( ! is_blog_installed() ) {
	exit( "WordPress belum terinstal. Jalankan dulu: php tools/setup-site.php\n" );
}

$nexustech_author = 1;
$nexustech_now    = current_time( 'mysql' );

nexustech_seed_log( '== Seeding demo NexusTech SEO Network ==' );

// ------------------------------------------------------------------
// 1. Kategori & tag
// ------------------------------------------------------------------

$nexustech_categories = array(
	'web-development' => 'Web Development',
	'seo'             => 'SEO',
	'mobile-app'      => 'Mobile App',
);

$nexustech_category_ids = array();
foreach ( $nexustech_categories as $slug => $name ) {
	$nexustech_category_ids[ $slug ] = nexustech_seed_term( $name, 'category' );
}

$nexustech_tag_ids = array();
foreach ( array(
	'html-css',
	'javascript',
	'laravel',
	'wordpress',
	'core-web-vitals',
	'keyword-research',
	'internal-link',
	'flutter',
	'play-store',
	'umkm',
) as $nexustech_tag ) {
	$nexustech_tag_ids[ $nexustech_tag ] = nexustech_seed_term( $nexustech_tag, 'post_tag' );
}

nexustech_seed_log( 'Kategori: ' . count( $nexustech_category_ids ) . ', tag: ' . count( $nexustech_tag_ids ) );

// ------------------------------------------------------------------
// 2. Halaman
// ------------------------------------------------------------------

$nexustech_home_id = nexustech_seed_post(
	array(
		'slug'       => 'home',
		'type'       => 'page',
		'title'      => 'Beranda',
		'menu_order' => 1,
		'excerpt'    => 'NexusTech membantu bisnis tumbuh lewat website cepat, SEO yang terukur, dan aplikasi mobile yang siap pakai.',
		'content'    => <<<'HTML'
<p>NexusTech adalah agensi digital yang fokus pada tiga hal: membangun website yang cepat, mengoptimalkan visibilitas di mesin pencari, dan merancang aplikasi mobile yang benar-benar dipakai pengguna.</p>
<p>Kami bekerja dengan pendekatan yang terukur: setiap keputusan teknis punya alasan, setiap halaman punya tujuan, dan setiap hasil bisa diverifikasi.</p>
HTML,
	)
);

$nexustech_blog_id = nexustech_seed_post(
	array(
		'slug'       => 'blog',
		'type'       => 'page',
		'title'      => 'Blog',
		'menu_order' => 2,
		'excerpt'    => 'Catatan praktis seputar web development, SEO, dan pengembangan aplikasi mobile.',
		'content'    => <<<'HTML'
<p>Kumpulan artikel praktis dari tim NexusTech. Semua tulisan dikelompokkan ke dalam panduan induk (pillar) agar mudah ditelusuri sesuai kebutuhan.</p>
HTML,
	)
);

nexustech_seed_meta( $nexustech_home_id, array( '_nexustech_meta_description' => 'NexusTech — agensi digital untuk web development, SEO, dan aplikasi mobile. Bangun kehadiran digital yang cepat, terukur, dan siap tumbuh.' ) );
nexustech_seed_meta( $nexustech_blog_id, array( '_nexustech_meta_description' => 'Artikel dan panduan praktis seputar web development, SEO teknis, dan pengembangan aplikasi mobile dari tim NexusTech.' ) );

// --- 3 landing page ---

$nexustech_landing_id = nexustech_seed_post(
	array(
		'slug'       => 'jasa-web-development',
		'type'       => 'page',
		'title'      => 'Jasa Web Development',
		'menu_order' => 3,
		'excerpt'    => 'Website cepat, aman, dan mudah dikelola — dibangun dari fondasi teknis yang benar.',
		'content'    => <<<'HTML'
<p>Website bisnis yang baik bukan sekadar tampilan yang enak dilihat. Ia harus cepat dimuat, aman, mudah diperbarui, dan siap menangkap peluang dari mesin pencari serta iklan.</p>

<h2>Mengapa website Anda butuh lebih dari sekadar tampilan menarik</h2>
<p>Banyak website kehilangan calon pelanggan di tiga titik: waktu muat yang terlalu lama, struktur halaman yang sulit dipahami mesin pencari, dan alur kontak yang membingungkan. Kami membangun website dengan ketiga hal tersebut sebagai prioritas sejak baris kode pertama.</p>

<h2>Yang Anda dapatkan</h2>
<ul>
	<li>Struktur halaman dan navigasi yang direncanakan berdasarkan perjalanan pengguna.</li>
	<li>Kode semantik dengan meta data, canonical, dan structured data yang rapi sejak awal.</li>
	<li>Skor performa yang dijaga lewat optimasi gambar, caching, dan pemuatan aset yang efisien.</li>
	<li>Panel admin yang bisa Anda kelola sendiri tanpa perlu menyentuh kode.</li>
	<li>Laporan serah terima berisi cara deploy, backup, dan pemeliharaan rutin.</li>
</ul>

<h2>Proses kerja</h2>
<ol>
	<li><strong>Discovery.</strong> Kami memetakan tujuan bisnis, audiens, dan kompetitor Anda.</li>
	<li><strong>Arsitektur.</strong> Struktur halaman, hierarki URL, dan skema konten disusun sebelum desain.</li>
	<li><strong>Build.</strong> Implementasi frontend dan backend dengan standar aksesibilitas serta keamanan.</li>
	<li><strong>Launch &amp; ukur.</strong> Peluncuran disertai pemantauan performa dan analitik.</li>
</ol>

<h2>Teknologi yang kami pakai</h2>
<p>Untuk company profile dan landing page kami sering memakai WordPress dengan tema kustom. Untuk kebutuhan aplikasi web internal, kami menggunakan Laravel atau PHP murni dengan PDO, selalu disertai prepared statement dan transaksi database yang aman.</p>
HTML,
	)
);

$nexustech_seo_landing_id = nexustech_seed_post(
	array(
		'slug'       => 'jasa-seo',
		'type'       => 'page',
		'title'      => 'Jasa SEO Profesional',
		'menu_order' => 4,
		'excerpt'    => 'SEO teknis, on-page, dan konten yang saling menopang untuk pertumbuhan organik jangka panjang.',
		'content'    => <<<'HTML'
<p>SEO bukan trik agar halaman muncul sebentar di halaman pertama. SEO adalah fondasi: struktur teknis yang sehat, konten yang menjawab niat pencarian, dan otoritas yang dibangun konsisten.</p>

<h2>Kenapa banyak website gagal di halaman kedua</h2>
<p>Penyebabnya biasanya bukan kurang konten, melainkan konten yang tidak terhubung. Setiap artikel berdiri sendiri tanpa halaman induk, tanpa tautan internal yang jelas, dan tanpa satu pun kata kunci fokus. Kami membalik pendekatan itu dengan arsitektur content network.</p>

<h2>Lingkup pekerjaan SEO kami</h2>
<ul>
	<li><strong>Technical SEO.</strong> Crawlability, sitemap XML, robots.txt, canonical, data terstruktur, dan Core Web Vitals.</li>
	<li><strong>On-page SEO.</strong> Riset kata kunci, judul, meta description, hierarki heading, serta tautan internal.</li>
	<li><strong>Content network.</strong> Halaman pillar sebagai pusat, artikel cluster sebagai pendukung yang saling menautkan.</li>
	<li><strong>Off-page.</strong> Konsistensi NAP, profil bisnis lokal, dan strategi tautan yang wajar.</li>
	<li><strong>Pengukuran.</strong> Pemantauan posisi, klik, impresi, dan konversi dari pencarian organik.</li>
</ul>

<h2>Tahapan audit hingga eksekusi</h2>
<ol>
	<li>Audit teknis dan inventaris konten yang sudah ada.</li>
	<li>Prioritisasi temuan berdasarkan dampak dan usaha.</li>
	<li>Perbaikan teknis lebih dulu, baru ekspansi konten.</li>
	<li>Pelaporan bulanan beserta rekomendasi berikutnya.</li>
</ol>

<h2>Yang bisa Anda ukur</h2>
<p>Kami tidak menjanjikan peringkat nomor satu. Kami menjanjikan proses yang transparan, perbaikan yang bisa diverifikasi satu per satu, dan laporan yang menunjukkan ke mana anggaran Anda pergi.</p>
HTML,
	)
);

$nexustech_mobile_landing_id = nexustech_seed_post(
	array(
		'slug'       => 'jasa-mobile-app',
		'type'       => 'page',
		'title'      => 'Jasa Pembuatan Mobile App',
		'menu_order' => 5,
		'excerpt'    => 'Dari ide ke aplikasi siap rilis di Play Store dan App Store — dengan alur kerja yang jelas.',
		'content'    => <<<'HTML'
<p>Aplikasi mobile yang berhasil dimulai dari lingkup yang tepat, bukan dari fitur yang paling banyak. Kami membantu Anda memangkas ide menjadi versi pertama yang layak dirilis, lalu mengembangkannya berdasarkan umpan balik nyata.</p>

<h2>Fitur yang benar-benar dipakai pengguna</h2>
<p>Kami memulai dengan memetakan satu alur utama yang paling bernilai, lalu membangun sisanya di sekitar alur tersebut. Hasilnya aplikasi yang lebih cepat selesai dan lebih mudah dievaluasi.</p>

<h2>Cakupan pekerjaan</h2>
<ul>
	<li>Rancangan alur pengguna dan prototipe yang bisa diklik sebelum masuk ke kode.</li>
	<li>Implementasi antarmuka yang konsisten di Android dan iOS.</li>
	<li>Integrasi API, autentikasi, penyimpanan lokal, dan notifikasi.</li>
	<li>Pengujian pada berbagai ukuran layar dan kondisi jaringan.</li>
	<li>Pendampingan proses publikasi ke Google Play Store dan Apple App Store.</li>
</ul>

<h2>Tahapan pengerjaan</h2>
<ol>
	<li><strong>Discovery &amp; scope.</strong> Menetapkan tujuan, pengguna, dan batasan rilis pertama.</li>
	<li><strong>Desain.</strong> Wireframe hingga antarmuka final yang siap dieksekusi.</li>
	<li><strong>Pengembangan.</strong> Sprint dua minggu dengan build yang bisa Anda coba langsung.</li>
	<li><strong>Rilis.</strong> Publikasi ke store, pemantauan crash, dan iterasi lanjutan.</li>
</ol>
HTML,
	)
);

nexustech_seed_meta(
	$nexustech_landing_id,
	array(
		'_nexustech_meta_description' => 'Jasa web development untuk bisnis: website cepat, aman, SEO-ready, dan mudah dikelola sendiri. Konsultasi gratis bersama NexusTech.',
		'_nexustech_focus_keyword'    => 'jasa web development',
		'_nexustech_faq'              => array(
			array(
				'q' => 'Berapa lama proses pembuatan website?',
				'a' => 'Untuk company profile atau landing page, umumnya 2-4 minggu sejak material konten lengkap. Website dengan integrasi sistem atau katalog besar bisa lebih lama.',
			),
			array(
				'q' => 'Apakah saya bisa memperbarui konten sendiri?',
				'a' => 'Bisa. Kami membangun panel admin yang mudah dipakai dan menyertakan sesi serah terima singkat beserta dokumentasi cara mengelola konten.',
			),
			array(
				'q' => 'Apakah termasuk pemeliharaan setelah rilis?',
				'a' => 'Pemeliharaan bersifat opsional. Anda bisa memilih paket pemantauan rutin untuk pembaruan keamanan, backup, dan pemantauan performa.',
			),
		),
	)
);

nexustech_seed_meta(
	$nexustech_seo_landing_id,
	array(
		'_nexustech_meta_description' => 'Jasa SEO profesional: audit teknis, optimasi on-page, dan arsitektur content network yang terukur. Tingkatkan trafik organik bisnis Anda.',
		'_nexustech_focus_keyword'    => 'jasa seo profesional',
		'_nexustech_faq'              => array(
			array(
				'q' => 'Berapa lama hasil SEO mulai terlihat?',
				'a' => 'Perbaikan teknis biasanya berdampak dalam 2-6 minggu. Pertumbuhan dari konten dan tautan umumnya baru konsisten setelah 3-6 bulan.',
			),
			array(
				'q' => 'Apakah perlu mengganti website yang sudah ada?',
				'a' => 'Belum tentu. Kami melakukan audit dulu; sering kali perbaikan struktur dan konten pada website yang ada sudah cukup untuk mendorong pertumbuhan.',
			),
			array(
				'q' => 'Bagaimana cara mengukur keberhasilan?',
				'a' => 'Kami memantau klik dan impresi dari pencarian organik, posisi kata kunci target, serta jumlah konversi yang berasal dari trafik organik.',
			),
		),
	)
);

nexustech_seed_meta(
	$nexustech_mobile_landing_id,
	array(
		'_nexustech_meta_description' => 'Jasa pembuatan aplikasi mobile untuk bisnis dan UMKM: desain, pengembangan, hingga rilis ke Play Store dan App Store. Mulai dari lingkup rilis pertama.',
		'_nexustech_focus_keyword'    => 'jasa pembuatan aplikasi mobile',
		'_nexustech_faq'              => array(
			array(
				'q' => 'Sebaiknya native atau cross-platform?',
				'a' => 'Untuk sebagian besar kebutuhan bisnis, cross-platform memberi waktu rilis lebih cepat dan biaya lebih efisien. Native lebih tepat bila aplikasi butuh akses berat ke fitur perangkat.',
			),
			array(
				'q' => 'Berapa estimasi biaya pembuatan aplikasi?',
				'a' => 'Biaya ditentukan oleh jumlah alur utama, integrasi backend, dan kebutuhan desain. Setelah sesi discovery, kami memberi rincian per modul.',
			),
			array(
				'q' => 'Apakah bisa rilis ke dua platform sekaligus?',
				'a' => 'Bisa. Kami membantu proses publikasi ke Google Play Store dan Apple App Store, termasuk penyiapan materi dan penanganan catatan peninjauan.',
			),
		),
	)
);

// --- 3 pillar ---

$nexustech_pillar_web_id = nexustech_seed_post(
	array(
		'slug'       => 'panduan-web-development',
		'type'       => 'page',
		'title'      => 'Panduan Lengkap Web Development untuk Bisnis',
		'menu_order' => 10,
		'excerpt'    => 'Fondasi, frontend, backend, keamanan, dan peluncuran website bisnis — dijelaskan bertahap.',
		'content'    => <<<'HTML'
<p>Halaman ini adalah pusat pembelajaran web development untuk kebutuhan bisnis. Semua artikel pendukung di bawahnya membahas satu topik secara mendalam, sementara halaman ini menyatukan semuanya dalam satu kerangka kerja.</p>

<h2>1. Fondasi sebelum menulis kode</h2>
<p>Sebelum memilih teknologi, tentukan dulu tujuan bisnisnya. Apakah website dipakai untuk membangun kredibilitas, menghasilkan prospek, atau menjual langsung? Jawaban ini menentukan struktur halaman, jenis konten, dan tingkat integrasi yang dibutuhkan.</p>

<h2>2. Memilih stack frontend</h2>
<p>Stack frontend menentukan pengalaman pengguna dan kecepatan halaman. Untuk website konten, HTML semantik dengan CSS modern biasanya lebih tepat daripada aplikasi berlapis JavaScript. Untuk dasbor interaktif, komponen berbasis JavaScript baru masuk akal.</p>

<h2>3. Backend dan pengelolaan data</h2>
<p>Gunakan prepared statement untuk semua query, terapkan transaksi saat mengubah beberapa tabel sekaligus, dan simpan kata sandi dengan hashing bcrypt. Hal-hal ini bukan fitur tambahan, melainkan syarat minimum.</p>

<h2>4. Keamanan sebagai kebiasaan</h2>
<p>Perlindungan CSRF, validasi input di sisi server, pembatasan akses berkas sensitif, dan pembaruan dependensi secara rutin adalah lapisan-lapisian yang saling melengkapi. Tidak ada satu lapisan yang cukup sendirian.</p>

<h2>5. Peluncuran dan pemeliharaan</h2>
<p>Website yang selesai tidak berarti selesai. Setelah rilis, yang perlu dijaga adalah kecepatan, ketersediaan, dan keamanan. Siapkan backup otomatis dan pantau performa secara berkala.</p>
HTML,
	)
);

$nexustech_pillar_seo_id = nexustech_seed_post(
	array(
		'slug'       => 'panduan-seo',
		'type'       => 'page',
		'title'      => 'Panduan SEO 2026: On-Page, Technical & Off-Page',
		'menu_order' => 11,
		'excerpt'    => 'Kerangka kerja SEO yang bisa dikerjakan bertahap: on-page, technical, off-page, dan pengukuran.',
		'content'    => <<<'HTML'
<p>SEO yang berhasil bukan hasil dari satu taktik, melainkan kombinasi antara struktur teknis yang sehat, konten yang relevan, dan otoritas yang tumbuh. Panduan ini menyatukan ketiganya ke dalam urutan kerja yang praktis.</p>

<h2>On-page: membuat halaman mudah dipahami</h2>
<p>Setiap halaman sebaiknya menargetkan satu kata kunci utama. Tempatkan kata kunci itu secara alami pada judul, paragraf pembuka, satu subjudul, dan meta description. Hindari pengulangan berlebihan yang justru membuat bacaan terasa janggal.</p>

<h2>Technical: memastikan mesin pencari bisa mengakses</h2>
<p>Periksa indexability, sitemap XML, robots.txt, canonical, dan data terstruktur. Tekankan juga pada Core Web Vitals: LCP, INP, dan CLS. Perbaikan teknis biasanya memberi hasil lebih cepat dibandingkan menambah artikel baru.</p>

<h2>Off-page: membangun kepercayaan</h2>
<p>Konsistensi nama, alamat, dan nomor telepon di seluruh direktori lokal membantu bisnis lokal muncul di hasil pencarian. Tautan berkualitas dari sumber relevan lebih bernilai daripada banyak tautan tidak relevan.</p>

<h2>Pengukuran: menilai apa yang benar-benar berubah</h2>
<p>Pantau klik dan impresi dari pencarian organik, posisi kata kunci target, serta konversi yang berasal dari trafik tersebut. Tanpa pengukuran, perbaikan berikutnya akan didasarkan pada dugaan.</p>
HTML,
	)
);

$nexustech_pillar_mobile_id = nexustech_seed_post(
	array(
		'slug'       => 'panduan-mobile-app',
		'type'       => 'page',
		'title'      => 'Panduan Membangun Aplikasi Mobile untuk UMKM',
		'menu_order' => 12,
		'excerpt'    => 'Dari memilih platform hingga rilis ke store: langkah praktis membangun aplikasi mobile untuk usaha kecil.',
		'content'    => <<<'HTML'
<p>Banyak usaha kecil ingin punya aplikasi, tetapi tidak semua benar-benar membutuhkannya. Panduan ini membantu memutuskan kapan aplikasi mobile masuk akal, bagaimana memulainya dengan lingkup kecil, dan apa yang perlu disiapkan menjelang rilis.</p>

<h2>Memilih platform</h2>
<p>Pilihan pertama biasanya antara native dan cross-platform. Native memberi akses paling dalam ke fitur perangkat, sedangkan cross-platform mempercepat rilis ke dua platform sekaligus dengan satu basis kode.</p>

<h2>Merancang alur yang sederhana</h2>
<p>Pengguna mobile memiliki perhatian yang pendek. Rancang satu alur utama yang bisa diselesaikan dalam beberapa langkah, lalu letakkan fitur tambahan di tempat yang tidak menghalangi alur tersebut.</p>

<h2>Backend dan integrasi API</h2>
<p>Aplikasi tanpa backend sering kali berhenti sebagai prototipe. Rencanakan sejak awal bagaimana data disimpan, bagaimana pengguna masuk, dan bagaimana aplikasi menangani koneksi yang tidak stabil.</p>

<h2>Rilis ke store</h2>
<p>Google Play Store dan Apple App Store punya syarat berbeda. Siapkan deskripsi, tangkapan layar, kebijakan privasi, dan pastikan tidak ada tautan keluar yang memicu penolakan peninjauan.</p>
HTML,
	)
);

nexustech_seed_meta(
	$nexustech_pillar_web_id,
	array(
		'_nexustech_meta_description' => 'Panduan lengkap web development untuk bisnis: memilih stack, membangun backend yang aman, dan menyiapkan peluncuran website.',
		'_nexustech_focus_keyword'    => 'panduan web development',
	)
);

nexustech_seed_meta(
	$nexustech_pillar_seo_id,
	array(
		'_nexustech_meta_description' => 'Panduan SEO 2026 yang membahas on-page, technical SEO, off-page, serta cara mengukur hasil secara realistis.',
		'_nexustech_focus_keyword'    => 'panduan seo',
	)
);

nexustech_seed_meta(
	$nexustech_pillar_mobile_id,
	array(
		'_nexustech_meta_description' => 'Panduan membangun aplikasi mobile untuk UMKM: memilih platform, merancang alur, menyiapkan backend, dan rilis ke store.',
		'_nexustech_focus_keyword'    => 'panduan aplikasi mobile',
	)
);

// Tetapkan template halaman sesuai kontrak docs/DESIGN.md.
foreach ( array( $nexustech_landing_id, $nexustech_seo_landing_id, $nexustech_mobile_landing_id ) as $nexustech_id ) {
	update_post_meta( $nexustech_id, '_wp_page_template', 'page-templates/template-landing.php' );
}

foreach ( array( $nexustech_pillar_web_id, $nexustech_pillar_seo_id, $nexustech_pillar_mobile_id ) as $nexustech_id ) {
	update_post_meta( $nexustech_id, '_wp_page_template', 'page-templates/template-pillar.php' );
}

// ------------------------------------------------------------------
// 3. Artikel cluster (9)
// ------------------------------------------------------------------

$nexustech_posts = array(
	array(
		'slug'       => 'memilih-stack-frontend-2026',
		'title'      => 'Cara Memilih Stack Frontend di 2026 Tanpa Terjebak Tren',
		'category'   => 'web-development',
		'tags'       => array( 'html-css', 'javascript' ),
		'pillar'     => $nexustech_pillar_web_id,
		'keyword'    => 'memilih stack frontend',
		'description' => 'Panduan memilih stack frontend berdasarkan jenis proyek, ukuran tim, dan kebutuhan jangka panjang, bukan sekadar tren.',
		'excerpt'    => 'Kerangka sederhana untuk menentukan apakah proyek Anda cukup memakai HTML dan CSS, atau benar-benar butuh framework JavaScript.',
		'content'    => <<<'HTML'
<p>Pertanyaan "framework apa yang terbaik" hampir selalu salah tempat. Yang lebih berguna: stack apa yang paling cocok untuk jenis proyek, ukuran tim, dan siklus pemeliharaan Anda.</p>

<h2>Mulai dari jenis proyeknya</h2>
<p>Website konten seperti company profile, blog, dan landing page hampir selalu lebih cepat dan lebih murah dibangun dengan HTML semantik dan CSS modern. Framework JavaScript baru menjadi masuk akal ketika halaman punya banyak state, interaksi real-time, atau data yang sering berubah tanpa reload.</p>

<h2>Pertimbangkan siapa yang merawatnya</h2>
<p>Stack yang canggih tapi tidak dipahami tim yang merawatnya akan menjadi utang teknis. Jika tidak ada developer frontend khusus, pilih pendekatan yang paling sederhana yang masih memenuhi kebutuhan.</p>

<h2>Perhatikan biaya tersembunyi</h2>
<ul>
	<li>Ukuran bundle yang membengkak dan berdampak ke Core Web Vitals.</li>
	<li>Waktu build dan kompleksitas pipeline deploy.</li>
	<li>Biaya pembaruan rutin ketika versi mayor berubah.</li>
</ul>

<h2>Kerangka keputusan singkat</h2>
<ol>
	<li>Konten statis atau sedikit interaksi? Gunakan HTML dan CSS.</li>
	<li>Ada komponen interaktif berulang? Tambahkan JavaScript modular.</li>
	<li>Aplikasi dengan banyak state dan data dinamis? Framework JavaScript baru layak dipertimbangkan.</li>
</ol>
HTML,
	),
	array(
		'slug'       => 'checklist-keamanan-website',
		'title'      => 'Checklist Keamanan Website yang Sering Terlupakan',
		'category'   => 'web-development',
		'tags'       => array( 'laravel', 'wordpress' ),
		'pillar'     => $nexustech_pillar_web_id,
		'keyword'    => 'keamanan website',
		'description' => 'Checklist keamanan website praktis: CSRF, prepared statement, hashing password, proteksi berkas sensitif, dan pembaruan rutin.',
		'excerpt'    => 'Enam lapisan keamanan dasar yang seharusnya ada di setiap website bisnis, beserta cara memeriksanya.',
		'content'    => <<<'HTML'
<p>Sebagian besar insiden keamanan website bukan berasal dari serangan canggih, melainkan dari hal-hal dasar yang terlewat. Berikut checklist yang bisa Anda periksa sendiri.</p>

<h2>1. Prepared statement untuk semua query</h2>
<p>Jangan pernah menyusun query dengan menggabungkan string dari input pengguna. Gunakan prepared statement atau query builder yang menangani escaping secara otomatis.</p>

<h2>2. Hashing kata sandi dengan bcrypt</h2>
<p>Simpan kata sandi hanya dalam bentuk hash bcrypt, dan verifikasi dengan fungsi pembanding khusus. Hindari hash cepat seperti MD5 atau SHA-1 untuk kata sandi.</p>

<h2>3. Proteksi CSRF pada semua form</h2>
<p>Setiap form yang mengubah data harus menyertakan token CSRF, termasuk aksi logout. Logout yang dijalankan lewat GET bisa dipicu dari situs lain tanpa sepengetahuan pengguna.</p>

<h2>4. Validasi dan escaping output</h2>
<p>Validasi input di sisi server, lalu escape seluruh output sebelum ditampilkan ke HTML. Keduanya berbeda peran dan keduanya dibutuhkan.</p>

<h2>5. Batasi akses berkas sensitif</h2>
<p>Blokir akses ke berkas konfigurasi, dump database, dan folder version control. Matikan juga directory listing agar isi folder tidak bisa ditelusuri.</p>

<h2>6. Perbarui dependensi secara rutin</h2>
<p>Sebagian besar kerentanan yang dieksploitasi sudah punya perbaikan resmi. Yang menjadi masalah adalah versi yang tertinggal berbulan-bulan.</p>
HTML,
	),
	array(
		'slug'       => 'biaya-membuat-website-bisnis',
		'title'      => 'Rincian Biaya Membuat Website Bisnis dan Apa yang Menentukannya',
		'category'   => 'web-development',
		'tags'       => array( 'wordpress', 'umkm' ),
		'pillar'     => $nexustech_pillar_web_id,
		'keyword'    => 'biaya membuat website',
		'description' => 'Faktor yang menentukan biaya membuat website bisnis: jumlah halaman, integrasi, desain kustom, dan pemeliharaan tahunan.',
		'excerpt'    => 'Kenapa dua website dengan jumlah halaman sama bisa punya biaya yang sangat berbeda, dan komponen mana yang paling berpengaruh.',
		'content'    => <<<'HTML'
<p>Pertanyaan "berapa biaya membuat website" tidak bisa dijawab tanpa konteks. Namun komponen biayanya cukup konsisten, sehingga bisa diperkirakan.</p>

<h2>Komponen utama biaya</h2>
<ul>
	<li><strong>Jumlah dan jenis halaman.</strong> Sepuluh halaman profil berbeda bobotnya dengan sepuluh halaman katalog produk.</li>
	<li><strong>Desain.</strong> Tema siap pakai jauh lebih murah daripada desain kustom yang mengikuti identitas merek.</li>
	<li><strong>Integrasi.</strong> Pembayaran, pengiriman, CRM, dan sistem internal menambah kompleksitas secara signifikan.</li>
	<li><strong>Migrasi konten.</strong> Memindahkan dan merapikan konten lama sering menjadi pekerjaan tersendiri.</li>
	<li><strong>Pemeliharaan.</strong> Pembaruan keamanan, backup, dan pemantauan performa berjalan setiap bulan.</li>
</ul>

<h2>Yang paling sering memengaruhi anggaran</h2>
<p>Integrasi pihak ketiga dan desain kustom adalah dua faktor yang paling sering membuat anggaran meleset. Keduanya juga yang paling sulit diestimasi sebelum kebutuhan teknisnya jelas.</p>

<h2>Cara menekan biaya tanpa mengorbankan kualitas</h2>
<ol>
	<li>Mulai dari lingkup minimum yang bisa dirilis, lalu perluas bertahap.</li>
	<li>Gunakan tema atau komponen yang sudah teruji untuk bagian non-pembeda.</li>
	<li>Siapkan konten sebelum pengembangan dimulai agar tidak ada waktu tunggu.</li>
</ol>
HTML,
	),
	array(
		'slug'       => 'riset-kata-kunci-sederhana',
		'title'      => 'Riset Kata Kunci Sederhana yang Bisa Dilakukan Sendiri',
		'category'   => 'seo',
		'tags'       => array( 'keyword-research', 'umkm' ),
		'pillar'     => $nexustech_pillar_seo_id,
		'keyword'    => 'riset kata kunci',
		'description' => 'Cara melakukan riset kata kunci sederhana tanpa alat berbayar: memetakan niat pencarian dan memilih prioritas.',
		'excerpt'    => 'Empat langkah riset kata kunci yang bisa dikerjakan dengan data pencarian gratis dan sedikit akal sehat.',
		'content'    => <<<'HTML'
<p>Riset kata kunci tidak harus dimulai dari alat berbayar. Yang paling penting adalah memahami niat pencarian di balik setiap frasa.</p>

<h2>Langkah 1: kumpulkan dari sudut pandang pelanggan</h2>
<p>Catat pertanyaan yang paling sering ditanyakan calon pelanggan lewat telepon, chat, dan email. Pertanyaan nyata biasanya lebih bernilai daripada daftar kata kunci hasil alat otomatis.</p>

<h2>Langkah 2: kelompokkan berdasarkan niat</h2>
<ul>
	<li><strong>Informasional.</strong> Pengguna sedang mencari pemahaman, misalnya "apa itu core web vitals".</li>
	<li><strong>Komparatif.</strong> Pengguna sedang membandingkan, misalnya "native vs cross-platform".</li>
	<li><strong>Transaksional.</strong> Pengguna siap bertindak, misalnya "jasa seo jakarta".</li>
</ul>

<h2>Langkah 3: nilai tingkat persaingannya</h2>
<p>Perhatikan jenis halaman yang sudah menempati halaman pertama. Jika didominasi pemain besar dengan halaman kategori, kata kunci itu kemungkinan butuh waktu panjang. Mulai dari frasa yang lebih spesifik.</p>

<h2>Langkah 4: petakan ke arsitektur konten</h2>
<p>Kata kunci transaksional menjadi landing page, kata kunci informasional luas menjadi halaman pillar, dan kata kunci spesifik menjadi artikel cluster yang menautkan balik ke pillar.</p>
HTML,
	),
	array(
		'slug'       => 'core-web-vitals-lcp-inp-cls',
		'title'      => 'Memahami Core Web Vitals: LCP, INP, dan CLS',
		'category'   => 'seo',
		'tags'       => array( 'core-web-vitals', 'html-css', 'javascript' ),
		'pillar'     => $nexustech_pillar_seo_id,
		'keyword'    => 'core web vitals',
		'description' => 'Penjelasan Core Web Vitals: apa itu LCP, INP, dan CLS, penyebab umum nilainya buruk, serta cara memperbaikinya.',
		'excerpt'    => 'Tiga metrik yang menentukan apakah halaman Anda terasa cepat bagi pengguna, dan penyebab tersering nilainya jatuh.',
		'content'    => <<<'HTML'
<p>Core Web Vitals mengukur pengalaman nyata pengguna, bukan sekadar kecepatan server. Ada tiga metrik utama yang perlu dipantau.</p>

<h2>LCP — Largest Contentful Paint</h2>
<p>LCP mengukur kapan elemen terbesar di area pandang selesai dirender. Penyebab umum nilainya buruk adalah gambar hero yang berukuran besar, font yang memblokir render, dan waktu respons server yang lambat.</p>

<h2>INP — Interaction to Next Paint</h2>
<p>INP mengukur responsivitas halaman terhadap interaksi pengguna. Skrip JavaScript yang terlalu banyak bekerja di thread utama adalah penyebab utamanya.</p>

<h2>CLS — Cumulative Layout Shift</h2>
<p>CLS mengukur seberapa banyak tata letak bergeser selama halaman dimuat. Sering disebabkan gambar dan iframe tanpa dimensi tetap, serta konten yang disisipkan setelah halaman terlihat.</p>

<h2>Perbaikan yang paling berdampak</h2>
<ol>
	<li>Sediakan dimensi lebar dan tinggi pada semua gambar serta kontainer media.</li>
	<li>Muat gambar di luar area pandang secara bertahap, dan prioritaskan gambar hero.</li>
	<li>Gunakan font lokal atau preload untuk font utama.</li>
	<li>Tunda eksekusi skrip yang tidak dibutuhkan saat halaman pertama dirender.</li>
</ol>
HTML,
	),
	array(
		'slug'       => 'struktur-internal-link-seo',
		'title'      => 'Struktur Internal Link yang Membuat Konten Saling Menopang',
		'category'   => 'seo',
		'tags'       => array( 'internal-link', 'keyword-research' ),
		'pillar'     => $nexustech_pillar_seo_id,
		'keyword'    => 'internal link',
		'description' => 'Cara menyusun struktur internal link dengan pola pillar dan cluster agar otoritas halaman menyebar merata.',
		'excerpt'    => 'Cara menyusun tautan internal agar setiap artikel baru memperkuat halaman induknya, bukan berdiri sendiri.',
		'content'    => <<<'HTML'
<p>Internal link adalah salah satu elemen SEO yang paling sering diabaikan, padahal pengaruhnya besar dan tidak butuh biaya tambahan.</p>

<h2>Masalah umum: artikel yang berdiri sendiri</h2>
<p>Blog yang terus menambah artikel tanpa struktur lama-lama menjadi tumpukan halaman yang saling tidak mengenal. Mesin pencari kesulitan menentukan halaman mana yang paling penting.</p>

<h2>Pola pillar dan cluster</h2>
<p>Satu halaman pillar membahas topik secara luas, sedangkan beberapa artikel cluster membahas sub-topik secara mendalam. Setiap cluster menautkan ke pillar, dan pillar mendaftar semua cluster-nya.</p>

<h2>Aturan penautan yang sehat</h2>
<ul>
	<li>Gunakan teks anchor yang menjelaskan isi halaman tujuan, bukan "klik di sini".</li>
	<li>Tautkan dari konteks yang relevan, bukan dari daftar di akhir artikel saja.</li>
	<li>Jaga jumlah tautan keluar per artikel tetap wajar dan semuanya relevan.</li>
	<li>Pastikan tidak ada halaman penting yang benar-benar terisolasi.</li>
</ul>

<h2>Menjaga struktur tetap rapi</h2>
<p>Ketika konten bertambah, periksa secara berkala artikel mana yang belum punya pillar induk. Menambahkan satu tautan yang tepat sering kali lebih berdampak daripada menulis artikel baru.</p>
HTML,
	),
	array(
		'slug'       => 'native-vs-cross-platform',
		'title'      => 'Native vs Cross-Platform: Mana yang Tepat untuk Bisnis Anda',
		'category'   => 'mobile-app',
		'tags'       => array( 'flutter', 'javascript' ),
		'pillar'     => $nexustech_pillar_mobile_id,
		'keyword'    => 'native vs cross-platform',
		'description' => 'Perbandingan native dan cross-platform dari sisi biaya, kecepatan rilis, performa, dan kebutuhan akses fitur perangkat.',
		'excerpt'    => 'Perbandingan praktis dua pendekatan pengembangan aplikasi mobile, beserta situasi yang paling cocok untuk masing-masing.',
		'content'    => <<<'HTML'
<p>Pilihan antara native dan cross-platform jarang soal mana yang lebih baik secara teknis. Yang menentukan adalah kebutuhan aplikasi dan kemampuan tim yang merawatnya.</p>

<h2>Native: kontrol penuh, biaya lebih tinggi</h2>
<p>Native berarti membangun terpisah untuk Android dan iOS. Keunggulannya adalah akses paling dalam ke fitur perangkat, performa terbaik untuk animasi berat, dan pengalaman yang paling sesuai dengan panduan tiap platform.</p>

<h2>Cross-platform: satu basis kode, dua platform</h2>
<p>Cross-platform memungkinkan satu basis kode dijalankan di dua platform. Untuk aplikasi bisnis dengan alur yang tidak terlalu rumit, pendekatan ini biasanya lebih cepat dirilis dan lebih hemat biaya.</p>

<h2>Kapan sebaiknya memilih native</h2>
<ul>
	<li>Aplikasi bergantung pada kamera, sensor, atau Bluetooth dengan kebutuhan khusus.</li>
	<li>Ada animasi kompleks atau pemrosesan grafis berat.</li>
	<li>Pengalaman pengguna yang sangat spesifik per platform menjadi keunggulan kompetitif.</li>
</ul>

<h2>Kapan cross-platform lebih tepat</h2>
<ul>
	<li>Alur aplikasi berupa form, daftar, dan integrasi API.</li>
	<li>Tim kecil yang perlu merilis ke dua platform dengan sumber daya terbatas.</li>
	<li>Produk masih akan banyak berubah mengikuti umpan balik pengguna.</li>
</ul>
HTML,
	),
	array(
		'slug'       => 'estimasi-biaya-aplikasi-mobile',
		'title'      => 'Cara Menyusun Estimasi Biaya Aplikasi Mobile Secara Realistis',
		'category'   => 'mobile-app',
		'tags'       => array( 'umkm', 'flutter' ),
		'pillar'     => $nexustech_pillar_mobile_id,
		'keyword'    => 'biaya aplikasi mobile',
		'description' => 'Faktor yang membentuk biaya aplikasi mobile: jumlah alur utama, integrasi backend, desain, dan biaya pemeliharaan setelah rilis.',
		'excerpt'    => 'Apa saja yang sebaiknya masuk ke dalam perhitungan biaya aplikasi mobile agar anggaran tidak membengkak di tengah jalan.',
		'content'    => <<<'HTML'
<p>Estimasi biaya aplikasi mobile paling sering meleset karena lingkupnya belum jelas saat angka disebutkan. Berikut komponen yang perlu dihitung lebih dulu.</p>

<h2>Jumlah alur utama</h2>
<p>Biaya aplikasi lebih ditentukan oleh jumlah alur pengguna yang harus diselesaikan daripada jumlah layar. Satu alur yang melibatkan autentikasi, pembayaran, dan notifikasi bisa lebih berat daripada sepuluh layar statis.</p>

<h2>Kebutuhan backend</h2>
<ul>
	<li>Penyimpanan data pengguna dan autentikasi.</li>
	<li>Integrasi pembayaran atau layanan pihak ketiga.</li>
	<li>Notifikasi push dan sinkronisasi data lintas perangkat.</li>
	<li>Panel admin untuk mengelola konten atau pesanan.</li>
</ul>

<h2>Desain dan pengujian</h2>
<p>Desain antarmuka, pembuatan prototipe, serta pengujian pada beragam ukuran layar dan kondisi jaringan adalah pekerjaan yang nyata dan perlu dianggarkan, bukan pelengkap.</p>

<h2>Biaya setelah rilis</h2>
<p>Setelah aplikasi terbit, masih ada biaya akun developer, pemeliharaan server, pemantauan crash, dan penyesuaian ketika sistem operasi merilis versi baru. Anggaran pemeliharaan tahunan sebaiknya direncanakan sejak awal.</p>
HTML,
	),
	array(
		'slug'       => 'tahapan-rilis-aplikasi',
		'title'      => 'Tahapan Rilis Aplikasi ke Google Play Store dan App Store',
		'category'   => 'mobile-app',
		'tags'       => array( 'play-store', 'umkm' ),
		'pillar'     => $nexustech_pillar_mobile_id,
		'keyword'    => 'rilis aplikasi',
		'description' => 'Tahapan merilis aplikasi ke Play Store dan App Store: penyiapan aset, kebijakan privasi, pengujian beta, dan penanganan peninjauan.',
		'excerpt'    => 'Daftar periksa menjelang publikasi aplikasi, termasuk hal-hal yang paling sering memicu penolakan saat peninjauan.',
		'content'    => <<<'HTML'
<p>Publikasi aplikasi sering dianggap langkah terakhir, padahal ia punya tahapan sendiri yang bisa memakan waktu lebih lama dari perkiraan.</p>

<h2>Sebelum mengunggah</h2>
<ul>
	<li>Ikon aplikasi, tangkapan layar, dan deskripsi yang menjelaskan manfaat aplikasi.</li>
	<li>Kebijakan privasi yang dapat diakses publik.</li>
	<li>Versi aplikasi dan catatan perubahan yang jelas.</li>
	<li>Pengujian pada perangkat nyata, bukan hanya emulator.</li>
</ul>

<h2>Pengujian beta</h2>
<p>Manfaatkan jalur pengujian internal dan beta. Rilis ke lingkaran kecil pengguna lebih dulu membantu menemukan masalah stabilitas sebelum aplikasi dinilai oleh pengguna umum.</p>

<h2>Peninjauan store</h2>
<p>Penyebab paling umum penolakan adalah tautan keluar yang tidak berfungsi, klaim fitur yang tidak sesuai dengan aplikasi, dan penggunaan izin yang tidak dijelaskan. Periksa semuanya sebelum mengirim.</p>

<h2>Setelah rilis</h2>
<p>Pantau laporan crash dan ulasan pengguna pada minggu pertama. Rilis awal hampir selalu membutuhkan perbaikan cepat sebelum masuk ke pengembangan fitur berikutnya.</p>
HTML,
	),
);

$nexustech_index = 0;
foreach ( $nexustech_posts as $nexustech_item ) {
	$nexustech_index++;

	// Tanggal dibuat bertahap agar urutan arsip deterministik.
	$nexustech_date = gmdate( 'Y-m-d H:i:s', strtotime( $nexustech_now . ' -' . $nexustech_index . ' days' ) );

	$nexustech_post_id = nexustech_seed_post(
		array(
			'slug'    => $nexustech_item['slug'],
			'type'    => 'post',
			'title'   => $nexustech_item['title'],
			'content' => $nexustech_item['content'],
			'excerpt' => $nexustech_item['excerpt'],
			'author'  => $nexustech_author,
			'date'    => $nexustech_date,
		)
	);

	if ( ! $nexustech_post_id ) {
		continue;
	}

	wp_set_object_terms( $nexustech_post_id, array( $nexustech_category_ids[ $nexustech_item['category'] ] ), 'category' );

	$nexustech_post_tags = array();
	foreach ( $nexustech_item['tags'] as $nexustech_tag_slug ) {
		if ( isset( $nexustech_tag_ids[ $nexustech_tag_slug ] ) ) {
			$nexustech_post_tags[] = (int) $nexustech_tag_ids[ $nexustech_tag_slug ];
		}
	}
	if ( $nexustech_post_tags ) {
		wp_set_object_terms( $nexustech_post_id, $nexustech_post_tags, 'post_tag' );
	}

	nexustech_seed_meta(
		$nexustech_post_id,
		array(
			'_nexustech_meta_description' => $nexustech_item['description'],
			'_nexustech_focus_keyword'    => $nexustech_item['keyword'],
			'_nexustech_pillar'           => (int) $nexustech_item['pillar'],
		)
	);
}

// ------------------------------------------------------------------
// 4. Opsi tampilan depan
// ------------------------------------------------------------------

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', (int) $nexustech_home_id );
update_option( 'page_for_posts', (int) $nexustech_blog_id );

nexustech_seed_log( 'Beranda: #' . $nexustech_home_id . ', halaman blog: #' . $nexustech_blog_id );

// ------------------------------------------------------------------
// 5. Menu navigasi
// ------------------------------------------------------------------

/**
 * Buat menu bila belum ada, lalu daftarkan itemnya.
 *
 * @param string $menu_name  Nama menu.
 * @param array  $items      Daftar item: [label, type(post_type|custom), id|url].
 * @return int menu_id (0 bila gagal).
 */
function nexustech_seed_menu( $menu_name, array $items ) {
	$menu = wp_get_nav_menu_object( $menu_name );

	if ( $menu instanceof WP_Term ) {
		nexustech_seed_log( '  - Menu "' . $menu_name . '" sudah ada, dilewati.' );

		return (int) $menu->term_id;
	}

	$menu_id = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		nexustech_seed_log( '  GAGAL membuat menu "' . $menu_name . '": ' . $menu_id->get_error_message() );

		return 0;
	}

	$position = 0;
	foreach ( $items as $item ) {
		$position++;

		$args = array(
			'menu-item-title'    => $item['label'],
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position,
		);

		if ( 'custom' === $item['type'] ) {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = $item['url'];
		} else {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = (int) $item['id'];
		}

		wp_update_nav_menu_item( $menu_id, 0, $args );
	}

	nexustech_seed_log( '  - Menu "' . $menu_name . '" dibuat dengan ' . count( $items ) . ' item.' );

	return (int) $menu_id;
}

$nexustech_primary_menu_id = nexustech_seed_menu(
	'Menu Utama',
	array(
		array( 'label' => 'Beranda', 'type' => 'page', 'id' => $nexustech_home_id ),
		array( 'label' => 'Web Development', 'type' => 'page', 'id' => $nexustech_landing_id ),
		array( 'label' => 'SEO', 'type' => 'page', 'id' => $nexustech_seo_landing_id ),
		array( 'label' => 'Mobile App', 'type' => 'page', 'id' => $nexustech_mobile_landing_id ),
		array( 'label' => 'Panduan', 'type' => 'page', 'id' => $nexustech_pillar_web_id ),
		array( 'label' => 'Blog', 'type' => 'page', 'id' => $nexustech_blog_id ),
	)
);

$nexustech_footer_menu_id = nexustech_seed_menu(
	'Menu Footer',
	array(
		array( 'label' => 'Blog', 'type' => 'page', 'id' => $nexustech_blog_id ),
		array( 'label' => 'Panduan SEO', 'type' => 'page', 'id' => $nexustech_pillar_seo_id ),
		array( 'label' => 'Panduan Mobile App', 'type' => 'page', 'id' => $nexustech_pillar_mobile_id ),
		array( 'label' => 'Konsultasi', 'type' => 'custom', 'url' => home_url( '/#kontak' ) ),
	)
);

if ( $nexustech_primary_menu_id || $nexustech_footer_menu_id ) {
	$nexustech_locations = (array) get_theme_mod( 'nav_menu_locations', array() );

	if ( $nexustech_primary_menu_id ) {
		$nexustech_locations['primary'] = $nexustech_primary_menu_id;
	}
	if ( $nexustech_footer_menu_id ) {
		$nexustech_locations['footer'] = $nexustech_footer_menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $nexustech_locations );
}

// ------------------------------------------------------------------
// 6. Selesai
// ------------------------------------------------------------------

flush_rewrite_rules();

nexustech_seed_log( '== Seeding selesai ==' );
nexustech_seed_log( 'Beranda   : ' . home_url( '/' ) );
nexustech_seed_log( 'Blog      : ' . get_permalink( $nexustech_blog_id ) );
nexustech_seed_log( 'Sitemap   : ' . home_url( '/sitemap.xml' ) );
