<?php
/**
 * Halaman beranda (landing utama NexusTech).
 *
 * @package NexusTech_SEO
 */

get_header();

$nexustech_landings = nexustech_landing_pages();
$nexustech_pillars  = nexustech_pillar_pages();
$nexustech_posts    = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
	)
);

$nexustech_counts = wp_count_posts( 'post' );
$nexustech_total  = isset( $nexustech_counts->publish ) ? (int) $nexustech_counts->publish : 0;

get_template_part(
	'template-parts/hero',
	null,
	array(
		'eyebrow' => __( 'Agensi Digital NexusTech', 'nexustech-seo' ),
		'title'   => __( 'Kehadiran digital yang terukur, bukan sekadar tampil online', 'nexustech-seo' ),
		'lead'    => __( 'Kami membangun website, strategi SEO, dan aplikasi mobile dengan pendekatan berbasis data — lengkap dengan panduan gratis yang bisa Anda pelajari sendiri.', 'nexustech-seo' ),
		'actions' => array(
			array(
				'label' => __( 'Lihat Layanan', 'nexustech-seo' ),
				'url'   => '#layanan',
			),
			array(
				'label' => __( 'Baca Panduan', 'nexustech-seo' ),
				'url'   => '#panduan',
				'style' => 'outline',
			),
		),
		'stats'   => array(
			array(
				'value' => (string) count( $nexustech_landings ),
				'label' => __( 'Layanan', 'nexustech-seo' ),
			),
			array(
				'value' => (string) count( $nexustech_pillars ),
				'label' => __( 'Panduan pillar', 'nexustech-seo' ),
			),
			array(
				'value' => (string) $nexustech_total,
				'label' => __( 'Artikel cluster', 'nexustech-seo' ),
			),
		),
	)
);
?>

<section class="nt-section" id="layanan">
	<div class="container">
		<header class="nt-section-head">
			<p class="nt-eyebrow"><?php esc_html_e( 'Layanan', 'nexustech-seo' ); ?></p>
			<h2 class="nt-section-title"><?php esc_html_e( 'Tiga pilar layanan NexusTech', 'nexustech-seo' ); ?></h2>
			<p class="nt-section-lead"><?php esc_html_e( 'Setiap layanan punya halaman khusus dengan cakupan, proses kerja, dan pertanyaan yang sering diajukan.', 'nexustech-seo' ); ?></p>
		</header>

		<div class="row g-4">
			<?php
			$nexustech_icons = array( 'fa-code', 'fa-magnifying-glass-chart', 'fa-mobile-screen-button' );
			foreach ( $nexustech_landings as $nexustech_index => $nexustech_page ) :
				$nexustech_icon = $nexustech_icons[ $nexustech_index % count( $nexustech_icons ) ];
				?>
				<div class="col-md-6 col-lg-4">
					<article class="nt-service-card">
						<span class="nt-service-icon" aria-hidden="true"><i class="fa-solid <?php echo esc_attr( $nexustech_icon ); ?>"></i></span>
						<h3 class="nt-service-title">
							<a href="<?php echo esc_url( get_permalink( $nexustech_page ) ); ?>"><?php echo esc_html( get_the_title( $nexustech_page ) ); ?></a>
						</h3>
						<p class="nt-service-text">
							<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $nexustech_page->post_content ), 26 ) ); ?>
						</p>
						<a class="nt-link-arrow" href="<?php echo esc_url( get_permalink( $nexustech_page ) ); ?>">
							<?php esc_html_e( 'Selengkapnya', 'nexustech-seo' ); ?>
							<i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
						</a>
					</article>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="nt-section nt-section-alt" id="panduan">
	<div class="container">
		<header class="nt-section-head">
			<p class="nt-eyebrow"><?php esc_html_e( 'Content Network', 'nexustech-seo' ); ?></p>
			<h2 class="nt-section-title"><?php esc_html_e( 'Panduan pillar dan artikel cluster', 'nexustech-seo' ); ?></h2>
			<p class="nt-section-lead"><?php esc_html_e( 'Setiap panduan utama menautkan artikel-artikel pendukungnya secara otomatis melalui relasi pillar-cluster.', 'nexustech-seo' ); ?></p>
		</header>

		<div class="nt-network">
			<?php foreach ( $nexustech_pillars as $nexustech_pillar ) : ?>
				<?php $nexustech_clusters = nexustech_clusters_of( $nexustech_pillar->ID ); ?>
				<article class="nt-network-pillar">
					<div class="nt-network-head">
						<span class="nt-network-badge"><?php esc_html_e( 'Pillar', 'nexustech-seo' ); ?></span>
						<h3 class="nt-network-title">
							<a href="<?php echo esc_url( get_permalink( $nexustech_pillar ) ); ?>"><?php echo esc_html( get_the_title( $nexustech_pillar ) ); ?></a>
						</h3>
						<p class="nt-network-count">
							<?php
							printf(
								/* translators: %d: jumlah artikel cluster */
								esc_html__( '%d artikel cluster terhubung', 'nexustech-seo' ),
								count( $nexustech_clusters )
							);
							?>
						</p>
					</div>

					<?php if ( $nexustech_clusters ) : ?>
						<ul class="nt-network-list">
							<?php foreach ( $nexustech_clusters as $nexustech_cluster ) : ?>
								<li>
									<a href="<?php echo esc_url( get_permalink( $nexustech_cluster ) ); ?>">
										<?php echo esc_html( get_the_title( $nexustech_cluster ) ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="nt-section" id="artikel">
	<div class="container">
		<header class="nt-section-head">
			<p class="nt-eyebrow"><?php esc_html_e( 'Blog', 'nexustech-seo' ); ?></p>
			<h2 class="nt-section-title"><?php esc_html_e( 'Artikel terbaru', 'nexustech-seo' ); ?></h2>
		</header>

		<?php if ( $nexustech_posts ) : ?>
			<div class="row g-4">
				<?php foreach ( $nexustech_posts as $nexustech_post ) : ?>
					<?php setup_postdata( $nexustech_post ); ?>
					<div class="col-md-6 col-xl-4">
						<?php get_template_part( 'template-parts/card-post' ); ?>
					</div>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<p class="nt-section-more">
				<a class="btn btn-nt-outline" href="<?php echo esc_url( nexustech_blog_url() ); ?>">
					<?php esc_html_e( 'Lihat semua artikel', 'nexustech-seo' ); ?>
				</a>
			</p>
		<?php else : ?>
			<p class="nt-empty-text"><?php esc_html_e( 'Belum ada artikel yang dipublikasikan.', 'nexustech-seo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php
get_footer();
