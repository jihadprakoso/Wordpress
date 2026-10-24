<?php
/**
 * Arsip penulis.
 *
 * @package NexusTech_SEO
 */

get_header();
?>
<div class="container nt-archive">
	<?php nexustech_breadcrumbs(); ?>

	<?php $nexustech_author_id = (int) get_queried_object_id(); ?>
	<?php $nexustech_bio = get_the_author_meta( 'description', $nexustech_author_id ); ?>
	<?php if ( $nexustech_bio ) : ?>
		<div class="nt-author-card">
			<?php echo get_avatar( $nexustech_author_id, 72, '', '', array( 'class' => 'nt-author-avatar' ) ); ?>
			<div>
				<p class="nt-eyebrow"><?php esc_html_e( 'Penulis', 'nexustech-seo' ); ?></p>
				<h2 class="nt-author-name"><?php echo esc_html( get_the_author_meta( 'display_name', $nexustech_author_id ) ); ?></h2>
				<p class="nt-author-bio"><?php echo esc_html( $nexustech_bio ); ?></p>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/archive-loop' ); ?>
</div>
<?php
get_footer();
