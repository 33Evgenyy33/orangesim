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
			'labels'              => array(
				'name'               => 'Пункты выдачи на карте',
				'singular_name'      => 'Пункт выдачи',
				'all_items'          => 'Все пункты выдачи',
				'add_new'            => 'Добавить пункт',
				'add_new_item'       => 'Добавление нового пункта выдачи',
				'edit_item'          => 'Редактировать пункт выдачи',
				'new_item'           => 'Новый пункт выдачи',
				'view_item'          => 'Посмотреть пункт выдачи',
				'search_items'       => 'Поиск пункта выдачи',
				'not_found'          => 'Пункты выдачи не найдены',
				'not_found_in_trash' => 'Пункты выдачи не найдены в корзине',
			),
			'public'              => false,
			'exclude_from_search' => true,
			'rewrite'             => false, // my custom slug
			'show_ui'             => true,
			'capability_type'     => 'store',
			'map_meta_cap'        => true,
			'query_var'           => 'ymap_stores',
			'supports'            => array( 'title', 'author', 'revisions' )
		)
	);
}

add_filter( 'enter_title_here', 'change_default_title' );
function change_default_title( $title ) {

	$screen = get_current_screen();

	if ( $screen->post_type == 'ymap_stores' ) {
		$title = 'Введите название пункта выдачи';
	}

	return $title;
}

add_action( 'admin_enqueue_scripts', 'wpc_add_admin_cpt_script', 10, 1 );
function wpc_add_admin_cpt_script( $hook ) {

//	if ( ( get_post_type() == 'wpsl_stores' ) || ( isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'wpsl_stores' ) ) ) {

	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'ymap_stores' === get_post_type() ) {
			$style_url  = plugins_url( '/css/', __FILE__ );
			$script_url = plugins_url( '/js/', __FILE__ );

			wp_enqueue_style( 'ymapsl-admin-css', $style_url . 'ymapsl-admin.css', false, WPSL_VERSION_NUM );

			wp_register_script( 'ymaps', 'http://api-maps.yandex.ru/2.1.63/?lang=ru_RU', array( 'jquery' ), '2.1.63', true );
			wp_enqueue_script( 'ymaps' );

			wp_enqueue_script( 'ymapsl-admin-js', $script_url . 'ymapsl-admin.js', array(
				'jquery',
				'ymaps'
			), WPSL_VERSION_NUM, true );

			wp_enqueue_script( 'parsley-admin-js', $script_url . 'parsley.min.js', array( 'jquery' ), WPSL_VERSION_NUM, true );


//			wp_enqueue_style('bootstrap-admin-css', 'https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css', false, WPSL_VERSION_NUM);
//			wp_enqueue_script('bootstrap-admin-js', 'https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js', array( 'jquery'), WPSL_VERSION_NUM, true);


		}
	}
}

add_filter( 'ymapsl_meta_fields', 'get_ymapsl_meta_fields' );
function get_ymapsl_meta_fields( $content ) {

	$meta_fields = array(
		'Адрес'                 => array(
			'id_ta'   => array(
				'label'    => 'ID Турагентства',
				'required' => false
			),
			'phone'   => array(
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
			'comment' => array(
				'label'    => 'Комментарий',
				'required' => false
			),
			'lng'     => array(
				'label'    => 'Longitude',
				'required' => true
			),
			'lat'     => array(
				'label'    => 'Latitude',
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
		'Данные ТА',  // Box title
		'ymapsl_custom_box_html',  // Content callback, must be of type callable
		$screen                   // Post type
	);
}

function ymapsl_custom_box_html( $post ) {

	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
//	file_put_contents( "ymapsl_meta_fields.txt", print_r( $meta_fields, true ) . "\r\n", FILE_APPEND | LOCK_EX );

	?>
    <div class="ymapsl-form">
        <div class="ymapsl-fields">
            <p id="ymapsl_id_ta_field">
                <label for="ymapsl_field">ID Турагентства </label>
                <input name="ymapsl_id_ta" id="ymapsl_id_ta"
                       value="<?= get_post_meta( $post->ID, '_ymapsl_id_ta', true ); ?>" type="number">
            </p>
            <p id="ymapsl_phone_field">
                <label for="ymapsl_field">Контактный телефон </label>
                <input name="ymapsl_phone" id="ymapsl_phone"
                       value="<?= get_post_meta( $post->ID, '_ymapsl_phone', true ); ?>">
            </p>
            <p id="ymapsl_city_field">
                <label for="ymapsl_field">Город <abbr class="required" title="обязательно">*</abbr></label>
                <input name="ymapsl_city" id="ymapsl_city"
                       value="<?= get_post_meta( $post->ID, '_ymapsl_city', true ); ?>" required="">
            </p>
            <p id="ymapsl_address_field">
                <label for="ymapsl_field">Адрес <abbr class="required" title="обязательно">*</abbr></label>
                <input name="ymapsl_address" id="ymapsl_address"
                       value="<?= get_post_meta( $post->ID, '_ymapsl_address', true ); ?>" required="">
            </p>
            <p id="ymapsl_comment_field">
                <label for="ymapsl_field">Комментарий</label>
                <textarea name="ymapsl_comment"
                          id="ymapsl_comment"><?= get_post_meta( $post->ID, '_ymapsl_comment', true ); ?></textarea>
            </p>
            <p id="ymapsl_lng_field">
                <label for="ymapsl_field">Longitude <abbr class="required" title="обязательно">*</abbr></label>
                <input name="ymapsl_lng" id="ymapsl_lng" value="<?= get_post_meta( $post->ID, '_ymapsl_lng', true ); ?>"
                       required="" readonly>
            </p>
            <p id="ymapsl_lat_field">
                <label for="ymapsl_field">Latitude <abbr class="required" title="обязательно">*</abbr></label>
                <input name="ymapsl_lat" id="ymapsl_lat" value="<?= get_post_meta( $post->ID, '_ymapsl_lat', true ); ?>"
                       class="" required="" readonly>
            </p>
            <div id="check_geocode_btn_wrap">
                <button type="button" id="check_geocode_btn">Установить</button>
            </div>
        </div>
    </div>
    <div id="YMapsID"></div>
	<?php
}

add_action( 'save_post', 'ymapsl_save_postdata' );
function ymapsl_save_postdata( $post_id ) {

	file_put_contents( "save_post.txt", print_r( get_the_title( $post_id ), true ) . "\r\n", FILE_APPEND | LOCK_EX );


	if ( ! isset( $_POST['ymapsl_city'] ) || ! isset( $_POST['ymapsl_address'] ) ) {
		return;
	}

	if ( is_int( wp_is_post_revision( $post_id ) ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	add_filter( 'post_updated_messages', 'your_message' );

	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
		if ( array_key_exists( 'ymapsl_' . $field_key, $_POST ) ) {
			update_post_meta( $post_id, '_ymapsl_' . $field_key, $_POST[ 'ymapsl_' . $field_key ] );
		}
	}

	$args  = array(
		'post_type'   => 'ymap_stores',
		'post_status' => 'publish',
		'meta_query'  => array(
			array(
				'key'   => '_ymapsl_city',
				'value' => 'Пенза'
			)
		)
	);
//	$query = new WP_Query();

//	$my_posts = $query->query( $args );

//	foreach ( $my_posts as $pst ) {
//		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/WP_Query.txt", print_r( $pst, true ) . "\r\n", FILE_APPEND | LOCK_EX );
//	}
}

function ymapsl_frontend() {
	$output = '';
	$output .= '<div id="ymapsl_wrap">';
	$output .= "\t" . '<div id="ymapsl_search">' . "\r\n";
	$output .= "\t" . '</div>' . "\r\n";
	$output .= "\t" . '<div id="ymapsl_result_list">' . "\r\n";
	$output .= "\t\t" . '<div id="ymapsl_stores">' . "\r\n";
	$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
	$output .= "\t\t" . '</div>' . "\r\n";
	$output .= "\t" . '</div>' . "\r\n";
	$output .= "\t" . '<div id="ymapsl_map">' . "\r\n";
	$output .= "\t" . '</div>' . "\r\n";
	$output .= '</div>';

	return $output;
}
add_shortcode( 'ymapsl', 'ymapsl_frontend' );