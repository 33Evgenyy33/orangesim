<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WPML Support
 *
 * @link https://wpml.org/
 */

if ( ! ( class_exists( 'SitePress' ) AND defined( 'ICL_LANGUAGE_CODE' ) ) ) {
	return;
}

// Adding class to body in Admin panel for pages in non-default language
global $sitepress;
$default_language = $sitepress->get_default_language();

if ( $default_language != ICL_LANGUAGE_CODE ) {
	global $pagenow;
	// Exception: do not add class on Theme Options page
	if ( ! ( ( $pagenow == 'admin.php' ) && ( $_GET['page'] == 'us-theme-options' ) ) ) {
		function us_admin_add_wpml_nondefault_class( $class ) {
			return $class . ' us_wpml_non_default';
		}

		add_filter( 'admin_body_class', 'us_admin_add_wpml_nondefault_class' );
	} else {
		// For Theme Options page adding redirect to default language
		wp_redirect( admin_url() . 'admin.php?page=us-theme-options&lang=' . $default_language );
	}
}

// Adding support for encoded shortcodes
add_filter( 'wpml_pb_shortcode_encode', 'wpml_pb_shortcode_encode_us_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_encode_us_urlencoded_json( $string, $encoding, $original_string ) {
	if ( $encoding !== 'us_urlencoded_json' ) {
		return $string;
	}

	$output = array();
	foreach ( $original_string as $combined_key => $value ) {
		$parts = explode( '_', $combined_key );
		$i = array_pop( $parts );
		$key = implode( '_', $parts );
		$output[$i][$key] = $value;
	}

	return urlencode( json_encode( $output ) );

}

add_filter( 'wpml_pb_shortcode_decode', 'wpml_pb_shortcode_decode_us_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_decode_us_urlencoded_json( $string, $encoding, $original_string ) {
	if ( $encoding !== 'us_urlencoded_json' ) {
		return $string;
	}

	$fields_to_translate = array(
		'title',
		'price',
		'substring',
		'features',
		'btn_text',
		'btn_link',
		'image',
		'link',
		'type',
		'url',
	);
	$rows = json_decode( urldecode( $original_string ), TRUE );
	$result = array();
	foreach ( $rows as $i => $row ) {
		foreach ( $row as $key => $value ) {
			if ( in_array( $key, $fields_to_translate ) ) {
				$result[$key . '_' . $i] = array( 'value' => $value, 'translate' => TRUE );
			} else {
				$result[$key . '_' . $i] = array( 'value' => $value, 'translate' => FALSE );
			}
		}
	}

	return $result;
}

