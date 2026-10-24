<?php
/**
 * Template hero yang dipakai ulang.
 *
 * @param array $args {
 *     @type string $eyebrow Label kecil di atas judul.
 *     @type string $title   Judul utama.
 *     @type string $lead    Paragraf pengantar.
 *     @type array  $actions Daftar tombol: [ label, url, style ].
 *     @type array  $stats   Daftar statistik: [ value, label ].
 * }
 *
 * @package NexusTech_SEO
 */

$nt_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$nt_lead    = isset( $args['lead'] ) ? $args['lead'] : ( is_singular() ? nexustech_get_meta_description() : get_bloginfo( 'description' ) );
$nt_actions = isset( $args['actions'] ) ? $args['actions'] : array();
$nt_stats   = isset( $args['stats'] ) ? $args['stats'] : array();

if ( isset( $args['title'] ) ) {
	$nt_title = $args['title'];
} elseif ( is_singular() ) {
	$nt_title = get_the_title();
} else {
	$nt_title = get_bloginfo( 'name' );
}
?>
<section class="nt-hero">
	<div class="container">
		<div class="nt-hero-inner">
			<?php if ( '' !== $nt_eyebrow ) : ?>
				<p class="nt-eyebrow"><?php echo esc_html( $nt_eyebrow ); ?></p>
			<?php endif; ?>

			<h1 class="nt-hero-title"><?php echo esc_html( $nt_title ); ?></h1>

			<?php if ( $nt_lead ) : ?>
				<p class="nt-hero-lead"><?php echo esc_html( $nt_lead ); ?></p>
			<?php endif; ?>

			<?php if ( $nt_actions ) : ?>
				<div class="nt-hero-actions">
					<?php foreach ( $nt_actions as $nt_action ) : ?>
						<?php
						$nt_style = isset( $nt_action['style'] ) ? $nt_action['style'] : 'primary';
						$nt_class = 'outline' === $nt_style ? 'btn-nt-outline' : 'btn-nt-primary';
						?>
						<a class="btn <?php echo esc_attr( $nt_class ); ?>" href="<?php echo esc_url( $nt_action['url'] ); ?>">
							<?php echo esc_html( $nt_action['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $nt_stats ) : ?>
				<ul class="nt-hero-stats">
					<?php foreach ( $nt_stats as $nt_stat ) : ?>
						<li>
							<span class="nt-stat-value"><?php echo esc_html( $nt_stat['value'] ); ?></span>
							<span class="nt-stat-label"><?php echo esc_html( $nt_stat['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
