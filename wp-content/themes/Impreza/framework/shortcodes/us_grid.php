<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_grid
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts ['post_type'] string WordPress post type name to show
 * @param $atts ['ignore_sticky'] bool Ignore sticky posts
 * @param $atts ['post_categories'] array
 * @param $atts ['us_portfolio_categories'] array
 * @param $atts ['us_testimonial_categories'] array
 * @param $atts ['product_categories'] array
 * @param $atts ['type'] string Display items as: 'grid' / 'masonry' / 'carousel'
 * @param $atts ['orderby'] string Order by: 'date' / 'date_asc' / 'modified' / 'modified_asc' / 'alpha' / 'rand'
 * @param $atts ['columns'] int Columns number: 1 / 2 / 3 / 4 / 5 / 6
 * @param $atts ['items_gap'] int Gap between items, ex: '10px' / '1em' / '3%'
 * @param $atts ['items_quantity'] int
 * @param $atts ['items_offset'] int Offset quantity
 * @param $atts ['pagination'] string Pagination: 'none' / 'regular' / 'ajax' / 'infinite'
 * @param $atts ['pagination_btn_text'] string
 * @param $atts ['items_layout'] string|int Grid layout
 * @param $atts ['img_size'] string
 * @param $atts ['items_action']
 * @param $atts ['popup_width']
 * @param $atts ['el_class'] string Additional class name
 * @param $atts ['carousel_arrows']
 * @param $atts ['carousel_dots']
 * @param $atts ['carousel_center']
 * @param $atts ['carousel_slideby']
 * @param $atts ['carousel_autoplay']
 * @param $atts ['carousel_interval']
 * @param $atts ['filter']
 * @param $atts ['filter_style']
 * @param $atts ['filter_align']
 * @param $atts ['breakpoint_1_width']
 * @param $atts ['breakpoint_1_cols']
 * @param $atts ['breakpoint_1_autoplay']
 * @param $atts ['breakpoint_2_width']
 * @param $atts ['breakpoint_2_cols']
 * @param $atts ['breakpoint_2_autoplay']
 * @param $atts ['breakpoint_3_width']
 * @param $atts ['breakpoint_3_cols']
 * @param $atts ['breakpoint_3_autoplay']
 * @param $atts ['ids'] Temporary field for backward compatibility with testimonials
 */

// If we are running US Grid loop already, return nothing
global $us_grid_loop_running;
if ( isset( $us_grid_loop_running ) AND $us_grid_loop_running ) {
	return false;
}
$us_grid_loop_running = TRUE;

$atts = us_shortcode_atts( $atts, 'us_grid' );

// Grid indexes, start from 1
global $us_grid_index;
$us_grid_index = isset( $us_grid_index ) ? ( $us_grid_index + 1 ) : 1;

// Get the page we are on for AJAX calls
global $us_is_in_footer, $us_footer_id;
if ( isset( $us_is_in_footer ) AND $us_is_in_footer AND ! empty( $us_footer_id ) ) {
	$post_id = $us_footer_id;
} else {
	$post_id = get_the_ID();
}

// Preparing the query
$query_args = array();
if ( ! empty( $atts['post_type'] ) ) {
	$query_args['post_type'] = explode( ',', $atts['post_type'] );
}
if ( ! empty( $atts['ignore_sticky'] ) AND $atts['ignore_sticky'] ) {
	$query_args['ignore_sticky_posts'] = 1;
}

// Posts from selected taxonomies
$known_post_type_taxonomies = array(
	'post' => 'category',
	'us_portfolio' => 'us_portfolio_category',
	'us_testimonial' => 'us_testimonial_category',
	'product' => 'product_cat',
);
if ( ! empty( $atts[$atts['post_type'] .'_categories'] ) ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $known_post_type_taxonomies[$atts['post_type']],
			'field' => 'slug',
			'terms' => explode(',', $atts[$atts['post_type'] .'_categories']),
		),
	);
}

// Set posts order
$orderby_translate = array(
	'date' => 'date',
	'date_asc' => 'date',
	'modified' => 'modified',
	'modified_asc' => 'modified_asc',
	'alpha' => 'title',
	'rand' => 'rand',
);
$order_translate = array(
	'date' => 'DESC',
	'date_asc' => 'ASC',
	'modified' => 'DESC',
	'modified_asc' => 'ASC',
	'alpha' => 'ASC',
	'rand' => '',
);
$orderby = in_array( $atts['orderby'], array(
	'date',
	'date_asc',
	'modified',
	'modified_asc',
	'alpha',
	'rand',
) ) ? $atts['orderby'] : 'date';
if ( $orderby == 'rand' ) {
	$query_args['orderby'] = 'rand';
} else {
	$query_args['orderby'] = array(
		$orderby_translate[$orderby] => $order_translate[$orderby],
	);
}

// Exclude current post from grid
if ( is_singular() ) {
	$current_ID = get_the_ID();
	if ( ! empty( $current_ID ) ) {
		$query_args['post__not_in'] = array( $current_ID );
	}
}

// Posts per page
if ( $atts['items_quantity'] < 1 ) {
	if ( ! empty( $atts['items_offset'] ) ) {
		$atts['items_quantity'] = 1000000;
	} else {
		$atts['items_quantity'] = -1;
	}
}
$query_args['posts_per_page'] = $atts['items_quantity'];

// Current page
if ( $atts['pagination'] == 'regular' ) {
	$request_paged = is_front_page() ? 'page' : 'paged';
	if ( get_query_var( $request_paged ) ) {
		$query_args['paged'] = get_query_var( $request_paged );
	}
}

if ( ! empty( $atts['ids'] ) ) {
	$ids = explode( ',', $atts['ids'] );
	$query_args['post__in'] = $ids;
	$query_args['posts_per_page'] = count($ids);
}

// Providing proper post statuses
$query_args['post_status'] = array( 'publish' => 'publish' );
$query_args['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
// Add private states if user is capable to view them
if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
	$query_args['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
}
$query_args['post_status'] = array_values( $query_args['post_status'] );

// Filters data
$filter = ! empty( $atts['filter'] ) ? $atts['filter'] : 'none';
$filter_taxonomy_name = $filter_default_taxonomies = '';
$filter_taxonomies = array();

if ( $filter == 'category' AND $atts['type'] != 'carousel' ) {
	if ( $atts['post_type'] != 'post' ) {
		$filter_taxonomy_name = $known_post_type_taxonomies[$atts['post_type']];
	}
	$tems_args = array(
		'hierarchical' => FALSE,
		'taxonomy' => $known_post_type_taxonomies[$atts['post_type']],
	);
	if ( ! empty( $atts[$atts['post_type'] .'_categories'] ) ) {
		$tems_args['slug'] = explode(',', $atts[$atts['post_type'] .'_categories']);
	}
	$filter_taxonomies = get_terms( $tems_args );
	if ( ! empty( $atts[$atts['post_type'] .'_categories'] ) ) {
		$filter_default_taxonomies = $atts[$atts['post_type'] .'_categories'];
	}
}

// Load Grid Listing template with given params
$template_vars = array(
	'query_args' => $query_args,
	'us_grid_index' => $us_grid_index,
	'post_id' => $post_id,
	'post_type' => $atts['post_type'],
	'img_size' => $atts['img_size'],
	'type' => $atts['type'],
	'columns' => $atts['columns'],
	'items_offset' => $atts['items_offset'],
	'items_layout' => $atts['items_layout'],
	'items_gap' => $atts['items_gap'],
	'pagination' => $atts['pagination'],
	'pagination_btn_text' => $atts['pagination_btn_text'],
	'carousel_arrows' => $atts['carousel_arrows'],
	'carousel_dots' => $atts['carousel_dots'],
	'carousel_center' => $atts['carousel_center'],
	'carousel_slideby' => $atts['carousel_slideby'],
	'carousel_autoplay' => $atts['carousel_autoplay'],
	'carousel_interval' => $atts['carousel_interval'],
	'breakpoint_1_width' => $atts['breakpoint_1_width'],
	'breakpoint_1_cols' => $atts['breakpoint_1_cols'],
	'breakpoint_1_autoplay' => $atts['breakpoint_1_autoplay'],
	'breakpoint_2_width' => $atts['breakpoint_2_width'],
	'breakpoint_2_cols' => $atts['breakpoint_2_cols'],
	'breakpoint_2_autoplay' => $atts['breakpoint_2_autoplay'],
	'breakpoint_3_width' => $atts['breakpoint_3_width'],
	'breakpoint_3_cols' => $atts['breakpoint_3_cols'],
	'breakpoint_3_autoplay' => $atts['breakpoint_3_autoplay'],
	'filter' => $filter,
	'filter_style' => $atts['filter_style'],
	'filter_align' => $atts['filter_align'],
	'filter_taxonomy_name' => $filter_taxonomy_name,
	'filter_default_taxonomies' => $filter_default_taxonomies,
	'filter_taxonomies' => $filter_taxonomies,
	'el_class' => $atts['el_class'],
);
us_load_template( 'templates/us_grid/listing', $template_vars );

$us_grid_loop_running = FALSE;
