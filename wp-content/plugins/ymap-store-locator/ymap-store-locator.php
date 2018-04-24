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

add_action( 'admin_enqueue_scripts', 'wpc_add_admin_cpt_script', 10, 1 );
function wpc_add_admin_cpt_script( $hook ) {

	global $post_type;

	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'ymap_stores' === $post_type ) {
			wp_enqueue_script( 'ymaps', 'http://api-maps.yandex.ru/2.1.63/?lang=ru_RU', array( 'jquery' ) );
		}
	}
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
			'lng'     => array(
				'label' => 'Longitude'
			),
			'lat'     => array(
				'label' => 'Latitude'
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
    <div class="ymapsl_form">
        <div class="ymapsl_fields">
			<?php
			foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
				$value = get_post_meta( $post->ID, '_ymapsl_' . $field_key, true );
				?>
                <p class="ymapsl_<?= $field_key ?>_form">
                    <label for="ymapsl_field"><?= $field_data['label'] ?></label>
                    <input name="ymapsl_<?= $field_key ?>" id="ymapsl_<?= $field_key ?>" value="<?= $value ?>"
                           class="postbox">
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
    <style>
        .ymapsl_form {
            display: flex;
        }
        .ymapsl_fields {
            max-width: 570px;
            display: flex;
            flex-wrap: wrap;
        }
        .ymapsl_fields > p {
            display: block;
            max-width: 311px;
            margin: 0px 11px;
        }
        .ymapsl_fields > p label {
            display: block;
            font-weight: 700;
            font-size: 14px;
        }
        .ymapsl_fields > p input {
            font-size: 16px;
            padding: 5px;
        }
        #check_geocode {
            margin-bottom: 20px;
            margin-top: 12px;
            background-color: #FF8F00;
            padding: 0 0.8em;
            border-radius: 3px;
            box-shadow: 0 0.1em 0.2em rgba(0,0,0,0.18);
            border: none;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s, opacity 0.3s, color 0.3s;
            outline: none;
            height: 50px;
        }
        #check_geocode:hover {
            box-shadow: 0 0.5em 1em rgba(0,0,0,0.2);
        }

        #YMapsID {
            width: 100%;
            height: 370px;
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {
            ymaps.ready(function () {
                var ymapslMap = new ymaps.Map('YMapsID', {
                    center: [56.326944, 44.0075],
                    zoom: 10,
                    type: 'yandex#map',
                    controls: []
                });


                <?php if (!empty($ymapsl_lng) && !empty($ymapsl_lat)){ ?>
                var myPlacemark = new ymaps.Placemark([<?= $ymapsl_lng ?>, <?= $ymapsl_lat ?>], {
                    hintContent: 'Собственный значок метки',
                    balloonContent: 'Это красивая метка'
                });
                ymapslMap.geoObjects.add(myPlacemark);
                ymapslMap.setBounds(ymapslMap.geoObjects.getBounds());
                ymapslMap.setZoom(16);
	            <?php } ?>

                $('#check_geocode').click(function (e) {
                    geocode();
                });

                function geocode() {
                    // Забираем запрос из поля ввода.
                    var request = $('#ymapsl_city').val() + ', ' + $('#ymapsl_address').val();

                    // Геокодируем введённые данные.
                    ymaps.geocode(request, {
                        /**
                         * Опции запроса
                         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
                         */
                        results: 1
                    }).then(function (res) {

                        // var obj = res.geoObjects.get(0),
                        //     error, hint;

                        // if (obj) {
                        //     // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                        //     switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        //         case 'exact':
                        //             break;
                        //         case 'number':
                        //         case 'near':
                        //         case 'range':
                        //             error = 'Неточный адрес, требуется уточнение';
                        //             hint = 'Уточните номер дома';
                        //             break;
                        //         case 'street':
                        //             error = 'Неполный адрес, требуется уточнение';
                        //             hint = 'Уточните номер дома';
                        //             break;
                        //         case 'other':
                        //         default:
                        //             error = 'Неточный адрес, требуется уточнение';
                        //             hint = 'Уточните адрес';
                        //     }
                        // } else {
                        //     error = 'Адрес не найден';
                        //     hint = 'Уточните адрес';
                        // }

                        // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                        // if (error) {
                        //     // showError(error);
                        //     // showMessage(hint);
                        // } else {
                        //     // showResult(obj);
                        //     // Выбираем первый результат геокодирования.
                        // }

                        var firstGeoObject = res.geoObjects.get(0);
                        // Координаты геообъекта.
                        var coords = firstGeoObject.geometry.getCoordinates();
                        var bounds = firstGeoObject.properties.get('boundedBy');

                        firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
                        // Получаем строку с адресом и выводим в иконке геообъекта.
                        firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

                        // Добавляем первый найденный геообъект на карту.
                        ymapslMap.geoObjects.removeAll();
                        ymapslMap.geoObjects.add(firstGeoObject);
                        // Масштабируем карту на область видимости геообъекта.
                        ymapslMap.setBounds(bounds, {
                            // Проверяем наличие тайлов на данном масштабе.
                        });
                        ymapslMap.setZoom(16);

                        console.log(coords);
                        $('#ymapsl_lng').val(coords[0]);
                        $('#ymapsl_lat').val(coords[1])


                    }, function (e) {
                        console.log(e)
                    })

                }

            });
        });
    </script>
	<?php
}

add_action( 'save_post', 'ymapsl_save_postdata' );
function ymapsl_save_postdata( $post_id ) {
	if ( array_key_exists( 'ymapsl_field', $_POST ) ) {
		update_post_meta( $post_id, '_ymapsl_meta_key', $_POST['ymapsl_field'] );
	}
	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {
		if ( array_key_exists( 'ymapsl_' . $field_key, $_POST ) ) {
			update_post_meta( $post_id, '_ymapsl_' . $field_key, $_POST[ 'ymapsl_' . $field_key ] );
		}
	}
}
