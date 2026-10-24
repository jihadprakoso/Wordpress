<?php
/**
 * Kartu artikel untuk grid arsip.
 *
 * @package NexusTech_SEO
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'nt-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="nt-card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'nexustech-card', array( 'class' => 'nt-card-img', 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="nt-card-body">
		<?php $nexustech_cats = get_the_category(); ?>
		<?php if ( $nexustech_cats ) : ?>
			<a class="nt-card-cat" href="<?php echo esc_url( get_category_link( $nexustech_cats[0]->term_id ) ); ?>">
				<?php echo esc_html( $nexustech_cats[0]->name ); ?>
			</a>
		<?php endif; ?>

		<h3 class="nt-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="nt-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>

		<p class="nt-card-meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span aria-hidden="true">&middot;</span>
			<span><?php echo esc_html( get_the_author() ); ?></span>
		</p>
	</div>
</article>
