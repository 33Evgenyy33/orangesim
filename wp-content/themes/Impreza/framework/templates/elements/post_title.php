<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Title element
 *
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link string
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$classes = isset( $classes ) ? $classes : '';
$classes .= ' entry-title'; // needed for Google structured data

// Generate anchor semantics
$_link_url = $_link_meta = '';
if ( $link === 'post' ) {
	$_link_url = apply_filters( 'the_permalink', get_permalink() );
	$_link_meta = ' rel="bookmark"';
	// Force opening in a new tab for "Link" post format
	if ( get_post_format() == 'link' ) {
		$_link_meta .= ' target="_blank"';
	}
} elseif ( $link === 'custom' ) {
	$link_atts = usof_get_link_atts( $custom_link );
	$_link_url = $link_atts['href'];
	$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
}

// Output the element
$output = '<' . $tag . ' class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $_link_url ) ) {
	$output .= '<a href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
}

$output .= get_the_title();

if ( ! empty( $_link_url ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
