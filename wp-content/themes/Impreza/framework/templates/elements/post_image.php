<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Image element
 *
 * @var $thumbnail_size string Image WordPress size
 * @var $placeholder bool Use placeholder if post has no thumbnail?
 * @var $media_preview bool Show media preview for video and gallery posts?
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link string
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

global $us_template_directory_uri, $us_grid_img_size, $_wp_additional_image_sizes, $us_post_img_ratio, $us_post_slider_size;

$classes = isset( $classes ) ? $classes : '';
$classes .= ( isset( $circle ) AND $circle ) ? ' as_circle' : '';
$post_format = get_post_format() ? get_post_format() : 'standard';

// Overwrite thumbnail_size from [us_grid] shortcode if set
if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
	$thumbnail_size = $us_grid_img_size;
}

// Calculate aspect ratio for media preview and for placeholder
if ( isset( $_wp_additional_image_sizes[ $thumbnail_size ] ) AND $_wp_additional_image_sizes[ $thumbnail_size ]['width'] != 0 AND $_wp_additional_image_sizes[ $thumbnail_size ]['height'] != 0 ) {
	$us_post_img_ratio = number_format( $_wp_additional_image_sizes[ $thumbnail_size ]['height'] / $_wp_additional_image_sizes[ $thumbnail_size ]['width'] * 100, 4 );
}

// Generate anchor semantics
$_link_url = $_link_meta = $_post_preview = '';
if ( $link === 'post' ) {
	$_link_url = apply_filters( 'the_permalink', get_permalink() );
	$_link_meta = ' rel="bookmark"';
	// Force opening in a new tab for "Link" post format
	if ( $post_format == 'link' ) {
		$_link_meta .= ' target="_blank"';
	}
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
$_link_meta .= ' aria-label="' . esc_attr( strip_tags( get_the_title() ) ) . '"'; // needed for accessibility support

// Generate media preview for Video, Audio, Gallery formats
if ( $media_preview AND ! post_password_required() ) {
	$us_post_slider_size = $thumbnail_size; // set slider images size for media preview

	$the_content = get_the_content();
	$_post_preview = us_get_post_preview( $the_content, TRUE );

	if ( $_post_preview != '' ) {
		$classes .= ' media_preview'; // add CSS class for media preview
		$_link_url = $_link_meta = ''; // remove link for media preview
	}
}

// Output Featured image
if ( $_post_preview == '' AND has_post_thumbnail() ) {
	$_post_preview = get_the_post_thumbnail( get_the_ID(), $thumbnail_size );
}

// Output the first image from the content of Gallery format
if ( $_post_preview == '' AND $post_format == 'gallery' ) {
	$the_content = get_the_content();
	if ( preg_match( '~\[us_gallery.+?\]|\[us_image_slider.+?\]|\[gallery.+?\]~', $the_content, $matches ) ) {
		$gallery = preg_replace( '~(vc_gallery|us_gallery|gallery)~', 'us_image_slider', $matches[0] );
		preg_match( '~\[us_image_slider(.+?)\]~', $gallery, $matches2 );
		$shortcode_atts = shortcode_parse_atts( $matches2[1] );
		if ( ! empty( $shortcode_atts['ids'] ) ) {
			$ids = explode( ',', $shortcode_atts['ids'] );
			if ( count( $ids ) > 0 ) {
				$_post_preview = wp_get_attachment_image( $ids[0], $thumbnail_size );
			}
		}
	}
}

// Output placeholder if enabled
if ( $_post_preview == '' AND $placeholder ) {
	$_post_preview ='<div class="w-grid-item-placeholder" style="padding-bottom:' . ( empty( $us_post_img_ratio ) ? 100 : $us_post_img_ratio ) . '%;"></div>';
}

// Don't output the element without any content
if ( $_post_preview == '' ) {
	return FALSE;
}

$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $_link_url ) ) {
	$output .= '<a href="' . esc_url( $_link_url ) . '"' . $_link_meta . '>';
}

$output .= $_post_preview;

if ( ! empty( $_link_url ) ) {
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
