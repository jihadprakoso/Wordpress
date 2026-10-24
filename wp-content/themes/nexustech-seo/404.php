<?php
/**
 * Halaman 404.
 *
 * @package NexusTech_SEO
 */

get_header();
?>
<div class="container nt-404">
	<p class="nt-eyebrow"><?php esc_html_e( 'Error 404', 'nexustech-seo' ); ?></p>
	<h1 class="nt-404-title"><?php esc_html_e( 'Halaman yang Anda cari tidak ditemukan', 'nexustech-seo' ); ?></h1>
	<p class="nt-404-text"><?php esc_html_e( 'Tautan mungkin sudah dipindahkan atau tidak pernah ada. Coba telusuri layanan dan panduan di bawah ini.', 'nexustech-seo' ); ?></p>

	<div class="nt-404-search"><?php get_search_form(); ?></div>

	<div class="row g-4 nt-404-grid">
		<div class="col-md-6">
			<h2 class="nt-404-sub"><?php esc_html_e( 'Layanan', 'nexustech-seo' ); ?></h2>
			<ul class="nt-link-list">
				<?php foreach ( nexustech_landing_pages() as $nexustech_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $nexustech_page ) ); ?>"><?php echo esc_html( get_the_title( $nexustech_page ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="col-md-6">
			<h2 class="nt-404-sub"><?php esc_html_e( 'Panduan', 'nexustech-seo' ); ?></h2>
			<ul class="nt-link-list">
				<?php foreach ( nexustech_pillar_pages() as $nexustech_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $nexustech_page ) ); ?>"><?php echo esc_html( get_the_title( $nexustech_page ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<p class="nt-404-home">
		<a class="btn btn-nt-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<i class="fa-solid fa-house" aria-hidden="true"></i>
			<?php esc_html_e( 'Kembali ke beranda', 'nexustech-seo' ); ?>
		</a>
	</p>
</div>
<?php
get_footer();
