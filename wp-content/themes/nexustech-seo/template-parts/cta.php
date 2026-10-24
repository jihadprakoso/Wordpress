<?php
/**
 * Blok CTA penutup.
 *
 * @param array $args {
 *     @type string $title  Judul CTA.
 *     @type string $text   Deskripsi CTA.
 *     @type string $anchor ID anchor (default: kontak).
 * }
 *
 * @package NexusTech_SEO
 */

$nt_cta_title  = isset( $args['title'] ) ? $args['title'] : __( 'Siap membangun kehadiran digital Anda?', 'nexustech-seo' );
$nt_cta_text   = isset( $args['text'] ) ? $args['text'] : __( 'Ceritakan tujuan bisnis Anda, kami bantu susun strategi website, SEO, dan aplikasi mobile yang terukur.', 'nexustech-seo' );
$nt_cta_anchor = isset( $args['anchor'] ) ? $args['anchor'] : 'kontak';
?>
<section class="nt-section nt-cta" id="<?php echo esc_attr( $nt_cta_anchor ); ?>">
	<div class="container">
		<div class="nt-cta-inner">
			<div>
				<h2 class="nt-cta-title"><?php echo esc_html( $nt_cta_title ); ?></h2>
				<p class="nt-cta-text"><?php echo esc_html( $nt_cta_text ); ?></p>
			</div>
			<div class="nt-cta-actions">
				<a class="btn btn-nt-light" href="https://wa.me/6285773837959" target="_blank" rel="noopener noreferrer">
					<i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
					<?php esc_html_e( 'Chat WhatsApp', 'nexustech-seo' ); ?>
				</a>
				<a class="btn btn-nt-ghost" href="<?php echo esc_url( home_url( '/#layanan' ) ); ?>">
					<?php esc_html_e( 'Lihat Layanan', 'nexustech-seo' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
