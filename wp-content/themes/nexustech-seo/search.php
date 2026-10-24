<?php
/**
 * Hasil pencarian.
 *
 * @package NexusTech_SEO
 */

get_header();
?>
<div class="container nt-archive">
	<?php nexustech_breadcrumbs(); ?>
	<?php get_template_part( 'template-parts/archive-loop' ); ?>
</div>
<?php
get_footer();
