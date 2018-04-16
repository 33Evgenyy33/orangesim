<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Taxonomy element
 *
 * @var $taxonomy_name string Taxonomy name
 * @var $link string Link type: 'post' / 'archive' / 'custom' / 'none'
 * @var $custom_link string
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( empty( $taxonomy_name ) OR ! taxonomy_exists( $taxonomy_name ) OR ! is_object_in_taxonomy( get_post_type(), $taxonomy_name ) ) {
	return FALSE;
}
$taxonomies = get_the_terms( get_the_ID(), $taxonomy_name );
if ( ! is_array( $taxonomies ) OR count( $taxonomies ) == 0 ) {
	return FALSE;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= ' style_' . $style;

$_link_url = $_link_meta = '';
if ( $link === 'post' ) {
	$_link_url = apply_filters( 'the_permalink', get_permalink() );
} elseif ( $link === 'custom' ) {
	$link_atts = usof_get_link_atts( $custom_link );
	$_link_url = $link_atts['href'];
	$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
}
// Output "rel" attribute for Posts tags
if ( $taxonomy_name == 'post_tag' ) {
	$_link_meta .= ' rel="tag"';
}

$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}

$i = 1;
foreach ( $taxonomies as $taxonomy ) {
	if ( $link === 'archive' ) {
		$_link_url = get_term_link( $taxonomy );
	}
	if ( ! empty( $_link_url ) ) {
		$output .= '<a href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
	}
	$output .= $taxonomy->name;
	if ( ! empty( $_link_url ) ) {
		$output .= '</a>';
	}
	// Output comma after anchor except the last one
	if ( $style != 'badge' AND $i != count( $taxonomies ) ) {
		$output .= $separator;
	}
	$i++;
}

$output .= '</div>';

echo $output;
