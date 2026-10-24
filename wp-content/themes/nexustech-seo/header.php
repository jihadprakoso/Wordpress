<?php
/**
 * Header tema.
 *
 * @package NexusTech_SEO
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="nt-skip-link" href="#nt-main"><?php esc_html_e( 'Lewati ke konten utama', 'nexustech-seo' ); ?></a>

<header class="nt-header sticky-top">
	<nav class="navbar navbar-expand-lg nt-navbar" aria-label="<?php esc_attr_e( 'Navigasi utama', 'nexustech-seo' ); ?>">
		<div class="container">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="navbar-brand nt-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="nt-brand-mark" aria-hidden="true"><i class="fa-solid fa-diagram-project"></i></span>
					<span class="nt-brand-text"><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nt-primary-nav" aria-controls="nt-primary-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Buka navigasi', 'nexustech-seo' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="nt-primary-nav">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0 nt-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}
				?>

				<a class="btn btn-nt-primary ms-lg-3" href="<?php echo esc_url( home_url( '/#kontak' ) ); ?>">
					<?php esc_html_e( 'Konsultasi Gratis', 'nexustech-seo' ); ?>
				</a>
			</div>
		</div>
	</nav>
</header>

<main id="nt-main" class="nt-main">
