<?php
/**
 * Footer tema.
 *
 * @package NexusTech_SEO
 */

?>
</main>

<footer class="nt-footer">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-4">
				<p class="nt-footer-brand">
					<span class="nt-brand-mark" aria-hidden="true"><i class="fa-solid fa-diagram-project"></i></span>
					<?php bloginfo( 'name' ); ?>
				</p>
				<p class="nt-footer-desc"><?php bloginfo( 'description' ); ?></p>

				<ul class="nt-social">
					<li>
						<a href="https://github.com/jihadprakoso" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
							<i class="fa-brands fa-github" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a href="https://www.linkedin.com/in/jihad-prakoso/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
							<i class="fa-brands fa-linkedin" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a href="https://wa.me/6285773837959" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
							<i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
						</a>
					</li>
				</ul>
			</div>

			<div class="col-6 col-lg-2">
				<h2 class="nt-footer-title"><?php esc_html_e( 'Jelajahi', 'nexustech-seo' ); ?></h2>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'nt-footer-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}
				?>
			</div>

			<div class="col-6 col-lg-3">
				<h2 class="nt-footer-title"><?php esc_html_e( 'Layanan', 'nexustech-seo' ); ?></h2>
				<ul class="nt-footer-menu">
					<?php foreach ( nexustech_landing_pages() as $nexustech_page ) : ?>
						<li>
							<a href="<?php echo esc_url( get_permalink( $nexustech_page ) ); ?>">
								<?php echo esc_html( get_the_title( $nexustech_page ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="col-lg-3">
				<h2 class="nt-footer-title"><?php esc_html_e( 'Kontak', 'nexustech-seo' ); ?></h2>
				<ul class="nt-footer-menu">
					<li>
						<a href="https://wa.me/6285773837959" target="_blank" rel="noopener noreferrer">
							<i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
							<?php esc_html_e( 'WhatsApp', 'nexustech-seo' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/sitemap.xml' ) ); ?>">
							<i class="fa-solid fa-sitemap" aria-hidden="true"></i>
							<?php esc_html_e( 'Sitemap XML', 'nexustech-seo' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="nt-footer-bottom">
			<p>
				<?php
				printf(
					/* translators: %1$s: tahun, %2$s: nama situs */
					esc_html__( '© %1$s %2$s. Seluruh hak cipta dilindungi.', 'nexustech-seo' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<p class="nt-footer-credit"><?php esc_html_e( 'Tema kustom nexustech-seo — tanpa plugin SEO.', 'nexustech-seo' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
