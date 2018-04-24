<?php
/**
 * Plugin Name: Yandex.Map Store Locator
 * Plugin URI: https://orangesim.ru/
 * Description: Your extension's description text.
 * Version: 1.0.0
 * Author: Evgeny
 * Author URI: https://orangesim.ru/
 * Developer: Evgeny
 * Developer URI: https://orangesim.ru/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 2.3
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'init', 'ymapsl_custom_post_type' );
function ymapsl_custom_post_type() {
	register_post_type( 'ymap_stores',
		array(
			'labels'          => array(
				'name'               => __( 'Yandex.Map Store Locator ' ),
				'singular_name'      => __( 'Yandex.Map Store' ),
				'all_items'          => __( 'All Yandex.Map Stores' ),
				'add_new'            => __( 'New Store' ),
				'add_new_item'       => __( 'Add New Store' ),
				'edit_item'          => __( 'Edit Store' ),
				'new_item'           => __( 'New Store' ),
				'view_item'          => __( 'View Stores' ),
				'search_items'       => __( 'Search Stores' ),
				'not_found'          => __( 'No Stores found' ),
				'not_found_in_trash' => __( 'No Stores found in trash' ),
			),
			'public'          => true,
			'rewrite'         => array( 'slug' => 'pickup-stores' ), // my custom slug
			'capability_type' => 'store',
			'map_meta_cap'    => true,
			'query_var'       => 'ymap_stores',
			'supports'        => array( 'title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail' )
		)
	);
}

add_filter( 'ymapsl_meta_fields', 'get_ymapsl_meta_fields' );
function get_ymapsl_meta_fields( $content ) {

	$meta_fields = array(
		'Адрес'                 => array(
			'city'    => array(
				'label'    => 'Город',
				'required' => true
			),
			'address' => array(
				'label'    => 'Адрес',
				'required' => true
			),
			'lat'     => array(
				'label' => 'Latitude'
			),
			'lng'     => array(
				'label' => 'Longitude'
			)
		),
		'Контактная информация' => array(
			'phone' => array(
				'label' => 'Телефон'
			),
			'email' => array(
				'label' => 'Email'
			)
		)
	);

	return $meta_fields;
}


add_action( 'add_meta_boxes', 'ymapsl_add_custom_box' );
function ymapsl_add_custom_box() {

	$screen = 'ymap_stores';
	add_meta_box(
		'ymapsl_box_id',           // Unique ID
		'Custom Meta Box Title',  // Box title
		'ymapsl_custom_box_html',  // Content callback, must be of type callable
		$screen                   // Post type
	);
}

function ymapsl_custom_box_html( $post ) {

	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
//	file_put_contents( "ymapsl_meta_fields.txt", print_r( $meta_fields, true ) . "\r\n", FILE_APPEND | LOCK_EX );

	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
		$value = get_post_meta( $post->ID, '_ymapsl_' . $field_key, true );
		?>
        <label for="ymapsl_field">Description for this field</label>
        <input name="ymapsl_<?= $field_key ?>" id="ymapsl_<?= $field_key ?>" value="<?= $value ?>" class="postbox">
		<?php
	}
}

add_action( 'save_post', 'ymapsl_save_postdata' );
function ymapsl_save_postdata( $post_id ) {
	if ( array_key_exists( 'ymapsl_field', $_POST ) ) {
		update_post_meta( $post_id, '_ymapsl_meta_key', $_POST['ymapsl_field'] );
	}
	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
		if ( array_key_exists( 'ymapsl_'.$field_key, $_POST ) ) {
			update_post_meta( $post_id, '_ymapsl_'.$field_key, $_POST['ymapsl_'.$field_key] );
		}
	}
}
