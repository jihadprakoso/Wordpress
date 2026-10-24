<?php
/**
 * Template Name: Pillar / Hub
 * Description: Halaman panduan utama (hub) yang otomatis mendaftar artikel cluster-nya. Menghasilkan CollectionPage schema.
 *
 * @package NexusTech_SEO
 */

get_header();

while ( have_posts() ) :
	the_post();

	$nexustech_clusters = nexustech_clusters_of( get_the_ID() );
	$nexustech_words    = str_word_count( wp_strip_all_tags( (string) get_the_content() ) );
	$nexustech_minutes  = max( 1, (int) ceil( $nexustech_words / 200 ) );

	get_template_part(
		'template-parts/hero',
		null,
		array(
			'eyebrow' => __( 'Panduan Pillar', 'nexustech-seo' ),
			'stats'   => array(
				array(
					'value' => (string) count( $nexustech_clusters ),
					'label' => __( 'Artikel cluster', 'nexustech-seo' ),
				),
				array(
					'value' => (string) $nexustech_minutes,
					'label' => __( 'Menit baca', 'nexustech-seo' ),
				),
			),
		)
	);
	?>
	<div class="container nt-singular">
		<?php nexustech_breadcrumbs(); ?>

		<div class="row g-5">
			<div class="col-lg-8">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'nt-content' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'nt-content-image' ) ); ?>
					<?php endif; ?>

					<?php the_content(); ?>
				</article>
			</div>

			<div class="col-lg-4">
				<aside class="nt-toc">
					<h2 class="nt-toc-title"><?php esc_html_e( 'Isi panduan ini', 'nexustech-seo' ); ?></h2>

					<?php if ( $nexustech_clusters ) : ?>
						<ol class="nt-toc-list">
							<?php foreach ( $nexustech_clusters as $nexustech_cluster ) : ?>
								<li>
									<a href="<?php echo esc_url( get_permalink( $nexustech_cluster ) ); ?>">
										<?php echo esc_html( get_the_title( $nexustech_cluster ) ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ol>
					<?php else : ?>
						<p class="nt-toc-empty"><?php esc_html_e( 'Belum ada artikel cluster yang tertaut ke panduan ini.', 'nexustech-seo' ); ?></p>
					<?php endif; ?>
				</aside>

				<?php get_sidebar(); ?>
			</div>
		</div>

		<?php if ( $nexustech_clusters ) : ?>
			<section class="nt-section nt-clusters">
				<header class="nt-section-head">
					<h2 class="nt-section-title"><?php esc_html_e( 'Artikel dalam panduan ini', 'nexustech-seo' ); ?></h2>
				</header>

				<div class="row g-4">
					<?php foreach ( $nexustech_clusters as $nexustech_cluster ) : ?>
						<?php setup_postdata( $nexustech_cluster ); ?>
						<div class="col-md-6 col-xl-4">
							<?php get_template_part( 'template-parts/card-post' ); ?>
						</div>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
	<?php
endwhile;

get_template_part( 'template-parts/cta' );
get_footer();
