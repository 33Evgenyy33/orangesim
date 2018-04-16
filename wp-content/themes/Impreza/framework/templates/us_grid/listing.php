<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a single Grid listing. The universal template that is used by all the possible Grid listings.
 *
 * (!) $query_args should be filtered before passing to this template.
 *
 * @var $query_args                   array Arguments for the new WP_Query. If not set, current global $wp_query will be used instead.
 * @var $us_grid_index                int Grid element number on page
 * @var $post_id                      int post or page where Grid element is placed
 * @var $post_type                    string WordPress post type name to show
 * @var $type                         string layout type: 'grid' / 'masonry' / 'carousel'
 * @var $columns                      int Columns quantity
 * @var $items_offset                 int Items to skip
 * @var $items_layout                 string|int Grid Layout ID
 * @var $items_gap                    string Gap between items, ex: '10px' / '1em' / '3%'
 * @var $pagination                   string Pagination type: regular / none / ajax / infinite
 * @var $pagination_btn_text          string
 * @var $carousel_arrows              bool used in Carousel type
 * @var $carousel_dots                bool used in Carousel type
 * @var $carousel_center              bool used in Carousel type
 * @var $carousel_autoplay            bool used in Carousel type
 * @var $carousel_interval            bool used in Carousel type
 * @var $carousel_slideby             bool used in Carousel type
 * @var $breakpoint_1_width           int responsive option
 * @var $breakpoint_1_cols            int responsive option
 * @var $breakpoint_1_autoplay        int responsive option
 * @var $breakpoint_2_width           int responsive option
 * @var $breakpoint_2_cols            int responsive option
 * @var $breakpoint_2_autoplay        int responsive option
 * @var $breakpoint_3_width           int responsive option
 * @var $breakpoint_3_cols            int responsive option
 * @var $breakpoint_3_autoplay        int responsive option
 * @var $filter                       string Filter type: 'none' / 'category'
 * @var $filter_style                 string Filter Bar style: 'style_1' / 'style_2' / ... / 'style_N
 * @var $filter_align                 string Filter Bar Alignment: 'left' / 'center' / 'right'
 * @var $filter_taxonomy_name         string Name of taxonomy to filter by
 * @var $filter_default_taxonomies    string Default taxonomy(ies) for 'All' filter state
 * @var $filter_taxonomies            array List of taxonomies to filter by
 * @var $img_size                     string featured image size
 * @var $el_class                     string Additional classes that will be appended to the main .w-grid container
 * @var $grid_elm_id                  string DOM element ID
 * @var $is_widget                    bool if used in widget
 *
 * @action Before the template: 'us_before_template:templates/us_grid/listing'
 * @action After the template: 'us_after_template:templates/us_grid/listing'
 * @filter Template variables: 'us_template_vars:templates/us_grid/listing'
 */

// Variables defaults
$classes = $list_classes = $css_code = $data_atts = '';

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$post_id = isset( $post_id ) ? $post_id : NULL;
$post_type = isset( $post_type ) ? $post_type : 'post';
$type = isset( $type ) ? $type : 'grid';
$columns = isset( $columns ) ? intval( $columns ) : 2;
$items_gap = isset( $items_gap ) ? $items_gap : '';
$items_offset = isset( $items_offset ) ? $items_offset : NULL;
$items_layout = isset( $items_layout ) ? $items_layout : 'blog_classic';
$img_size = isset( $img_size ) ? $img_size : 'default';
$el_class = isset( $el_class ) ? $el_class : '';
$is_widget = isset( $is_widget ) ? $is_widget : FALSE;
$filter = isset( $filter ) ? $filter : 'none';
$filter_style = isset( $filter_style ) ? $filter_style : 'style_1';
$filter_align = isset( $filter_align ) ? $filter_align : 'center';

$breakpoint_1_width = isset( $breakpoint_1_width ) ? $breakpoint_1_width : us_get_option( 'blog_breakpoint_1_width' );
$breakpoint_1_cols = isset( $breakpoint_1_cols ) ? $breakpoint_1_cols : us_get_option( 'blog_breakpoint_1_cols' );
$breakpoint_2_width = isset( $breakpoint_2_width ) ? $breakpoint_2_width : us_get_option( 'blog_breakpoint_2_width' );
$breakpoint_2_cols = isset( $breakpoint_2_cols ) ? $breakpoint_2_cols : us_get_option( 'blog_breakpoint_2_cols' );
$breakpoint_3_width = isset( $breakpoint_3_width ) ? $breakpoint_3_width : us_get_option( 'blog_breakpoint_3_width' );
$breakpoint_3_cols = isset( $breakpoint_3_cols ) ? $breakpoint_3_cols : us_get_option( 'blog_breakpoint_3_cols' );

if ( ! isset( $grid_elm_id ) OR empty( $grid_elm_id ) ) {
	$grid_elm_id = 'us_grid_' . $us_grid_index;
}

// Setting global variable for Image size to use in grid elements
if ( ! empty( $img_size ) AND $img_size != 'default' ) {
	global $us_grid_img_size;
	$us_grid_img_size = $img_size;
}

// Global preloader type
$preloader_type = us_get_option( 'preloader' );
if ( ! in_array( $preloader_type, us_get_preloader_numeric_types() ) ) {
	$preloader_type = 1;
}

// Additional classes for "w-grid"
$classes .= ' type_' . $type;
$classes .= ' layout_' . $items_layout;
if ( $columns != 1 AND $type != 'carousel' ) {
	$classes .= ' cols_' . $columns;
}
if ( $pagination == 'regular' ) {
	$classes .= ' with_pagination';
}
if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

// Determine Grid Layout
if ( ! empty( $items_layout ) ) {
	if ( $templates_config = us_config( 'grid-templates', array(), TRUE ) AND isset( $templates_config[$items_layout] ) ) {
		$grid_layout_settings = us_fix_grid_settings( $templates_config[$items_layout] );
	} elseif ( $grid_layout = get_post( (int) $items_layout ) ) {
		if ( $grid_layout instanceof WP_Post AND $grid_layout->post_type === 'us_grid_layout' ) {
			if ( ! empty( $grid_layout->post_content ) AND substr( strval( $grid_layout->post_content ), 0, 1 ) === '{' ) {
				try {
					$grid_layout_settings = json_decode( $grid_layout->post_content, TRUE );
				}
				catch ( Exception $e ) {
				}
			}
		}
	}
}

if ( ! isset( $grid_layout_settings ) OR empty( $grid_layout_settings ) ) {
	echo 'No Grid Layout';
	return;
}

if ( us_arr_path( $grid_layout_settings, 'default.options.fixed' ) ) {
	$classes .= ' height_fixed';
} elseif ( us_arr_path( $grid_layout_settings, 'default.options.overflow' ) ) {
	$classes .= ' overflow_hidden';
}
if ( us_arr_path( $grid_layout_settings, 'default.options.link' ) == 'popup_post' ) {
	$classes .= ' lightbox_page';
}

// Set items offset to WP Query flow
if ( ! empty( $items_offset ) AND abs( intval( $items_offset ) ) > 0 ) {
	global $us_grid_items_offset;
	$us_grid_items_offset = abs( intval( $items_offset ) );
	$query_args['_id'] = 'us_grid';
	add_action( 'pre_get_posts', 'us_grid_query_offset', 1 );
	add_filter( 'found_posts', 'us_grid_adjust_offset_pagination', 1, 2 );
}

// Filter and execute database query
global $wp_query;
$use_custom_query = isset( $query_args ) AND is_array( $query_args ) AND ! empty( $query_args );
if ( $use_custom_query ) {
	us_open_wp_query_context();
	$wp_query = new WP_Query( $query_args );
} else {
	$query_args = $wp_query->query;

	// Extracting query arguments from WP_Query that are not shown but relevant
	if ( ! isset( $query_args['post_type'] ) AND preg_match_all( '~\.post_type = \'([a-z0-9\_\-]+)\'~', $wp_query->request, $matches ) ) {
		$query_args['post_type'] = $matches[1];
	}
	if ( ! isset( $query_args['post_status'] ) AND preg_match_all( '~\.post_status = \'([a-z]+)\'~', $wp_query->request, $matches ) ) {
		$query_args['post_status'] = $matches[1];
	}
}

if ( ! have_posts() ) {
	echo us_translate( 'No results found.' );
	if ( $use_custom_query ) {
		us_close_wp_query_context();
	}

	return;
}

// Output filtration by taxonomy
$filter_html = '';
if ( $filter != 'none' AND $type != 'carousel' AND $pagination != 'regular' AND ! $is_widget ) {

	// $categories_names already contains only the used categories
	if ( count( $filter_taxonomies ) > 1 ) {
		$classes .= ' with_filters';

		$filter_html .= '<div class="g-filters ' . $filter_style . ' align_' . $filter_align . '"><div class="g-filters-list">';
		$filter_html .= '<a class="g-filters-item active" href="javascript:void(0)" data-taxonomy="*"><span>' . __( 'All', 'us' ) . '</span></a>';
		foreach ( $filter_taxonomies as $filter_taxonomiy ) {
			$filter_html .= '<a class="g-filters-item" href="javascript:void(0)" data-taxonomy="' . $filter_taxonomiy->slug . '"><span>' . $filter_taxonomiy->name . '</span></a>';
		}
		$filter_html .= '</div></div>';

		$data_atts .= ' data-filter_taxonomy_name="' . $filter_taxonomy_name . '"';
		if ( ! empty( $filter_default_taxonomies ) ) {
			$data_atts .= ' data-filter_default_taxonomies="' . $filter_default_taxonomies . '"';
		}
	}
}

// Apply isotope script for filter and masonry
if ( ! empty( $filter_html ) OR ( $type == 'masonry' AND $columns > 1 ) ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-isotope' );
	}
	$classes .= ' with_isotope';

	if ( $type == 'grid' ) {
		$classes .= ' isotope_fit_rows';
	}
}

// Output attributes for Carousel type
if ( $type == 'carousel' ) {

	// We need owl script for this
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-owl' );
	}

	$data_atts .= ' data-breakpoint_1_width="' . intval( $breakpoint_1_width ) . '"';
	$data_atts .= ' data-breakpoint_1_cols="' . intval( $breakpoint_1_cols ) . '"';
	$data_atts .= ' data-breakpoint_1_autoplay="' . intval( ! ! $breakpoint_1_autoplay ) . '"';
	$data_atts .= ' data-breakpoint_2_width="' . intval( $breakpoint_2_width ) . '"';
	$data_atts .= ' data-breakpoint_2_cols="' . intval( $breakpoint_2_cols ) . '"';
	$data_atts .= ' data-breakpoint_2_autoplay="' . intval( ! ! $breakpoint_2_autoplay ) . '"';
	$data_atts .= ' data-breakpoint_3_width="' . intval( $breakpoint_3_width ) . '"';
	$data_atts .= ' data-breakpoint_3_cols="' . intval( $breakpoint_3_cols ) . '"';
	$data_atts .= ' data-breakpoint_3_autoplay="' . intval( ! ! $breakpoint_3_autoplay ) . '"';

	$data_atts .= ' data-items="' . $columns . '"';
	$data_atts .= ' data-nav="' . intval( ! ! $carousel_arrows ) . '"';
	$data_atts .= ' data-dots="' . intval( ! ! $carousel_dots ) . '"';
	$data_atts .= ' data-center="' . intval( ! ! $carousel_center ) . '"';
	$data_atts .= ' data-autoplay="' . intval( ! ! $carousel_autoplay ) . '"';
	$data_atts .= ' data-timeout="' . intval( $carousel_interval * 1000 ) . '"';
	$data_atts .= ' data-autoheight="' . intval( $columns == 1 ) . '"';
	if ( $carousel_slideby ) {
		$data_atts .= ' data-slideby="page"';
	} else {
		$data_atts .= ' data-slideby="1"';
	}

	$list_classes = ' owl-carousel';
}

// Generate items gap via CSS
if ( ! empty( $items_gap ) ) {
	if ( $columns != 1 ) {
		if ( ! empty( $filter_html ) AND $pagination == 'none' ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( ! empty( $filter_html ) AND $pagination != 'none' ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination == 'none' ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination != 'none' ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . ' -' . $items_gap . ' ' . $items_gap . '}';
		}
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item { padding: ' . $items_gap . '}';
		// Force left & right gaps for grid-list in fullwidth section
		$css_code .= '.l-section.width_full .vc_col-sm-12 #' . $grid_elm_id . ' .w-grid-list { margin-left: ' . $items_gap . '; margin-right: ' . $items_gap . '}';
		// Force top & bottom gaps for grid-list in fullheight section
		$css_code .= '.l-section.height_auto .vc_col-sm-12 #' . $grid_elm_id . ' .w-grid-list { margin-top: ' . $items_gap . '; margin-bottom: ' . $items_gap . '}';
	} elseif ( $type != 'carousel' ) {
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item { margin-bottom: ' . $items_gap . '}';
	}
}

// Calculate items aspect ratio
$grid_elm_ratio_w = $grid_elm_ratio_h = 1;
$grid_elm_ratio = us_arr_path( $grid_layout_settings, 'default.options.ratio' );
if ( $grid_elm_ratio == '4x3' ) {
	$grid_elm_ratio_w = 4;
	$grid_elm_ratio_h = 3;
} elseif ( $grid_elm_ratio == '3x2' ) {
	$grid_elm_ratio_w = 3;
	$grid_elm_ratio_h = 2;
} elseif ( $grid_elm_ratio == '2x3' ) {
	$grid_elm_ratio_w = 2;
	$grid_elm_ratio_h = 3;
} elseif ( $grid_elm_ratio == '3x4' ) {
	$grid_elm_ratio_w = 3;
	$grid_elm_ratio_h = 4;
} elseif ( $grid_elm_ratio == '16x9' ) {
	$grid_elm_ratio_w = 16;
	$grid_elm_ratio_h = 9;
} elseif ( $grid_elm_ratio == 'custom' ) {
	$grid_elm_ratio_w = us_arr_path( $grid_layout_settings, 'default.options.ratio_width' ) ? us_arr_path( $grid_layout_settings, 'default.options.ratio_width' ) : 1;
	$grid_elm_ratio_h = us_arr_path( $grid_layout_settings, 'default.options.ratio_height' ) ? us_arr_path( $grid_layout_settings, 'default.options.ratio_height' ) : 1;
}
if ( us_arr_path( $grid_layout_settings, 'default.options.fixed' ) ) {

	// Apply grid item aspect ratio
	$css_code .= '#' . $grid_elm_id . ' .w-grid-item-h:before {';
	$css_code .= 'padding-bottom: ' . number_format( $grid_elm_ratio_h / $grid_elm_ratio_w * 100, 4 ) . '%}';

	// Fix aspect ratio regarding Portfolio custom size and items gap
	if ( empty( $items_gap ) ) {
		$items_gap = '0px'; // needed for CSS calc function
	}
	if ( $post_type == 'us_portfolio' AND $type != 'carousel' AND ! $is_widget ) {
		$css_code .= '@media (min-width:' . intval( $breakpoint_3_width ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_1x2 .w-grid-item-h:before {';
		$css_code .= 'padding-bottom: calc(' . ( $grid_elm_ratio_h * 2 ) / $grid_elm_ratio_w * 100 . '% + ' . $items_gap . ' + ' . $items_gap . ')}';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1 .w-grid-item-h:before {';
		$css_code .= 'padding-bottom: calc(' . $grid_elm_ratio_h / ( $grid_elm_ratio_w * 2 ) * 100 . '% - ' . $items_gap . ' * ' . $grid_elm_ratio_h / $grid_elm_ratio_w . ')}';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 .w-grid-item-h:before {';
		$css_code .= 'padding-bottom: calc(' . $grid_elm_ratio_h / $grid_elm_ratio_w * 100 . '% - ' . $items_gap . ' * ' . 2 * ( $grid_elm_ratio_h / $grid_elm_ratio_w - 1 ) . ')}';
		$css_code .= '}';
	}
}

// Generate columns responsive CSS
if ( $type != 'carousel' AND ! $is_widget ) {

	if ( $columns > intval( $breakpoint_1_cols ) ) {
		$css_code .= '@media (max-width:' . ( intval( $breakpoint_1_width ) - 1 ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_1_cols ) . '%}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_1_cols ) != 1 ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_code .= 'width:' . 200 / intval( $breakpoint_1_cols ) . '%}';
		}
		$css_code .= '}';
	}

	if ( $columns > intval( $breakpoint_2_cols ) ) {
		$css_code .= '@media (max-width:' . ( intval( $breakpoint_2_width ) - 1 ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_2_cols ) . '%}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_2_cols ) != 1 ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_code .= 'width:' . 200 / intval( $breakpoint_2_cols ) . '%}';
		}
		$css_code .= '}';
	}

	if ( $columns > intval( $breakpoint_3_cols ) ) {
		$css_code .= '@media (max-width:' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_3_cols ) . '%}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_3_cols ) != 1 ) {
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_code .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_code .= 'width:' . 200 / intval( $breakpoint_3_cols ) . '%}';
		}
		$css_code .= '}';
	}

}

// Generate Grid Layout settings CSS
$item_bg_color = us_arr_path( $grid_layout_settings, 'default.options.color_bg' );
$item_text_color = us_arr_path( $grid_layout_settings, 'default.options.color_text' );
$item_border_radius = us_arr_path( $grid_layout_settings, 'default.options.border_radius' );
$item_box_shadow = us_arr_path( $grid_layout_settings, 'default.options.box_shadow' );
$item_box_shadow_hover = us_arr_path( $grid_layout_settings, 'default.options.box_shadow_hover' );

$css_code .= '#' . $grid_elm_id . ' .w-grid-item-h {';
if ( ! empty( $item_bg_color ) ) {
	$css_code .= 'background-color:' . $item_bg_color . ';';
}
if ( ! empty( $item_text_color ) ) {
	$css_code .= 'color:' . $item_text_color . ';';
}
if ( ! empty( $item_border_radius ) ) {
	$css_code .= 'border-radius:' . $item_border_radius . 'rem;';
	$css_code .= 'z-index: 3;';
}
if ( ! empty( $item_box_shadow ) OR ! empty( $item_box_shadow_hover ) ) {
	$css_code .= 'box-shadow:';
	$css_code .= '0 ' . number_format( $item_box_shadow / 10, 2 ) . 'rem ' . number_format( $item_box_shadow / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_code .= '0 ' . number_format( $item_box_shadow / 3, 2 ) . 'rem ' . number_format( $item_box_shadow, 2 ) . 'rem rgba(0,0,0,0.1);';
	$css_code .= 'transition-duration: 0.3s;';
}
$css_code .= '}';

if ( $item_box_shadow_hover != $item_box_shadow ) {
	$css_code .= '.no-touch #' . $grid_elm_id . ' .w-grid-item-h:hover { box-shadow:';
	$css_code .= '0 ' . number_format( $item_box_shadow_hover / 10, 2 ) . 'rem ' . number_format( $item_box_shadow_hover / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_code .= '0 ' . number_format( $item_box_shadow_hover / 3, 2 ) . 'rem ' . number_format( $item_box_shadow_hover, 2 ) . 'rem rgba(0,0,0,0.15);';
	$css_code .= 'z-index: 4;';
	$css_code .= '}';
}

// Generate Grid Layout elements CSS
$css_data = array();
foreach ( $grid_layout_settings['data'] as $elm_id => $elm ) {
	$elm_class = 'usg_' . str_replace( ':', '_', $elm_id );

	// Elements settings
	$css_code .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
	$css_code .= ( isset( $elm['font_size'] ) AND ! empty( $elm['font_size'] ) ) ? 'font-size:' . $elm['font_size'] . ';' : '';
	$css_code .= ( isset( $elm['line_height'] ) AND ! empty( $elm['line_height'] ) ) ? 'line-height:' . $elm['line_height'] . ';' : '';
	$css_code .= ( isset( $elm['text_styles'] ) AND in_array( 'bold', $elm['text_styles'] ) ) ? 'font-weight: bold;' : '';
	$css_code .= ( isset( $elm['text_styles'] ) AND in_array( 'uppercase', $elm['text_styles'] ) ) ? 'text-transform: uppercase;' : '';
	$css_code .= ( isset( $elm['text_styles'] ) AND in_array( 'italic', $elm['text_styles'] ) ) ? 'font-style: italic;' : '';
	$css_code .= ( isset( $elm['width'] ) AND ! empty( $elm['width'] ) ) ? 'width:' . $elm['width'] . '; flex-shrink: 0;' : '';
	$css_code .= ( isset( $elm['border_radius'] ) AND ! empty( $elm['border_radius'] ) ) ? 'border-radius:' . $elm['border_radius'] . 'rem;' : '';
	$css_code .= ( isset( $elm['color_bg'] ) AND ! empty( $elm['color_bg'] ) ) ? 'background-color:' . $elm['color_bg'] . ';' : '';
	$css_code .= ( isset( $elm['color_border'] ) AND ! empty( $elm['color_border'] ) ) ? 'border-color:' . $elm['color_border'] . ';' : '';
	$css_code .= ( isset( $elm['color_text'] ) AND ! empty( $elm['color_text'] ) ) ? 'color:' . $elm['color_text'] . ';' : '';
	$css_code .= ( isset( $elm['bg_gradient'] ) AND $elm['bg_gradient'] AND isset( $elm['color_grad'] ) AND ! empty( $elm['color_grad'] ) ) ? 'background: linear-gradient( transparent, ' . $elm['color_grad'] . ');' : '';
	$css_code .= '}';
	if ( isset( $elm['font_size_mobiles'] ) AND ! empty( $elm['font_size_mobiles'] ) ) {
		$css_code .= '@media (max-width: ' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_code .= 'font-size:' . $elm['font_size_mobiles'] . ';';
		$css_code .= '}}';
	}
	if ( isset( $elm['line_height_mobiles'] ) AND ! empty( $elm['line_height_mobiles'] ) ) {
		$css_code .= '@media (max-width: ' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_code .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_code .= 'line-height:' . $elm['line_height_mobiles'] . ';';
		$css_code .= '}}';
	}

	// CSS of Hover effects
	if ( isset( $elm['hover'] ) AND $elm['hover'] ) {
		$css_code .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_code .= isset( $elm['transition_duration'] ) ? 'transition-duration:' . $elm['transition_duration'] . 's;' : '';
		if ( isset( $elm['scale'] ) AND isset( $elm['translateX'] ) AND isset( $elm['translateY'] ) ) {
			$css_code .= 'transform: scale(' . $elm['scale'] . ') translate(' . $elm['translateX'] . '%,' . $elm['translateY'] . '%);';
		}
		$css_code .= ( isset( $elm['opacity'] ) AND intval( $elm['opacity'] ) != 1 ) ? 'opacity:' . $elm['opacity'] . ';' : '';
		$css_code .= '}';

		$css_code .= '#' . $grid_elm_id . ' .w-grid-item-h:hover .' . $elm_class . '{';
		if ( isset( $elm['scale_hover'] ) AND isset( $elm['translateX_hover'] ) AND isset( $elm['translateY_hover'] ) ) {
			$css_code .= 'transform: scale(' . $elm['scale_hover'] . ') translate(' . $elm['translateX_hover'] . '%,' . $elm['translateY_hover'] . '%);';
		}
		$css_code .= isset( $elm['opacity_hover'] ) ? 'opacity:' . $elm['opacity_hover'] . ';' : '';
		$css_code .= ( isset( $elm['color_bg_hover'] ) AND ! empty( $elm['color_bg_hover'] ) ) ? 'background-color:' . $elm['color_bg_hover'] . ';' : '';
		$css_code .= ( isset( $elm['color_border_hover'] ) AND ! empty( $elm['color_border_hover'] ) ) ? 'border-color:' . $elm['color_border_hover'] . ';' : '';
		$css_code .= ( isset( $elm['color_text_hover'] ) AND ! empty( $elm['color_text_hover'] ) ) ? 'color:' . $elm['color_text_hover'] . ';' : '';
		$css_code .= '}';
	}

	// CSS Design Options
	if ( isset( $elm['design_options'] ) AND ! empty( $elm['design_options'] ) AND is_array( $elm['design_options'] ) ) {
		foreach ( $elm['design_options'] as $key => $value ) {
			if ( $value === '' ) {
				continue;
			}
			$key = explode( '_', $key );
			if ( ! isset( $css_data[ $key[2] ] ) ) {
				$css_data[ $key[2] ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ] ) ) {
				$css_data[ $key[2] ][ $elm_class ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ][ $key[0] ] ) ) {
				$css_data[ $key[2] ][ $elm_class ][ $key[0] ] = array();
			}
			$css_data[ $key[2] ][ $elm_class ][ $key[0] ][ $key[1] ] = $value;
		}
	}

}

foreach ( array( 'default' ) as $state ) {
	if ( ! isset( $css_data[ $state ] ) ) {
		continue;
	}
	foreach ( $css_data[ $state ] as $elm_class => $props ) {
		$css_code .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		foreach ( $props as $prop => $values ) {
			// Add absolute positioning if its values not empty
			if ( $prop === 'position' AND ! empty( $values ) ) {
				$css_code .= 'position: absolute;';
			}
			// Add solid border if its values not empty
			if ( $prop === 'border' AND ! empty( $values ) ) {
				$css_code .= 'border-style: solid; border-width: 0;';
			}
			if ( count( $values ) == 4 AND count( array_unique( $values ) ) == 1 AND $prop !== 'position' ) {
				// All the directions have the same value, so grouping them together
				$values = array_values( $values );

				if ( $prop === 'border' ) {
					$css_code .= $prop . '-width:' . $values[0] . ';';
				} else {
					$css_code .= $prop . ':' . $values[0] . ';';
				}
			} else {
				foreach ( $values as $dir => $val ) {
					if ( $prop === 'position' ) {
						$css_prop = $dir;
					} elseif ( $prop === 'border' ) {
						$css_prop = $prop . '-' . $dir . '-width';
					} else {
						$css_prop = $prop . '-' . $dir;
					}
					$css_code .= $css_prop . ':' . $val . ';';
				}
			}
		}
		$css_code .= "}";
	}
}

// Output the Grid element
echo '<div class="w-grid' . $classes . '" id="' . esc_attr( $grid_elm_id ) . '">';
echo '<style id="' . $grid_elm_id . '_css">' . us_minify_css( $css_code ) . '</style>';
echo $filter_html;
if ( $filter_html != '' ) {
	?>
	<div class="w-grid-preloader">
		<div class="g-preloader type_<?php echo $preloader_type; ?>"><div></div></div>
	</div>
	<?php
}
echo '<div class="w-grid-list' . $list_classes . '"' . $data_atts. '>';

// Preparing template settings for loop post template
$template_vars = array(
	'grid_layout_settings' => &$grid_layout_settings,
	'post_type' => $post_type,
	'type' => $type,
	'is_widget' => $is_widget,
);

// Start the loop
while ( have_posts() ) {
	the_post();

	us_load_template( 'templates/us_grid/listing-post', $template_vars );
}

echo '</div>';

// Output preloader for Carousel type
if ( $type == 'carousel' ) {
	?>
	<div class="g-preloader type_<?php echo $preloader_type; ?>"><div></div></div>
	<?php
}

// Output pagination for not Carousel type
if ( $wp_query->max_num_pages > 1 AND $type != 'carousel' ) {

	// Next page elements may have sliders, so we preloading the needed assets now
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-royalslider' );
	}

	// Add lang variable if WPML is active
	if ( class_exists( 'SitePress' ) ) {
		global $sitepress;
		if ( $sitepress->get_default_language() != $sitepress->get_current_language() ) {
			$json_data['template_vars']['lang'] = $sitepress->get_current_language();
		}
	}

	if ( $pagination == 'infinite' ) {
		$is_infinite = TRUE;
		$pagination = 'ajax';
	}

	if ( $pagination == 'regular' ) {
		the_posts_pagination(
			array(
				'mid_size' => 3,
				'before_page_number' => '<span>',
				'after_page_number' => '</span>',
			)
		);
	} elseif ( $pagination == 'ajax' ) {
		if ( ! isset( $pagination_btn_text ) OR empty( $pagination_btn_text ) ) {
			$pagination_btn_text = __( 'Load More', 'us' );
		}
		?>
		<div class="g-loadmore">
			<a class="g-loadmore-btn" href="javascript:void(0)"><span><?php echo $pagination_btn_text ?></span></a>
			<div class="g-preloader type_<?php echo $preloader_type; ?>"><div></div></div>
		</div><?php
	}
}

// Pagination and/or popup data
$json_data = array(

	// Controller options
	'ajax_url' => admin_url( 'admin-ajax.php' ),
	'permalink_url' => get_permalink(),
	'action' => 'us_ajax_grid',
	'max_num_pages' => $wp_query->max_num_pages,
	'infinite_scroll' => ( ( isset( $is_infinite ) ) ? $is_infinite : 0 ),

	// Grid listing template variables that will be passed to this file in the next call
	'template_vars' => array(
		'query_args' => $query_args,
		'post_id' => $post_id,
		'us_grid_index' => $us_grid_index,
		'items_offset' => $items_offset,
		'items_layout' => $items_layout,
		'type' => $type,
		'columns' => $columns,
		'img_size' => $img_size,
	),
);
?>
	<div class="w-grid-json hidden"<?php echo us_pass_data_to_js( $json_data ) ?>></div>
<?php

// Output popup semantics
if ( us_arr_path( $grid_layout_settings, 'default.options.link' ) == 'popup_post' ) {

	if ( $post_type == 'post' ) {
		$show_popup_arrows = us_get_option( 'post_nav', 0 );
	} elseif ( $post_type == 'us_portfolio' ) {
		$show_popup_arrows = us_get_option( 'portfolio_nav', 0 );
	} else {
		$show_popup_arrows = TRUE;
	}

	$popup_width = trim( us_arr_path( $grid_layout_settings, 'default.options.popup_width' ) );
	if ( ! empty( $popup_width ) AND strpos( $popup_width, 'px' ) === FALSE AND strpos( $popup_width, '%' ) === FALSE ) {
		$popup_width = $popup_width . 'px';
	}

	?>
	<div class="l-popup">
		<div class="l-popup-overlay"></div>
		<div class="l-popup-wrap">
			<div class="l-popup-box">
				<div class="l-popup-box-content"<?php if ( ! empty( $popup_width ) ) { echo ' style="max-width: ' . $popup_width . ';"'; } ?>>
					<div class="g-preloader type_<?php echo $preloader_type; ?>"><div></div></div>
					<iframe class="l-popup-box-content-frame" allowfullscreen></iframe>
				</div>
			</div>
	<?php if ( $show_popup_arrows ) { ?>
			<div class="l-popup-arrow to_next" title="Next"></div>
			<div class="l-popup-arrow to_prev" title="Previous"></div>
	<?php } ?>
			<div class="l-popup-closer"></div>
		</div>
	</div>
	<?php
}

echo '</div>';

if ( $use_custom_query ) {
	// Cleaning up
	us_close_wp_query_context();
}

// Reset image size for the next grid element
if ( isset( $us_grid_img_size ) ) {
	$us_grid_img_size = 'default';
}
