<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output button element
 *
 * @var $label            string
 * @var $link_type        string Used in Grid builder
 * @var $link             string
 * @var $style            string
 * @var $icon             string
 * @var $iconpos          string
 * @var $size             string
 * @var $size_tablets     string
 * @var $size_mobiles     string
 * @var $color_bg         string
 * @var $color_hover_bg   string
 * @var $color_text       string
 * @var $color_hover_text string
 * @var $design_options   array
 * @var $classes          string
 * @var $id               string
 */

// .w-btn-wrapper additional classes
$classes = isset( $classes ) ? $classes : '';

$inner_classes = ' style_' . $style;
if ( isset( $color ) ) {
	$inner_classes .= ' color_' . $color;
} else {
	$inner_classes .= ' color_custom';
}

$icon_html = '';
if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$inner_classes .= ' icon_at' . $iconpos;
} else {
	$inner_classes .= ' icon_none';
}

if ( isset( $link_type ) AND $link_type === 'post' ) {
	$link_atts['href'] = apply_filters( 'the_permalink', get_permalink() );
} elseif ( empty( $link_type ) OR $link_type === 'custom' ) {
	$link_atts = usof_get_link_atts( $link );
	if ( ! isset( $link_atts['href'] ) ) {
		$link_atts['href'] = '';
	}
	if ( ! empty( $link_atts['href'] ) AND strpos( $link_atts['href'], '[lang]' ) !== FALSE ) {
		$link_atts['href'] = str_replace( '[lang]', usof_get_lang(), $link_atts['href'] );
	}
} else { //elseif ( $link_type == 'none' )
	$link_atts['href'] = '';
}

$output = '<div class="w-btn-wrapper' . $classes . '">';
$output .= '<a class="w-btn' . $inner_classes . '" href="' . esc_url( $link_atts['href'] ) . '"';
if ( ! empty( $link_atts['target'] ) ) {
	$output .= ' target="' . esc_attr( $link_atts['target'] ) . '"';
}
$output .= '>';
$output .= $icon_html;
$output .= '<span class="w-btn-label">' . $label . '</span>';
$output .= '</a></div>';

echo $output;
