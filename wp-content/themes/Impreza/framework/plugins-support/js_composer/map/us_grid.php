<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_grid
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */

global $us_grid_sc_config;
$us_grid_sc_config = $config;
add_action( 'init', 'us_add_grid_map' , 11 );

function us_add_grid_map() {
	global $us_grid_sc_config;

	// Fetching the available post types to choose from
	$available_posts_types = array();
	$posts_types_params = array(
		'show_in_menu' => TRUE,
	);
	$skip_post_types = array(
		'us_header',
		'us_footer',
		'us_grid_layout',
		'attachment',
		'shop_order',
		'shop_coupon',
		'product',
	);
	foreach ( get_post_types( $posts_types_params, 'objects', 'and' ) as $post_type_name => $post_type ) {
		if ( in_array( $post_type_name, $skip_post_types ) ) {
			continue;
		}
		$available_posts_types[$post_type_name] = $post_type->labels->name . ' (' . $post_type_name . ')';
	}

	// Fetching the available taxonomies to choose from
	$taxonomies_params = $available_taxonomies = array();
	$known_post_type_taxonomies = array(
		'post' => 'category',
		'us_portfolio' => 'us_portfolio_category',
		'us_testimonial' => 'us_testimonial_category',
		'product' => 'product_cat',
	);
	foreach ( $known_post_type_taxonomies as $post_type => $taxonomy_slug ) {
		if ( isset( $available_posts_types[$post_type] ) ) {
			$taxonomy_class = get_taxonomy( $taxonomy_slug );
			if ( ! empty( $taxonomy_class ) AND ! empty( $taxonomy_class->labels ) AND ! empty( $taxonomy_class->labels->name ) ) {
				$available_taxonomies[$taxonomy_slug] = array(
					'name' => $taxonomy_class->labels->name,
					'post_type' => $post_type,
				);
			}
		}
	}
	foreach ( $available_taxonomies as $taxonomy_slug => $taxonomy ) {
		$taxonomy_items = array();
		$taxonomy_items_raw = get_categories(
			array(
				'taxonomy' => $taxonomy_slug,
				'hierarchical' => 0,
			)
		);
		if ( $taxonomy_items_raw ) {
			foreach ( $taxonomy_items_raw as $taxonomy_item_raw ) {
				if ( is_object( $taxonomy_item_raw ) ) {
					$taxonomy_items[$taxonomy_item_raw->name] = $taxonomy_item_raw->slug;
				}
			}
			if ( count( $taxonomy_items ) > 0 ) {
				// Do not output the only category of Posts
				if ( $taxonomy['post_type'] == 'post' AND count( $taxonomy_items ) == 1 ) {
					continue;
				}
				$taxonomies_params[] = array(
					'param_name' => $taxonomy['post_type'] . '_categories',
					'heading' => sprintf( __( 'Show Items of selected %s', 'us' ), $taxonomy['name'] ),
					'type' => 'checkbox',
					'value' => $taxonomy_items,
					'dependency' => array( 'element' => 'post_type', 'value' => $taxonomy['post_type'] ),
				);
			}
		}
	}

	$templates_config = us_config( 'grid-templates', array(), TRUE );
	$default_layout_templates = array();
	foreach ( $templates_config as $template_name => $template ) {
		$default_layout_templates[$template['title']] = $template_name;
	}

	$us_grid_map = array(
		'base' => 'us_grid',
		'name' => __( 'Grid', 'us' ),
		'description' => __( 'Shows list of posts, pages or any custom post types', 'us' ),
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 260,
	);

	// General
	$general_params = array_merge(
		array(
			array(
				'param_name' => 'post_type',
				'heading' => us_translate( 'Show' ),
				'type' => 'dropdown',
				'value' => array_flip( $available_posts_types ),
				'std' => 'post',
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'ignore_sticky',
				'type' => 'checkbox',
				'value' => array( __( 'Ignore sticky posts', 'us' ) => TRUE ),
				( ( $us_grid_sc_config['atts']['ignore_sticky'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['ignore_sticky'],
				'dependency' => array( 'element' => 'post_type', 'value' => 'post' ),
			),
		), $taxonomies_params, array(
			array(
				'param_name' => 'type',
				'heading' => us_translate( 'Type' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Regular Grid', 'us' ) => 'grid',
					__( 'Masonry', 'us' ) => 'masonry',
					__( 'Carousel', 'us' ) => 'carousel',
				),
				'std' => $us_grid_sc_config['atts']['type'],
				'admin_label' => TRUE,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'orderby',
				'heading' => us_translate( 'Order' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'By date of creation (newer first)', 'us' ) => 'date',
					__( 'By date of creation (older first)', 'us' ) => 'date_asc',
					__( 'By date of update (newer first)', 'us' ) => 'modified',
					__( 'By date of update (older first)', 'us' ) => 'modified_asc',
					__( 'Alphabetically', 'us' ) => 'alpha',
					us_translate( 'Random' ) => 'rand',
				),
				'std' => $us_grid_sc_config['atts']['orderby'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'columns',
				'heading' => us_translate( 'Columns' ),
				'type' => 'dropdown',
				'value' => array( '1', '2', '3', '4', '5', '6' ),
				'std' => $us_grid_sc_config['atts']['columns'],
				'admin_label' => TRUE,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'items_gap',
				'heading' => __( 'Gap between Items', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '10px, 3rem, 2vw' ),
				'type' => 'textfield',
				'std' => $us_grid_sc_config['atts']['items_gap'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'items_quantity',
				'heading' => __( 'Items Quantity', 'us' ),
				'description' => __( 'If left blank, will output all the items', 'us' ),
				'type' => 'textfield',
				'std' => $us_grid_sc_config['atts']['items_quantity'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'items_offset',
				'heading' => __( 'Items Quantity to skip', 'us' ),
				'description' => __( 'Allows to skip a certain number of items', 'us' ),
				'type' => 'textfield',
				'std' => $us_grid_sc_config['atts']['items_offset'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'pagination',
				'heading' => us_translate( 'Pagination' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'None' ) => 'none',
					__( 'Numbered pagination', 'us' ) => 'regular',
					__( 'Load items on button click', 'us' ) => 'ajax',
					__( 'Load items on page scroll', 'us' ) => 'infinite',
				),
				'std' => $us_grid_sc_config['atts']['pagination'],
				'dependency' => array( 'element' => 'type', 'value' => array( 'grid', 'masonry' ) ),
			),
			array(
				'param_name' => 'pagination_btn_text',
				'heading' => __( 'Button Label', 'us' ),
				'type' => 'textfield',
				'std' => $us_grid_sc_config['atts']['pagination_btn_text'],
				'dependency' => array( 'element' => 'pagination', 'value' => 'ajax' ),
			),
		)
	);

	// Appearance
	$appearance_params = array(
		array(
			'param_name' => 'items_layout',
			'heading' => __( 'Grid Layout', 'us' ),
			'description' => sprintf( __( 'You can edit selected layout or create a new one on the %s page', 'us' ), '<a href="' . admin_url() . 'edit.php?post_type=us_grid_layout" target="_blank">' . __( 'Grid Layouts', 'us' ) . '</a>' ),
			'type' => 'dropdown',
			'admin_label' => TRUE,
			'value' => us_array_merge(
				$default_layout_templates,
				array_flip( us_get_posts_titles_for( 'us_grid_layout' ) )
			),
			'std' => $us_grid_sc_config['atts']['items_layout'],
			'group' => us_translate( 'Appearance' ),
		),
		array(
			'param_name' => 'img_size',
			'heading' => __( 'Post Image Size', 'us' ),
			'description' => sprintf( __( 'To change the default image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">' . us_translate( 'Media Settings' ) . '</a>' ) . ' ' . sprintf( __( 'To add custom image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . admin_url( 'admin.php?page=us-theme-options#advanced' ) . '">' . __( 'Theme Options', 'us' ) . '</a>' ),
			'type' => 'dropdown',
			'value' => array_merge( array( __( 'As in Grid Layout', 'us' ) => 'default' ), us_image_sizes_select_values() ),
			'std' => $us_grid_sc_config['atts']['img_size'],
			'group' => us_translate( 'Appearance' ),
		),
		array(
			'param_name' => 'el_class',
			'heading' => us_translate( 'Extra class name', 'js_composer' ),
			'type' => 'textfield',
			'std' => $us_grid_sc_config['atts']['el_class'],
			'group' => us_translate( 'Appearance' ),
		),
	);

	// Carousel Settings
	$carousel_params = array(
		array(
			'param_name' => 'carousel_arrows',
			'type' => 'checkbox',
			'value' => array( __( 'Show Navigation Arrows', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['carousel_arrows'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['carousel_arrows'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		array(
			'param_name' => 'carousel_dots',
			'type' => 'checkbox',
			'value' => array( __( 'Show Navigation Dots', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['carousel_dots'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['carousel_dots'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		array(
			'param_name' => 'carousel_center',
			'type' => 'checkbox',
			'value' => array( __( 'Enable first item centering', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['carousel_center'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['carousel_center'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		array(
			'param_name' => 'carousel_slideby',
			'type' => 'checkbox',
			'value' => array( __( 'Slide by several items instead of one', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['carousel_slideby'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['carousel_slideby'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		array(
			'param_name' => 'carousel_autoplay',
			'type' => 'checkbox',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['carousel_autoplay'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['carousel_autoplay'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		array(
			'param_name' => 'carousel_interval',
			'heading' => __( 'Auto Rotation Interval (in seconds)', 'us' ),
			'type' => 'textfield',
			'std' => $us_grid_sc_config['atts']['carousel_interval'],
			'dependency' => array( 'element' => 'carousel_autoplay', 'not_empty' => TRUE ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
	);

	// Filter
	$filter_params = array(
		array(
			'param_name' => 'filter',
			'type' => 'checkbox',
			'value' => array( __( 'Enable filtering by category', 'us' ) => 'category' ),
			( ( $us_grid_sc_config['atts']['filter'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['filter'],
			'dependency' => array( 'element' => 'type', 'value' => array( 'grid', 'masonry' ) ),
			'dependency' => array( 'element' => 'pagination', 'value' => array( 'none', 'ajax', 'infinite' ) ),
			'group' => us_translate( 'Filter' ),
		),
		array(
			'param_name' => 'filter_style',
			'heading' => __( 'Filter Bar Style', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				sprintf( __( 'Style %d', 'us' ), 1 ) => 'style_1',
				sprintf( __( 'Style %d', 'us' ), 2 ) => 'style_2',
				sprintf( __( 'Style %d', 'us' ), 3 ) => 'style_3',
			),
			'std' => $us_grid_sc_config['atts']['filter_style'],
			'edit_field_class' => 'vc_col-sm-6',
			'dependency' => array( 'element' => 'filter', 'not_empty' => TRUE ),
			'group' => us_translate( 'Filter' ),
		),
		array(
			'param_name' => 'filter_align',
			'heading' => __( 'Filter Bar Alignment', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				us_translate( 'Left' ) => 'left',
				us_translate( 'Center' ) => 'center',
				us_translate( 'Right' ) => 'right',
			),
			'std' => $us_grid_sc_config['atts']['filter_align'],
			'edit_field_class' => 'vc_col-sm-6',
			'dependency' => array( 'element' => 'filter', 'not_empty' => TRUE ),
			'group' => us_translate( 'Filter' ),
		),
	);

	// Responsive Options
	$responsive_params = array(
		array(
			'param_name' => 'breakpoint_1_width',
			'heading' => __( 'Below screen width', 'us' ),
			'type' => 'textfield',
			'std' => $us_grid_sc_config['atts']['breakpoint_1_width'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_1_cols',
			'heading' => __( 'show', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ) => 8,
				sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ) => 7,
				sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ) => 6,
				sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ) => 5,
				sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ) => 4,
				sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ) => 3,
				sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ) => 2,
				sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ) => 1,
			),
			'std' => $us_grid_sc_config['atts']['breakpoint_1_cols'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_1_autoplay',
			'type' => 'checkbox',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['breakpoint_1_autoplay'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['breakpoint_1_autoplay'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_2_width',
			'heading' => __( 'Below screen width', 'us' ),
			'type' => 'textfield',
			'std' => $us_grid_sc_config['atts']['breakpoint_2_width'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_2_cols',
			'heading' => __( 'show', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ) => 8,
				sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ) => 7,
				sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ) => 6,
				sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ) => 5,
				sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ) => 4,
				sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ) => 3,
				sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ) => 2,
				sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ) => 1,
			),
			'std' => $us_grid_sc_config['atts']['breakpoint_2_cols'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_2_autoplay',
			'type' => 'checkbox',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['breakpoint_2_autoplay'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['breakpoint_2_autoplay'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_3_width',
			'heading' => __( 'Below screen width', 'us' ),
			'type' => 'textfield',
			'std' => $us_grid_sc_config['atts']['breakpoint_3_width'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_3_cols',
			'heading' => __( 'show', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ) => 8,
				sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ) => 7,
				sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ) => 6,
				sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ) => 5,
				sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ) => 4,
				sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ) => 3,
				sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ) => 2,
				sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ) => 1,
			),
			'std' => $us_grid_sc_config['atts']['breakpoint_3_cols'],
			'edit_field_class' => 'vc_col-sm-6',
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		array(
			'param_name' => 'breakpoint_3_autoplay',
			'type' => 'checkbox',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
			( ( $us_grid_sc_config['atts']['breakpoint_3_autoplay'] !== FALSE ) ? 'std' : '_std' ) => $us_grid_sc_config['atts']['breakpoint_3_autoplay'],
			'dependency' => array( 'element' => 'type', 'value' => 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
	);

	$us_grid_map['params'] = array_merge(
		$general_params, $appearance_params, $carousel_params, $filter_params, $responsive_params
	);


	vc_map(
		$us_grid_map
	);
}
