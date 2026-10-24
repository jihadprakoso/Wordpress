<?php
/**
 * Halaman statis biasa.
 *
 * @package NexusTech_SEO
 */

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/hero',
		null,
		array(
			'eyebrow' => __( 'NexusTech', 'nexustech-seo' ),
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
	<?php
endwhile;

get_template_part( 'template-parts/cta' );
get_footer();
