<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Comments element
 *
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link string
 * @var $icon string Icon name
 * @var $color string Custom color
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( get_post_format() == 'link' OR ! comments_open() ) {
	return;
}

$comments_number = get_comments_number();

$_link_url = $_link_meta = '';
if ( $link === 'post' ) {
	ob_start();
	comments_link();
	$_link_url = ob_get_clean();
} elseif ( $link === 'custom' ) {
	$link_atts = usof_get_link_atts( $custom_link );
	$_link_url = $link_atts['href'];
	$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
}

$classes = isset( $classes ) ? $classes : '';

$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $_link_url ) ) {
	$output .= '<a href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
}

ob_start();
$comments_label = sprintf( us_translate_n( '%s <span class="screen-reader-text">Comment</span>', '%s <span class="screen-reader-text">Comments</span>', $comments_number ), $comments_number );
comments_number( us_translate( 'No Comments' ), $comments_label, $comments_label );
$output .= ob_get_clean();

if ( ! empty( $_link_url ) ) {
	$output .= '</a>';
}

$output .= '</div>';

echo $output;
