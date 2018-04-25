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

const WPSL_VERSION_NUM = '1.0';

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

add_action( 'admin_enqueue_scripts', 'wpc_add_admin_cpt_script', 10, 1 );
function wpc_add_admin_cpt_script( $hook ) {

//	if ( ( get_post_type() == 'wpsl_stores' ) || ( isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'wpsl_stores' ) ) ) {

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'ymap_stores' === get_post_type() ) {
			$style_url = plugins_url( '/css/', __FILE__ );
			$script_url = plugins_url( '/js/', __FILE__ );

			wp_enqueue_style('ymapsl-admin-css', $style_url.'ymapsl-admin.css', false, WPSL_VERSION_NUM);

			wp_register_script( 'ymaps', 'http://api-maps.yandex.ru/2.1.63/?lang=ru_RU', array( 'jquery' ), '2.1.63', true);
			wp_enqueue_script('ymaps');

			wp_enqueue_script('ymapsl-admin-js', $script_url.'ymapsl-admin.js', array( 'jquery','ymaps'), WPSL_VERSION_NUM, true);

			wp_enqueue_script('parsley-admin-js', 'https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.1/parsley.min.js', array( 'jquery','ymaps'), WPSL_VERSION_NUM, true);

//			wp_enqueue_style('bootstrap-admin-css', 'https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css', false, WPSL_VERSION_NUM);
//			wp_enqueue_script('bootstrap-admin-js', 'https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js', array( 'jquery'), WPSL_VERSION_NUM, true);


		}
	}
}

add_filter( 'ymapsl_meta_fields', 'get_ymapsl_meta_fields' );
function get_ymapsl_meta_fields( $content ) {

	$meta_fields = array(
		'Адрес'                 => array(
			'id_ta'    => array(
				'label'    => 'ID Турагентства',
				'required' => false
			),
			'phone' => array(
				'label'    => 'Контактный телефон',
				'required' => false
			),
			'city'    => array(
				'label'    => 'Город',
				'required' => true
			),
			'address' => array(
				'label'    => 'Адрес',
				'required' => true
			),
			'lng'     => array(
				'label' => 'Longitude',
				'required' => true
			),
			'lat'     => array(
				'label' => 'Latitude',
				'required' => true
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

	?>
    <div class="ymapsl-error-form"></div>
    <div class="ymapsl_form">
        <div class="ymapsl_fields">
			<?php
			foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
				$value = get_post_meta( $post->ID, '_ymapsl_' . $field_key, true );
				?>
                <p class="ymapsl_<?= $field_key ?>_form">
                    <label for="ymapsl_field"><?= $field_data['label'] ?> <?= $field_data['required'] ?  '<abbr class="required" title="обязательно">*</abbr>':'' ?></label>
                    <input name="ymapsl_<?= $field_key ?>" id="ymapsl_<?= $field_key ?>" value="<?= $value ?>" class="" <?= $field_data['required'] ?  'required=""':'' ?> >
                </p>
				<?php
			}

			$ymapsl_lng = $value = get_post_meta( $post->ID, '_ymapsl_lng', true );
			$ymapsl_lat = $value = get_post_meta( $post->ID, '_ymapsl_lat', true );
			?>
        </div>
        <button type="button" id="check_geocode">Установить</button>
    </div>
    <div id="YMapsID"></div>
	<?php
}

add_action( 'save_post', 'ymapsl_save_postdata' );
function ymapsl_save_postdata( $post_id ) {

    file_put_contents( "save_post.txt", print_r(  get_the_title( $post_id ), true ) . "\r\n", FILE_APPEND | LOCK_EX );


    if (!isset($_POST['ymapsl_city']) || !isset($_POST['ymapsl_address'])) return;

	add_filter('post_updated_messages', 'your_message');

	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
		if ( array_key_exists( 'ymapsl_' . $field_key, $_POST ) ) {
			update_post_meta( $post_id, '_ymapsl_' . $field_key, $_POST[ 'ymapsl_' . $field_key ] );
		}
	}
}
