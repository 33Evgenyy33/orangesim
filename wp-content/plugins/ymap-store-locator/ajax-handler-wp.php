<?php
/**
 * WORDPRESS SPECIFIC AJAX HANDLER (because admin-ajax.php does not render plugin shortcodes).
 * by alexandre@pixeline.be
 * credits: Raz Ohad https://coderwall.com/p/of7y2q/faster-ajax-for-wordpress
 */

//mimic the actual admin-ajax

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

if (!isset($_REQUEST['action'])) {
    die('-1');
}

define('SHORTINIT', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once 'vendor/autoload.php';


$action = trim($_REQUEST['action']);
// Declare all actions that you will use this ajax handler for, as an added security measure.
$allowed_actions = array(
    'dothis_dothat',
    'ymapsl_search_stores'
);

if (in_array($action, $allowed_actions)) {
    ymapsl_search_stores();
} else {
    die('-1');
}

function ymapsl_get_meta_value($post_id, $meta_key)
{
    global $wpdb;

    $post_id = intval($post_id);

    $meta_value = $wpdb->get_var($wpdb->prepare("SELECT wp_postmeta.meta_value FROM $wpdb->postmeta WHERE (wp_postmeta.post_id = %d) AND (wp_postmeta.meta_key = %s)", $post_id, $meta_key));

    return $meta_value;
}

function ymapsl_search_stores()
{
    global $wpdb;

    if (isset($_POST['city'])) {
        $selected_city = trim($_POST['city']);

        if (empty($selected_city)) {
            die();
        }

        $my_posts = $wpdb->get_results($wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS  wp_posts.ID,wp_posts.post_title FROM wp_posts  INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE 1=1  AND ( 
  ( wp_postmeta.meta_key = '_ymapsl_city' AND wp_postmeta.meta_value = %s) 
) AND wp_posts.post_type = 'ymapsl_stores' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 10", $selected_city));

        $ta_address_details = array();
        $ta_address_details[1]['type'] = 'FeatureCollection';


        if (empty($my_posts)) {
            echo json_encode(array());
            die();
        }

        $available_posts_with_ta_id = get_stores_with_simcards($my_posts);

        if (empty($available_posts_with_ta_id)) {
            echo json_encode(array());
            die();
        }

        foreach ($available_posts_with_ta_id as $post) {
            $post_id = $post->ID;
            $ta_sim_qty = $post->qty;
            $ta_name = $post->post_title;

            $ta_city = ymapsl_get_meta_value($post_id, '_ymapsl_city');
            $ta_address = ymapsl_get_meta_value($post_id, '_ymapsl_address');
            $ta_metro = ymapsl_get_meta_value($post_id, '_ymapsl_metro');
            $ta_phone = ymapsl_get_meta_value($post_id, '_ymapsl_phone');
            $ta_opening_hours = ymapsl_get_meta_value($post_id, '_ymapsl_opening_hours');
            $ta_hours = ymapsl_get_meta_value($post_id, '_ymapsl_hours');
            $ta_hours = maybe_unserialize($ta_hours);
            $ta_hours_count = 0;

            if (isset($ta_hours)) {
                foreach ($ta_hours as $key => $value) {

                    $ta_hours_count += count($value);
                }

                if ($ta_hours_count === 0)
                    $ta_hours = 'need to clarify';
            }
            $ta_comment = ymapsl_get_meta_value($post_id, '_ymapsl_comment');
            $ta_lng = ymapsl_get_meta_value($post_id, '_ymapsl_lng');
            $ta_lat = ymapsl_get_meta_value($post_id, '_ymapsl_lat');


            if (!empty($ta_comment)) {
                $ta_comment_map = '<strong>Прмечание: </strong>' . $ta_comment;
                $ta_comment_list = $ta_comment;
            } else {
                $ta_comment_map = '';
                $ta_comment_list = '';
            }

            if (!empty($ta_metro)) {
                $ta_metro_map = '<br><span class="ymaps-map-metro">' . $ta_metro . '</span>';
                $ta_metro_list = $ta_metro;
            } else {
                $ta_metro_map = '';
                $ta_metro_list = '';
            }

            if (empty($ta_opening_hours)) {
                $ta_opening_hours = 'уточнять по телефону';
            }

            $ta_address_details[1]['features'][] = array(
                "type" => "Feature",
                "id" => intval($post_id),
                "geometry" => array(
                    "type" => "Point",
                    "coordinates" => [floatval($ta_lng), floatval($ta_lat)]
                ),
                "properties" => array(
                    "balloonContentHeader" => "<div style='color:#2977e0;font-weight:bold'> {$ta_name} </div>",
                    "balloonContentBody" => "<div style='font-size:13px;' class='ymaps-map-balloon'><i class=\"far fa-map-marker-alt\"></i> {$ta_city}, {$ta_address}{$ta_metro_map}<br><i class=\"far fa-clock\"></i> {$ta_opening_hours}<br><i class=\"far fa-phone\"></i> {$ta_phone}<br>{$ta_comment_map}</div>",
                    "iconContent" => $ta_name
                )
            );

            $ta_address_details[0]['address'][] = array(
                'id' => intval($post_id),
                'qty' => $ta_sim_qty,
                'name' => $ta_name,
                'city' => $ta_city,
                'address' => $ta_address,
                'metro' => $ta_metro_list,
                'phone' => $ta_phone,
                'opening_hours' => $ta_opening_hours,
                'hours' => $ta_hours,
                'comment' => $ta_comment_list
            );
        }

        $json = json_encode(array($ta_address_details[0], $ta_address_details[1]), JSON_UNESCAPED_UNICODE);

        echo $json;
    }

    die();
}

function get_stores_with_simcards($my_posts)
{

    $client = new Client(['timeout' => 12.0]); // see how i set a timeout

    // $stores_without_id = array(); Массив для кабинетов без ID
    $posts_with_ta_id = array();
    $promises = [];

    foreach ($my_posts as $one_post) {
        $ta_id = ymapsl_get_meta_value($one_post->ID, '_ymapsl_id_ta');

        if (!$ta_id) {
            continue;
        }

        $promises[$one_post->ID] = $client->getAsync("http://seller.sgsim.ru/euroroaming_order_submit?operation=get_simcards_new&ta=$ta_id");

        array_push($posts_with_ta_id, $one_post);
    }

    $results = Promise\settle($promises)->wait();

    $available_posts_with_ta_id = [];
    foreach ($results as $key => $result) { //$ta_id =>
        if ($result['state'] === 'fulfilled') {
            $response = $result['value'];
            if ($response->getStatusCode() == 200) {
                $res_body = (array)json_decode($result['value']->getBody());
                if (array_key_exists('orange', $res_body)) {
                    foreach ($posts_with_ta_id as $post) {
                        if ($post->ID == $key) {
                            $post->qty = count($res_body['orange']);
                            array_push($available_posts_with_ta_id, $post);
                        }
                    }
                }
            } else {
                continue;
            }
        }

    }

    return $available_posts_with_ta_id;

//    foreach ($results as $ta_with_id => $result) {

//        $site = $stores_with_id[$ta_with_id];
//        $this->logger->info('Crawler FetchHomePages: domain check ' . $ta_with_id);
//
//        if ($result['state'] === 'fulfilled') {
//            $response = $result['value'];
//            if ($response->getStatusCode() == 200) {
//                $site->setHtml($response->getBody());
//            } else {
//                $site->setHtml($response->getStatusCode());
//            }
//        } else if ($result['state'] === 'rejected') { // notice that if call fails guzzle returns is as state rejected with a reason.
//
//            $site->setHtml('ERR: ' . $result['reason']);
//        } else {
//            $site->setHtml('ERR: unknown exception ');
//            $this->logger->err('Crawler FetchHomePages: unknown fetch fail domain: ' . $ta_with_id);
//        }
//
//        $this->entityManager->persist($site); // this is a call to Doctrines entity manager
//    }

    /****************************************************************************/


}
