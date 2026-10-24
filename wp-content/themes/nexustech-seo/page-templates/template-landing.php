<?php
/**
 * Template Name: Landing Page (Jasa)
 * Description: Halaman layanan dengan hero, FAQ, dan daftar panduan terkait. Menghasilkan Service + FAQPage schema.
 *
 * @package NexusTech_SEO
 */

get_header();

while ( have_posts() ) :
	the_post();

	$nexustech_faq    = get_post_meta( get_the_ID(), '_nexustech_faq', true );
	$nexustech_guides = nexustech_pillar_pages();
	$nexustech_posts  = wp_count_posts( 'post' );

	get_template_part(
		'template-parts/hero',
		null,
		array(
			'eyebrow' => __( 'Layanan NexusTech', 'nexustech-seo' ),
			'actions' => array(
				array(
					'label' => __( 'Konsultasi Gratis', 'nexustech-seo' ),
					'url'   => '#kontak',
				),
				array(
					'label' => __( 'Baca Panduan', 'nexustech-seo' ),
					'url'   => '#panduan-terkait',
					'style' => 'outline',
				),
			),
			'stats'   => array(
				array(
					'value' => (string) count( $nexustech_guides ),
					'label' => __( 'Panduan pillar', 'nexustech-seo' ),
				),
				array(
					'value' => isset( $nexustech_posts->publish ) ? (string) (int) $nexustech_posts->publish : '0',
					'label' => __( 'Artikel pendukung', 'nexustech-seo' ),
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
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>

	<?php get_template_part( 'template-parts/faq', null, array( 'faq' => $nexustech_faq ) ); ?>

	<?php if ( $nexustech_guides ) : ?>
		<section class="nt-section nt-section-alt" id="panduan-terkait">
			<div class="container">
				<header class="nt-section-head">
					<p class="nt-eyebrow"><?php esc_html_e( 'Panduan', 'nexustech-seo' ); ?></p>
					<h2 class="nt-section-title"><?php esc_html_e( 'Pelajari lebih dalam sebelum memutuskan', 'nexustech-seo' ); ?></h2>
				</header>

				<div class="row g-4">
					<?php foreach ( $nexustech_guides as $nexustech_guide ) : ?>
						<?php $nexustech_cluster_count = count( nexustech_clusters_of( $nexustech_guide->ID ) ); ?>
						<div class="col-md-6 col-lg-4">
							<article class="nt-guide-card">
								<p class="nt-network-badge"><?php esc_html_e( 'Panduan', 'nexustech-seo' ); ?></p>
								<h3 class="nt-guide-title">
									<a href="<?php echo esc_url( get_permalink( $nexustech_guide ) ); ?>"><?php echo esc_html( get_the_title( $nexustech_guide ) ); ?></a>
								</h3>
								<p class="nt-guide-text">
									<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $nexustech_guide->post_content ), 22 ) ); ?>
								</p>
								<p class="nt-network-count">
									<?php
									printf(
										/* translators: %d: jumlah artikel cluster */
										esc_html__( '%d artikel cluster', 'nexustech-seo' ),
										(int) $nexustech_cluster_count
									);
									?>
								</p>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_template_part( 'template-parts/cta' );
get_footer();
