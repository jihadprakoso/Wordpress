<?php
/**
 * Meta box SEO, relasi pillar, dan FAQ.
 *
 * Kontrak meta key (lihat docs/DESIGN.md):
 * - _nexustech_meta_description  (string)
 * - _nexustech_focus_keyword     (string)
 * - _nexustech_pillar            (int, post saja)
 * - _nexustech_faq               (array of ['q' => string, 'a' => string], page saja)
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftar pilihan pillar (halaman dengan template pillar).
 *
 * @return WP_Post[]
 */
function nexustech_pillar_choices() {
	return get_pages(
		array(
			'post_status' => array( 'publish', 'draft', 'private' ),
			'meta_key'    => '_wp_page_template',
			'meta_value'  => NEXUSTECH_TPL_PILLAR,
			'sort_column' => 'menu_order,post_title',
		)
	);
}

/**
 * Daftarkan meta box.
 */
function nexustech_add_meta_boxes() {
	add_meta_box( 'nexustech-seo', __( 'SEO NexusTech', 'nexustech-seo' ), 'nexustech_render_seo_box', array( 'post', 'page' ), 'normal', 'high' );
	add_meta_box( 'nexustech-network', __( 'Content Network (Pillar)', 'nexustech-seo' ), 'nexustech_render_network_box', 'post', 'side', 'default' );
	add_meta_box( 'nexustech-faq', __( 'FAQ (Landing Page)', 'nexustech-seo' ), 'nexustech_render_faq_box', 'page', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'nexustech_add_meta_boxes' );

/**
 * Nonce bersama untuk semua meta box tema.
 */
function nexustech_meta_nonce_field() {
	wp_nonce_field( 'nexustech_save_meta', 'nexustech_meta_nonce' );
}

/**
 * Meta box SEO.
 *
 * @param WP_Post $post Post yang sedang diedit.
 */
function nexustech_render_seo_box( $post ) {
	$description = (string) get_post_meta( $post->ID, '_nexustech_meta_description', true );
	$keyword     = (string) get_post_meta( $post->ID, '_nexustech_focus_keyword', true );

	nexustech_meta_nonce_field();
	?>
	<div class="nexustech-metabox">
		<p>
			<label for="nexustech-meta-description"><strong><?php esc_html_e( 'Meta description', 'nexustech-seo' ); ?></strong></label>
		</p>
		<textarea id="nexustech-meta-description" name="nexustech_meta_description" rows="3" class="widefat" maxlength="300"><?php echo esc_textarea( $description ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Kosongkan untuk memakai excerpt/isi konten secara otomatis. Idealnya 120-155 karakter.', 'nexustech-seo' ); ?></p>

		<p style="margin-top:1rem;">
			<label for="nexustech-focus-keyword"><strong><?php esc_html_e( 'Focus keyword', 'nexustech-seo' ); ?></strong></label>
		</p>
		<input type="text" id="nexustech-focus-keyword" name="nexustech_focus_keyword" class="widefat" value="<?php echo esc_attr( $keyword ); ?>">
		<p class="description">
			<?php esc_html_e( 'Badge: ', 'nexustech-seo' ); ?>
			<span id="nexustech-keyword-badge" class="nexustech-badge"><?php echo esc_html( '' !== $keyword ? $keyword : '—' ); ?></span>
		</p>
	</div>
	<script>
	( function () {
		var input = document.getElementById( 'nexustech-focus-keyword' );
		var badge = document.getElementById( 'nexustech-keyword-badge' );
		if ( ! input || ! badge ) { return; }
		input.addEventListener( 'input', function () {
			badge.textContent = input.value.trim() === '' ? '—' : input.value;
		} );
	} )();
	</script>
	<?php
}

/**
 * Meta box relasi pillar.
 *
 * @param WP_Post $post Post yang sedang diedit.
 */
function nexustech_render_network_box( $post ) {
	$current = (int) get_post_meta( $post->ID, '_nexustech_pillar', true );
	$pillars = nexustech_pillar_choices();
	?>
	<p><?php esc_html_e( 'Tautkan artikel ini ke halaman pillar-nya agar masuk ke daftar "Bagian dari panduan".', 'nexustech-seo' ); ?></p>
	<select name="nexustech_pillar" class="widefat">
		<option value="0"><?php esc_html_e( '— Tidak ada —', 'nexustech-seo' ); ?></option>
		<?php foreach ( $pillars as $pillar ) : ?>
			<option value="<?php echo esc_attr( $pillar->ID ); ?>" <?php selected( $current, $pillar->ID ); ?>>
				<?php echo esc_html( $pillar->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}

/**
 * Meta box FAQ.
 *
 * @param WP_Post $post Page yang sedang diedit.
 */
function nexustech_render_faq_box( $post ) {
	$faq  = get_post_meta( $post->ID, '_nexustech_faq', true );
	$lines = array();

	if ( is_array( $faq ) ) {
		foreach ( $faq as $entry ) {
			if ( empty( $entry['q'] ) ) {
				continue;
			}
			$lines[] = $entry['q'] . ' | ' . (string) ( $entry['a'] ?? '' );
		}
	}

	?>
	<p><?php esc_html_e( 'Satu FAQ per baris dengan format: Pertanyaan | Jawaban', 'nexustech-seo' ); ?></p>
	<textarea name="nexustech_faq" rows="6" class="widefat" placeholder="Berapa lama proses pembuatan website? | Umumnya 2-4 minggu tergantung kompleksitas."><?php echo esc_textarea( implode( "\n", $lines ) ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Hanya dipakai pada halaman dengan template "Landing Page (Jasa)" dan otomatis menghasilkan FAQPage schema.', 'nexustech-seo' ); ?></p>
	<?php
}

/**
 * Simpan nilai meta box.
 *
 * @param int $post_id ID post.
 */
function nexustech_save_meta_boxes( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['nexustech_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['nexustech_meta_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'nexustech_save_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['nexustech_meta_description'] ) ) {
		update_post_meta( $post_id, '_nexustech_meta_description', sanitize_textarea_field( wp_unslash( $_POST['nexustech_meta_description'] ) ) );
	}

	if ( isset( $_POST['nexustech_focus_keyword'] ) ) {
		update_post_meta( $post_id, '_nexustech_focus_keyword', sanitize_text_field( wp_unslash( $_POST['nexustech_focus_keyword'] ) ) );
	}

	if ( 'post' === get_post_type( $post_id ) && isset( $_POST['nexustech_pillar'] ) ) {
		$pillar = absint( wp_unslash( $_POST['nexustech_pillar'] ) );
		if ( $pillar ) {
			update_post_meta( $post_id, '_nexustech_pillar', $pillar );
		} else {
			delete_post_meta( $post_id, '_nexustech_pillar' );
		}
	}

	if ( 'page' === get_post_type( $post_id ) && isset( $_POST['nexustech_faq'] ) ) {
		$raw    = (string) wp_unslash( $_POST['nexustech_faq'] );
		$parsed = array();

		foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}

			$parts = explode( '|', $line, 2 );
			$q     = sanitize_text_field( trim( $parts[0] ) );
			if ( '' === $q ) {
				continue;
			}

			$parsed[] = array(
				'q' => $q,
				'a' => sanitize_textarea_field( trim( $parts[1] ?? '' ) ),
			);
		}

		if ( $parsed ) {
			update_post_meta( $post_id, '_nexustech_faq', $parsed );
		} else {
			delete_post_meta( $post_id, '_nexustech_faq' );
		}
	}
}
add_action( 'save_post', 'nexustech_save_meta_boxes' );
