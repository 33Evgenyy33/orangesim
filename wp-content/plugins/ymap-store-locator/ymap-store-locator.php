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
				'name'               => 'Пункты выдачи',
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

add_action( 'admin_enqueue_scripts', 'load_scripts_for_admin', 10, 1 );
function load_scripts_for_admin( $hook ) {

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
		}
	}
}


add_filter( 'ymapsl_meta_fields', 'get_ymapsl_meta_fields' );
function get_ymapsl_meta_fields( $content ) {

	$meta_fields = array(
		'Адрес'                 => array(
			'id_ta'         => array(
				'label'    => 'ID Турагентства',
				'required' => false
			),
			'phone'         => array(
				'label'    => 'Контактный телефон',
				'required' => false
			),
			'city'          => array(
				'label'    => 'Город',
				'required' => true
			),
			'address'       => array(
				'label'    => 'Адрес',
				'required' => true
			),
			'opening_hours' => array(
				'label'    => 'График работы',
				'required' => false
			),
			'comment'       => array(
				'label'    => 'Комментарий',
				'required' => false
			),
			'lng'           => array(
				'label'    => 'Longitude',
				'required' => true
			),
			'lat'           => array(
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

	?>
    <div class="ymapsl-form">
        <div class="ymapsl-fields">
            <div class="ymapsl-fields-row">
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
            </div>
            <div class="ymapsl-fields-row">
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
            </div>
            <div class="ymapsl-fields-row">
                <p id="ymapsl_opening_hours_field">
                    <label for="ymapsl_field">График работы</label>
                    <input name="ymapsl_opening_hours" id="ymapsl_opening_hours"
                           value="<?= get_post_meta( $post->ID, '_ymapsl_opening_hours', true ); ?>">
                </p>
            </div>
            <div class="ymapsl-fields-row">
                <p id="ymapsl_comment_field">
                    <label for="ymapsl_field">Комментарий</label>
                    <textarea name="ymapsl_comment"
                              id="ymapsl_comment"><?= get_post_meta( $post->ID, '_ymapsl_comment', true ); ?></textarea>
                </p>
            </div>
            <div class="ymapsl-fields-row">
                <p id="ymapsl_lng_field">
                    <label for="ymapsl_field">Longitude <abbr class="required" title="обязательно">*</abbr></label>
                    <input name="ymapsl_lng" id="ymapsl_lng"
                           value="<?= get_post_meta( $post->ID, '_ymapsl_lng', true ); ?>"
                           required="" readonly>
                </p>
                <p id="ymapsl_lat_field">
                    <label for="ymapsl_field">Latitude <abbr class="required" title="обязательно">*</abbr></label>
                    <input name="ymapsl_lat" id="ymapsl_lat"
                           value="<?= get_post_meta( $post->ID, '_ymapsl_lat', true ); ?>"
                           class="" required="" readonly>
                </p>
            </div>
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

	$args = array(
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

add_action( 'wp_enqueue_scripts', 'load_scripts_for_frontend' );
function load_scripts_for_frontend() {
	if ( ! is_front_page() ) { //Важно!!! для главной страницы заменить на ( is_front_page() )

		$style_url  = plugins_url( '/css/', __FILE__ );
		$script_url = plugins_url( '/js/', __FILE__ );


//		wp_enqueue_style( 'ymapsl-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', false, WPSL_VERSION_NUM );
		wp_enqueue_style( 'ymapsl-frontend-css', $style_url . 'frontend-style.css', false, WPSL_VERSION_NUM );


		wp_localize_script( 'jquery', 'ymapsl_ajax',
			array(
				'url' => admin_url('admin-ajax.php')
			)
		);

		wp_register_script( 'ymaps-frontend-js', 'http://api-maps.yandex.ru/2.1.64/?lang=ru_RU', array( 'jquery' ), '2.1.63', true );
		wp_enqueue_script( 'ymaps-frontend-js' );

		wp_enqueue_script( 'ymapsl-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), WPSL_VERSION_NUM, true );
		wp_enqueue_script( 'ymapsl-frontend-js', $script_url . 'frontend-script.js', array(
			'jquery',
			'ymaps-frontend-js',
			'ymapsl-select2-js'
		), WPSL_VERSION_NUM, true );

	}
}

add_filter( 'ymapsl_cities', 'get_ymapsl_cities' );
function get_ymapsl_cities( $content ) {

	$ymapsl_cities = array(
		'Москва',
		'Санкт-Петербург',
		'Пенза'
	);

	return $ymapsl_cities;
}

add_shortcode( 'ymapsl', 'ymapsl_frontend' );
function ymapsl_frontend() {

	$ymapsl_cities = apply_filters( 'ymapsl_cities', '' );

	$output = '';
	$output .= '<div id="ymapsl_wrap">'. "\r\n";
	$output .= "\t" . '<div class="ymapsl-loader-wrap">' . "\r\n";
	$output .= "\t\t" . '<div class="ymapsl-loader">' . "\r\n";
	$output .= "\t\t\t" . '<div class="ymapsl-loader-bounceball"></div>' . "\r\n";
	$output .= "\t\t\t" . '<div class="ymapsl-loader-text"></div>' . "\r\n";
	$output .= "\t\t" . '</div>' . "\r\n";
	$output .= "\t" . '</div>' . "\r\n";
	$output .= "\t" . '<div id="ymapsl_search">' . "\r\n";
	$output .= "\t\t" . '<select id="ymapsl_search_cities">' . "\r\n";
	$output .= "\t\t\t" . '<option></option>' . "\r\n";
	foreach ( $ymapsl_cities as $ymapsl_city ) {
		$output .= "\t\t\t" . '<option value="' . $ymapsl_city . '">' . $ymapsl_city . '</option>' . "\r\n";
	}
	$output .= "\t\t" . '</select>' . "\r\n";
	$output .= "\t" . '</div>' . "\r\n";
	$output .= "\t" . '<div id="ymapsl_map_container">' . "\r\n";
	$output .= "\t\t" . '<div id="ymapsl_result_list">' . "\r\n";
	$output .= "\t\t\t" . '<div id="ymapsl_stores">' . "\r\n";
	$output .= "\t\t\t\t" . '<ul></ul>' . "\r\n";
	$output .= "\t\t\t" . '</div>' . "\r\n";
	$output .= "\t\t" . '</div>' . "\r\n";
	$output .= "\t\t" . '<div id="ymapsl_map_wrap">' . "\r\n";
	$output .= "\t\t\t" . '<div id="ymapsl_map">' . "\r\n";
	$output .= "\t\t\t" . '</div>' . "\r\n";
	$output .= "\t\t" . '</div>' . "\r\n";
	$output .= "\t" . '</div>';
	$output .= '</div>';

	return $output;
}

add_action( 'wp_ajax_nopriv_ymapsl_search_stores', 'ymapsl_search_stores' );
add_action( 'wp_ajax_ymapsl_search_stores', 'ymapsl_search_stores' );
function ymapsl_search_stores() {
	global $wpdb;
	// проверяем nonce код, если проверка не пройдена прерываем обработку
//	check_ajax_referer( 'myajax-nonce', 'nonce_code' );
	// или так
//	if( ! wp_verify_nonce( $_POST['nonce_code'], 'myajax-nonce' ) ) die( 'Stop!');

	if ( isset( $_POST['city'] ) ) {
		$selected_city = $_POST['city'];

		if ( empty( $selected_city ) ) {
			die();
		}

		$args = array(
			'post_type'   => 'ymap_stores',
			'post_status' => 'publish',
			'meta_query'  => array(
				array(
					'key'   => '_ymapsl_city',
					'value' => $selected_city
				)
			),
            'orderby' => 'date',
            'order' => 'DESC'
		);

		$query = new WP_Query();

		$my_posts = $query->query( $args );

		$addressshop         = array();
		$addressshop[1]['type'] = 'FeatureCollection';

//		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/all_stores.txt", print_r( $my_posts, true ) . "\r\n", FILE_APPEND | LOCK_EX );

        if (empty($my_posts)){
            echo json_encode(array());
            die();
        }

		$stores_without_id = array();
		$stores_with_id = array();
		$urls = array();
		foreach ($my_posts as $one_post) {
			$ta_id = get_post_meta( $one_post->ID, '_ymapsl_id_ta', true );

			if (!$ta_id) {
//				array_push($stores_without_id, $one_post);
				continue;
			}

			array_push($urls, "http://seller.sgsim.ru/euroroaming_order_submit?operation=get_simcards_new&ta=$ta_id");
			array_push($stores_with_id, $one_post);
		}
		$available_stores_with_id = array();
		$res = array();
		$mh = curl_multi_init();
		foreach ($urls as $i => $url) {
			$conn[$i] = curl_init($url);
			curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);  //ничего в браузер не давать
			curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, 10); //таймаут соединения
			curl_multi_add_handle($mh, $conn[$i]);
		}//Пока все соединения не отработают
		do {
			curl_multi_exec($mh, $active);
		} while ($active);

		//разбор полетов
		for ($i = 0; $i < count($urls); $i++) {
			//ответ сервера в переменную
			$resp      = curl_multi_getcontent( $conn[ $i ] );
			$res[ $i ] = $resp;
			//Если вернулся пустой массив, то сим-карт нет в наличие, пункт не отображается
			if ( empty( $resp ) ) {
				continue;
			}

			$array_of_simcard = (array) json_decode( $resp );

			file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/1array_of_simcard.txt", print_r( $array_of_simcard, true ) . "\r\n", FILE_APPEND | LOCK_EX );

			$exist_operators = array();
			foreach ($array_of_simcard as $key => $numbers) {
				array_push($exist_operators, $key);
			}

			//Заполнения массива имен сим-карт, полученных с селлера
            if (!in_array('orange', $exist_operators)) continue;

			$stores_with_id[$i]->qty = count($array_of_simcard['orange']);
            array_push($available_stores_with_id, $stores_with_id[$i]);

			curl_multi_remove_handle($mh, $conn[$i]);
			curl_close($conn[$i]);
		}
		curl_multi_close($mh);

		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/available_stores_with_id.txt", print_r( $available_stores_with_id, true ) . "\r\n", FILE_APPEND | LOCK_EX );

		$icon_num = 1;
		foreach ( $available_stores_with_id as $store ) {
			$store_id            = $store->ID;
			$store_name          = $store->post_title;
			$store_city          = get_post_meta( $store_id, '_ymapsl_city', true );
			$store_address       = get_post_meta( $store_id, '_ymapsl_address', true );
			$store_phone         = get_post_meta( $store_id, '_ymapsl_phone', true );
			$store_opening_hours = get_post_meta( $store_id, '_ymapsl_opening_hours', true );
			$store_comment       = get_post_meta( $store_id, '_ymapsl_comment', true );
			$store_lng           = get_post_meta( $store_id, '_ymapsl_lng', true );
			$store_lat           = get_post_meta( $store_id, '_ymapsl_lat', true );

			if (!empty($store_comment)){
				$store_comment_map = '<strong>Прмечание: </strong>' . $store_comment;
				$store_comment_list = $store_comment;
            } else {
				$store_comment_map = '';
				$store_comment_list = '';
            }

			$addressshop[1]['features'][] = array(
				"type"       => "Feature",
				"id"         => intval( $store_id ),
				"geometry"   => array(
					"type"        => "Point",
					"coordinates" => [ floatval( $store_lng ), floatval( $store_lat ) ]
				),
				"properties" => array(
					"balloonContentHeader" => "<div style='color:#2977e0;font-weight:bold'> {$store_name} </div>",
					"balloonContentBody"   => "<div style='font-size:13px;'><div><strong>Адрес: </strong>{$store_address}<br><strong>Режим работы: </strong>{$store_opening_hours}<br><strong>Тел.: </strong>{$store_phone}<br>{$store_comment_map}</div></div>",
                    "iconContent"          => $store_name
                    )
			);

			$addressshop[0]['address'][] = array(
				'id'            => intval( $store_id ),
				'qty'           => 1,
				'name'          => $store_name,
				'city'          => $store_city,
				'address'       => $store_address,
				'phone'         => $store_phone,
				'opening_hours' => $store_opening_hours,
				'comment'       => $store_comment_list
			);

			$icon_num++;

		}

		$json = json_encode(array($addressshop[0],$addressshop[1]), JSON_UNESCAPED_UNICODE );

		echo $json;
	}

	wp_die();
}