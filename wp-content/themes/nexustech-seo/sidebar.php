<?php
/**
 * Sidebar widget area.
 *
 * @package NexusTech_SEO
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="nt-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'nexustech-seo' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
