<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['type'] string Separator type: 'default' / 'fullwidth' / 'short' / 'invisible'
 * @param $atts           ['size'] string Separator size: 'small' / 'medium' / 'large' / 'huge'
 * @param $atts           ['thick'] string Line thickness: '1' / '2' / '3' / '4' / '5'
 * @param $atts           ['style'] string Line style: 'solid' / 'dashed' / 'dotted' / 'double'
 * @param $atts           ['color'] string Color style: 'border' / 'primary' / 'secondary' / 'custom'
 * @param $atts           ['bdcolor'] string Border color value
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['text'] string Title
 * @param $atts           ['title_tag'] string Title HTML tag: 'h1' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'div'
 * @param $atts           ['title_size'] string Title Size
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_separator' );

$classes = $inner_html = $inline_css = '';

$classes .= ' type_' . $atts['type'];
$classes .= ' size_' . $atts['size'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['type'] != 'invisible' ) {
	$classes .= ' thick_' . $atts['thick'];
	$classes .= ' style_' . $atts['style'];
	$classes .= ' color_' . $atts['color'];

	$atts['icon'] = trim( $atts['icon'] );
	if ( ! empty( $atts['icon'] ) ) {
		$classes .= ' cont_icon';
		$inner_html = us_prepare_icon_tag( $atts['icon'] );
	} elseif ( ! empty( $atts['text'] ) ) {
		$classes .= ' cont_text';
		$title_inline_css = us_prepare_inline_css(
			array(
				'font-size' => $atts['title_size'],
			)
		);
		$inner_html = '<' . $atts['title_tag'] . $title_inline_css . '>' . $atts['text'] . '</' . $atts['title_tag'] . '>';
	} else {
		$classes .= ' cont_none';
	}

	$inline_css = us_prepare_inline_css(
		array(
			'border-color' => $atts['bdcolor'],
			'color' => $atts['bdcolor'],
		)
	);
}

// Output the element
$output = '<div class="w-separator' . $classes . '"' . $inline_css . '>';
if ( $atts['type'] != 'invisible' ) {
	$output .= '<div class="w-separator-h">' . $inner_html . '</div>';
}
$output .= '</div>';

echo $output;
