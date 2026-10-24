<?php
/**
 * Structured data (JSON-LD) dalam satu graf.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bangun graf schema.org untuk halaman yang sedang dibuka.
 *
 * @return array
 */
function nexustech_schema_graph() {
	$home  = home_url( '/' );
	$name  = get_bloginfo( 'name' );
	$lang  = get_bloginfo( 'language' );
	$graph = array();

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $home . '#organization',
		'name'  => $name,
		'url'   => $home,
	);

	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo_src = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $logo_src ) {
			$organization['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $logo_src,
			);
		}
	}

	$graph[] = $organization;

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => $home . '#website',
		'url'             => $home,
		'name'            => $name,
		'description'     => get_bloginfo( 'description' ),
		'inLanguage'      => $lang,
		'publisher'       => array( '@id' => $home . '#organization' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$crumbs = nexustech_breadcrumb_items();
	if ( count( $crumbs ) > 1 ) {
		$elements = array();
		foreach ( $crumbs as $index => $crumb ) {
			$item = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'name'     => $crumb['label'],
			);
			if ( '' !== $crumb['url'] ) {
				$item['item'] = $crumb['url'];
			}
			$elements[] = $item;
		}

		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => nexustech_get_canonical() . '#breadcrumb',
			'itemListElement' => $elements,
		);
	}

	if ( is_singular() ) {
		$post_id  = (int) get_queried_object_id();
		$permalink = (string) get_permalink();
		$template  = (string) get_page_template_slug( $post_id );

		if ( 'post' === get_post_type( $post_id ) ) {
			$article = array(
				'@type'            => 'BlogPosting',
				'@id'              => $permalink . '#article',
				'headline'         => wp_strip_all_tags( get_the_title() ),
				'description'      => nexustech_get_meta_description(),
				'datePublished'    => get_the_date( 'c' ),
				'dateModified'     => get_the_modified_date( 'c' ),
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id'   => $permalink,
				),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ),
					'url'   => get_author_posts_url( (int) get_post_field( 'post_author', $post_id ) ),
				),
				'publisher'        => array( '@id' => $home . '#organization' ),
				'inLanguage'       => $lang,
			);

			if ( has_post_thumbnail() ) {
				$article['image'] = get_the_post_thumbnail_url( $post_id, 'full' );
			}

			$graph[] = $article;
		} elseif ( NEXUSTECH_TPL_LANDING === $template ) {
			$graph[] = array(
				'@type'       => 'Service',
				'@id'         => $permalink . '#service',
				'name'        => wp_strip_all_tags( get_the_title() ),
				'description' => nexustech_get_meta_description(),
				'url'         => $permalink,
				'provider'    => array( '@id' => $home . '#organization' ),
				'areaServed'  => array(
					'@type' => 'Country',
					'name'  => 'Indonesia',
				),
			);

			$faq = get_post_meta( $post_id, '_nexustech_faq', true );
			if ( is_array( $faq ) && $faq ) {
				$questions = array();
				foreach ( $faq as $entry ) {
					if ( empty( $entry['q'] ) ) {
						continue;
					}
					$questions[] = array(
						'@type'          => 'Question',
						'name'           => wp_strip_all_tags( $entry['q'] ),
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( (string) ( $entry['a'] ?? '' ) ),
						),
					);
				}

				if ( $questions ) {
					$graph[] = array(
						'@type'      => 'FAQPage',
						'@id'        => $permalink . '#faq',
						'mainEntity' => $questions,
					);
				}
			}
		} elseif ( NEXUSTECH_TPL_PILLAR === $template ) {
			$parts = array();
			foreach ( nexustech_clusters_of( $post_id ) as $cluster ) {
				$parts[] = array(
					'@type' => 'BlogPosting',
					'@id'   => get_permalink( $cluster ) . '#article',
					'name'  => wp_strip_all_tags( get_the_title( $cluster ) ),
					'url'   => get_permalink( $cluster ),
				);
			}

			$collection = array(
				'@type'       => 'CollectionPage',
				'@id'         => $permalink . '#collection',
				'name'        => wp_strip_all_tags( get_the_title() ),
				'description' => nexustech_get_meta_description(),
				'url'         => $permalink,
				'inLanguage'  => $lang,
			);

			if ( $parts ) {
				$collection['hasPart'] = $parts;
			}

			$graph[] = $collection;
		}
	}

	return $graph;
}

/**
 * Cetak JSON-LD ke <head>.
 */
function nexustech_print_schema() {
	$graph = nexustech_schema_graph();
	if ( ! $graph ) {
		return;
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'nexustech_print_schema', 20 );
