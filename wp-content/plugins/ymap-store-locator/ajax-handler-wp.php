<?php
/**
 * WORDPRESS SPECIFIC AJAX HANDLER (because admin-ajax.php does not render plugin shortcodes).
 * by alexandre@pixeline.be
 * credits: Raz Ohad https://coderwall.com/p/of7y2q/faster-ajax-for-wordpress
 */

//mimic the actual admin-ajax

if ( ! isset( $_REQUEST['action'] ) ) {
	die( '-1' );
}


define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );


$action = trim( $_REQUEST['action'] );
// Declare all actions that you will use this ajax handler for, as an added security measure.
$allowed_actions = array(
	'dothis_dothat',
	'ymapsl_search_stores'
);

if ( in_array( $action, $allowed_actions ) ) {
	ymapsl_search_stores();
} else {
	die( '-1' );
}

function ymapsl_get_meta_value( $post_id, $meta_key ) {
	global $wpdb;
	$post_id = intval($post_id);

	$meta_value = $wpdb->get_var($wpdb->prepare("SELECT wp_postmeta.meta_value FROM $wpdb->postmeta WHERE (wp_postmeta.post_id = %d) AND (wp_postmeta.meta_key = %s)",$post_id, $meta_key ));

	return $meta_value;
}

function ymapsl_search_stores() {
	global $wpdb;

	if ( isset( $_POST['city'] ) ) {
		$selected_city = $_POST['city'];

		if ( empty( $selected_city ) ) {
			die();
		}

		$my_posts = $wpdb->get_results($wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS  wp_posts.ID,wp_posts.post_title FROM wp_posts  INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1=1  AND ( 
  ( wp_postmeta.meta_key = '_ymapsl_city' AND wp_postmeta.meta_value = %s) 
) AND wp_posts.post_type = 'ymap_stores' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 10", $selected_city ));

		$addressshop         = array();
		$addressshop[1]['type'] = 'FeatureCollection';


		if (empty($my_posts)){
			echo json_encode(array());
			die();
		}

		$available_stores_with_id = get_stores_with_simcards($my_posts);

		if (empty($available_stores_with_id)){
			echo json_encode(array());
			die();
		}

		$icon_num = 1;
		foreach ( $available_stores_with_id as $store ) {
			$store_id            = $store->ID;
			$store_sim_qty       = $store->qty;
			$store_name          = $store->post_title;

			$store_city          = ymapsl_get_meta_value( $store_id, '_ymapsl_city');
			$store_address       = ymapsl_get_meta_value(  $store_id, '_ymapsl_address');
			$store_phone         = ymapsl_get_meta_value(  $store_id, '_ymapsl_phone');
			$store_opening_hours = ymapsl_get_meta_value(  $store_id, '_ymapsl_opening_hours');
			$store_comment       = ymapsl_get_meta_value(  $store_id, '_ymapsl_comment');
			$store_lng           = ymapsl_get_meta_value(  $store_id, '_ymapsl_lng');
			$store_lat           = ymapsl_get_meta_value(  $store_id, '_ymapsl_lat');


			if (!empty($store_comment)){
				$store_comment_map = '<strong>Прмечание: </strong>' . $store_comment;
				$store_comment_list = $store_comment;
			} else {
				$store_comment_map = '';
				$store_comment_list = '';
			}

			if (empty($store_opening_hours)){
				$store_opening_hours = 'уточнять по телефону';
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
					"balloonContentBody"   => "<div style='font-size:13px;' class='ymaps-map-balloon'><i class=\"fas fa-map-marker-alt\"></i> {$store_city}, {$store_address}<br><i class=\"fas fa-clock\"></i> {$store_opening_hours}<br><i class=\"fas fa-phone\"></i> {$store_phone}<br>{$store_comment_map}</div>",
					"iconContent"          => $store_name
				)
			);

			$addressshop[0]['address'][] = array(
				'id'            => intval( $store_id ),
				'qty'           => $store_sim_qty,
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

	die();
}

function get_stores_with_simcards($my_posts){
	$stores_without_id = array();
	$stores_with_id = array();
	$urls = array();
	foreach ($my_posts as $one_post) {
		$ta_id = ymapsl_get_meta_value( $one_post->ID, '_ymapsl_id_ta');


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

	return $available_stores_with_id;
}
