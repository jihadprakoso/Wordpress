<?php
/**
 * Blok FAQ (Bootstrap accordion).
 *
 * @param array $args {
 *     @type array $faq Daftar array( 'q' => string, 'a' => string ).
 * }
 *
 * @package NexusTech_SEO
 */

$nt_faq = isset( $args['faq'] ) && is_array( $args['faq'] ) ? $args['faq'] : array();

if ( ! $nt_faq ) {
	return;
}
?>
<section class="nt-section nt-faq">
	<div class="container">
		<header class="nt-section-head">
			<p class="nt-eyebrow"><?php esc_html_e( 'FAQ', 'nexustech-seo' ); ?></p>
			<h2 class="nt-section-title"><?php esc_html_e( 'Pertanyaan yang sering diajukan', 'nexustech-seo' ); ?></h2>
		</header>

		<div class="accordion nt-accordion" id="nt-faq-accordion">
			<?php foreach ( array_values( $nt_faq ) as $nt_index => $nt_item ) : ?>
				<?php if ( empty( $nt_item['q'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<?php $nt_id = 'nt-faq-' . ( $nt_index + 1 ); ?>
				<div class="accordion-item">
					<h3 class="accordion-header" id="<?php echo esc_attr( $nt_id ); ?>-head">
						<button class="accordion-button<?php echo 0 === $nt_index ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $nt_id ); ?>" aria-expanded="<?php echo 0 === $nt_index ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $nt_id ); ?>">
							<?php echo esc_html( $nt_item['q'] ); ?>
						</button>
					</h3>
					<div id="<?php echo esc_attr( $nt_id ); ?>" class="accordion-collapse collapse<?php echo 0 === $nt_index ? ' show' : ''; ?>" aria-labelledby="<?php echo esc_attr( $nt_id ); ?>-head" data-bs-parent="#nt-faq-accordion">
						<div class="accordion-body">
							<?php echo wp_kses_post( wpautop( (string) ( $nt_item['a'] ?? '' ) ) ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
