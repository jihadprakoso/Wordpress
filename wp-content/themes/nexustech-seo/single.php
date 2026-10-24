<?php
/**
 * Artikel tunggal (post). Artikel cluster menampilkan tautan ke pillar induknya.
 *
 * @package NexusTech_SEO
 */

get_header();

while ( have_posts() ) :
	the_post();

	$nexustech_pillar     = nexustech_pillar_of();
	$nexustech_categories = get_the_category();
	$nexustech_eyebrow    = $nexustech_categories ? $nexustech_categories[0]->name : __( 'Artikel', 'nexustech-seo' );
	$nexustech_words      = str_word_count( wp_strip_all_tags( (string) get_the_content() ) );
	$nexustech_minutes    = max( 1, (int) ceil( $nexustech_words / 200 ) );

	get_template_part(
		'template-parts/hero',
		null,
		array(
			'eyebrow' => $nexustech_eyebrow,
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

					<p class="nt-entry-meta">
						<span><i class="fa-regular fa-calendar" aria-hidden="true"></i> <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
						<span><i class="fa-regular fa-user" aria-hidden="true"></i> <?php the_author(); ?></span>
						<span>
							<i class="fa-regular fa-clock" aria-hidden="true"></i>
							<?php
							printf(
								/* translators: %d: estimasi menit baca */
								esc_html__( '%d menit baca', 'nexustech-seo' ),
								(int) $nexustech_minutes
							);
							?>
						</span>
					</p>

					<?php the_content(); ?>

					<?php if ( has_tag() ) : ?>
						<div class="nt-tags">
							<span class="nt-tags-label"><?php esc_html_e( 'Tag:', 'nexustech-seo' ); ?></span>
							<?php the_tags( '', '', '' ); ?>
						</div>
					<?php endif; ?>
				</article>

				<?php if ( $nexustech_pillar ) : ?>
					<aside class="nt-pillar-box">
						<p class="nt-eyebrow"><?php esc_html_e( 'Bagian dari panduan', 'nexustech-seo' ); ?></p>
						<h2 class="nt-pillar-box-title">
							<a href="<?php echo esc_url( get_permalink( $nexustech_pillar ) ); ?>">
								<?php echo esc_html( get_the_title( $nexustech_pillar ) ); ?>
							</a>
						</h2>
						<p class="nt-pillar-box-text">
							<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $nexustech_pillar->post_content ), 28 ) ); ?>
						</p>
						<a class="nt-link-arrow" href="<?php echo esc_url( get_permalink( $nexustech_pillar ) ); ?>">
							<?php esc_html_e( 'Buka panduan lengkap', 'nexustech-seo' ); ?>
							<i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
						</a>
					</aside>
				<?php endif; ?>
			</div>

			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>

		<?php $nexustech_related = nexustech_related_posts( get_the_ID(), 3 ); ?>
		<?php if ( $nexustech_related ) : ?>
			<section class="nt-section nt-related">
				<header class="nt-section-head">
					<h2 class="nt-section-title"><?php esc_html_e( 'Artikel terkait', 'nexustech-seo' ); ?></h2>
				</header>
				<div class="row g-4">
					<?php foreach ( $nexustech_related as $nexustech_related_post ) : ?>
						<?php setup_postdata( $nexustech_related_post ); ?>
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
