<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Author element
 *
 * @var $link string Link type: 'post' / 'author' / 'custom' / 'none'
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
$classes .= ' vcard author'; // needed for Google structured data

$_link_url = $_link_meta = '';
if ( $link === 'author_page' ) {
	$_link_url = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
} elseif ( $link === 'author_website' ) {
	$_link_url = ( get_the_author_meta('url') ) ? get_the_author_meta('url') : '';
	$_link_meta = ' target="_blank"';
} elseif ( $link === 'post' ) {
	$_link_url = apply_filters( 'the_permalink', get_permalink() );
} elseif ( $link === 'custom' ) {
	$link_atts = usof_get_link_atts( $custom_link );
	$_link_url = $link_atts['href'];
	$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
}

$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $_link_url ) ) {
	$output .= '<a class="fn" href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
}

$output .= get_the_author();

if ( ! empty( $_link_url ) ) {
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
