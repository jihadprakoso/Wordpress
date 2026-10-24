<?php
/**
 * Isi daftar arsip: judul konteks, grid kartu, dan pagination.
 *
 * Dipakai ulang oleh index.php, archive.php, category.php, tag.php,
 * author.php, dan search.php.
 *
 * @package NexusTech_SEO
 */

if ( is_home() ) {
	$nexustech_blog_id = (int) get_option( 'page_for_posts' );
	$nexustech_title   = $nexustech_blog_id ? get_the_title( $nexustech_blog_id ) : __( 'Blog', 'nexustech-seo' );
	$nexustech_desc    = $nexustech_blog_id ? nexustech_get_meta_description() : '';
} elseif ( is_search() ) {
	$nexustech_title = sprintf(
		/* translators: %s: kata kunci pencarian */
		__( 'Hasil pencarian: %s', 'nexustech-seo' ),
		get_search_query()
	);
	$nexustech_desc  = sprintf(
		/* translators: %d: jumlah artikel */
		__( '%d artikel ditemukan.', 'nexustech-seo' ),
		(int) $GLOBALS['wp_query']->found_posts
	);
} else {
	$nexustech_title = wp_strip_all_tags( get_the_archive_title() );
	$nexustech_desc  = wp_strip_all_tags( get_the_archive_description() );
}
?>
<header class="nt-archive-head">
	<h1 class="nt-archive-title"><?php echo esc_html( $nexustech_title ); ?></h1>
	<?php if ( '' !== $nexustech_desc ) : ?>
		<p class="nt-archive-desc"><?php echo esc_html( $nexustech_desc ); ?></p>
	<?php endif; ?>
</header>

<?php if ( have_posts() ) : ?>
	<div class="row g-4">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<div class="col-md-6 col-xl-4">
				<?php get_template_part( 'template-parts/card-post' ); ?>
			</div>
		<?php endwhile; ?>
	</div>

	<div class="nt-pagination">
		<?php
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => esc_html__( 'Sebelumnya', 'nexustech-seo' ),
				'next_text'          => esc_html__( 'Berikutnya', 'nexustech-seo' ),
				'screen_reader_text' => esc_html__( 'Navigasi halaman', 'nexustech-seo' ),
			)
		);
		?>
	</div>
<?php else : ?>
	<div class="nt-empty">
		<p><?php esc_html_e( 'Belum ada artikel yang cocok dengan tampilan ini.', 'nexustech-seo' ); ?></p>
		<?php get_search_form(); ?>
	</div>
<?php endif; ?>
