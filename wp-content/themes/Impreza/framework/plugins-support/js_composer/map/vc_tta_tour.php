<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Modifying shortcode: vc_tta_tour
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '<' ) ) {
	// Oops: the modified shorcode doesn't exist in current VC version. Doing nothing.
	return;
}

if ( ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_tour', 'title' );
	vc_remove_param( 'vc_tta_tour', 'style' );
	vc_remove_param( 'vc_tta_tour', 'shape' );
	vc_remove_param( 'vc_tta_tour', 'color' );
	vc_remove_param( 'vc_tta_tour', 'no_fill_content_area' );
	vc_remove_param( 'vc_tta_tour', 'spacing' );
	vc_remove_param( 'vc_tta_tour', 'gap' );
	vc_remove_param( 'vc_tta_tour', 'tab_position' );
	vc_remove_param( 'vc_tta_tour', 'alignment' );
	vc_remove_param( 'vc_tta_tour', 'controls_size' );
	vc_remove_param( 'vc_tta_tour', 'autoplay' );
	vc_remove_param( 'vc_tta_tour', 'active_section' );
	vc_remove_param( 'vc_tta_tour', 'pagination_style' );
	vc_remove_param( 'vc_tta_tour', 'pagination_color' );
	vc_remove_param( 'vc_tta_tour', 'css_animation' );

	vc_add_params(
		'vc_tta_tour', array(
		array(
			'param_name' => 'tab_position',
			'heading' => __( 'Tabs Position', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				us_translate( 'Left' ) => 'left',
				us_translate( 'Right' ) => 'right',
			),
			'std' => $config['atts']['tab_position'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 50,
		),
		array(
			'param_name' => 'c_align',
			'heading' => __( 'Tabs Text Alignment', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				us_translate( 'Left' ) => 'left',
				us_translate( 'Center' ) => 'center',
				us_translate( 'Right' ) => 'right',
			),
			'std' => $config['atts']['c_align'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 40,
		),
		array(
			'param_name' => 'controls_size',
			'heading' => __( 'Tabs Width', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				us_translate_x( 'Auto', 'auto preload' ) => 'auto',
				'10%' => '10',
				'20%' => '20',
				'30%' => '30',
				'40%' => '40',
				'50%' => '50',
			),
			'std' => $config['atts']['controls_size'],
			'weight' => 30,
		),
		array(
			'param_name' => 'title_size',
			'heading' => __( 'Tabs Title Size', 'us' ),
			'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3em, 200%' ),
			'type' => 'textfield',
			'std' => $config['atts']['title_size'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 20,
		),
		array(
			'param_name' => 'title_tag',
			'heading' => __( 'Sections Title HTML tag', 'us' ),
			'description' => __( 'Used for SEO purposes', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				'h1' => 'h1',
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6',
				'p' => 'p',
				'div' => 'div',
			),
			'std' => $config['atts']['title_tag'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 10,
		),
	)
	);
}

// Setting proper shortcode order in VC shortcodes listing
vc_map_update( 'vc_tta_tour', array( 'weight' => 300 ) );
