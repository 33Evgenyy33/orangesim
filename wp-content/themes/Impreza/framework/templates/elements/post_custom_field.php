<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Custom Field element
 *
 * @var $key string custom field key
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link string
 * @var $type string 'text' / 'image'
 * @var $thumbnail_size string Image WordPress size
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$postID = get_the_ID();
if ( ! $postID ) {
	return FALSE;
}

// Retrieve meta key value
if ( $key != 'custom' ) {
	$value = get_post_meta( $postID, $key, TRUE );
} elseif ( ! empty( $custom_key ) ) {
	$value = get_post_meta( $postID, $custom_key, TRUE );
} else {
	$value = '';
}

$type = 'text';

// Force "image" type for relevant meta keys
if ( in_array( $key, array( 'us_titlebar_image', 'us_tile_additional_image' ) ) ) {
	$type = 'image';
}

// Generate image semantics
if ( $type == 'image' ) {
	$value = intval( $value );

	if ( $value ) {
		global $us_grid_img_size;
		if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
			$thumbnail_size = $us_grid_img_size;
		}

		$image = wp_get_attachment_image_src( $value, $thumbnail_size );

		if ( is_array( $image ) ) {
			$value = wp_get_attachment_image( $value, $thumbnail_size );
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

$classes = isset( $classes ) ? $classes : '';
$classes .= isset( $type ) ? ( ' type_' . $type ) : '';
$tag = 'div';

// Generate anchor semantics
$_link_url = $_link_meta = '';
if ( $link === 'post' ) {
	$_link_url = apply_filters( 'the_permalink', get_permalink() );
} elseif ( $link === 'custom' ) {
	$link_atts = usof_get_link_atts( $custom_link );
	$_link_url = $link_atts['href'];
	$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
	if ( $_link_url == '{{us_testimonial_link}}' || $_link_url == '{{us_tile_link}}' ) {
		$_field = ( $_link_url == '{{us_testimonial_link}}' ) ? 'us_testimonial_link' : 'us_tile_link' ;
		$postID = get_the_ID();
		$link_atts = get_post_meta( $postID, $_field, TRUE );
		$link_atts = usof_get_link_atts( json_decode( $link_atts, TRUE ) );
		$_link_url = ( ! empty( $link_atts['href'] ) ) ? $link_atts['href'] : '';
		$_link_meta = ( ! empty( $link_atts['target'] ) ) ? ' target="' . esc_attr( $link_atts['target'] ) . '"' : '';
		$_link_meta .= ' rel="nofollow"'; // force "nofollow" for metabox URLs
	}
}

// Output the element
$output = '<' . $tag . ' class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $_link_url ) ) {
	$output .= '<a href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
}

$output .= $value;

if ( ! empty( $_link_url ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
