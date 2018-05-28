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

const YMAPSL_VERSION_NUM = '1.0';

add_action( 'init', 'ymapsl_custom_post_type' );
function ymapsl_custom_post_type() {
	register_post_type( 'ymapsl_stores',
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
			'query_var'           => 'ymapsl_stores',
			'supports'            => array( 'title', 'author', 'revisions' )
		)
	);
}

add_filter( 'enter_title_here', 'change_default_title' );
function change_default_title( $title ) {

	$screen = get_current_screen();

	if ( $screen->post_type == 'ymapsl_stores' ) {
		$title = 'Введите название пункта выдачи';
	}

	return $title;
}

add_action( 'admin_enqueue_scripts', 'load_scripts_for_admin', 10, 1 );
function load_scripts_for_admin( $hook ) {

//	if ( ( get_post_type() == 'ymapsl_stores' ) || ( isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'ymapsl_stores' ) ) ) {

	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'ymapsl_stores' === get_post_type() ) {
			$style_url  = plugins_url( '/css/', __FILE__ );
			$script_url = plugins_url( '/js/', __FILE__ );

			wp_enqueue_style( 'ymapsl-admin-css', $style_url . 'ymapsl-admin.css', false, YMAPSL_VERSION_NUM );

			wp_register_script( 'yandex-maps-admin-js', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', array( 'jquery' ), '2.1.64', true );
			wp_enqueue_script( 'yandex-maps-admin-js' );

			wp_enqueue_style( 'flatpickr-admin-css', $style_url . 'flatpickr.min.css', false, YMAPSL_VERSION_NUM );
			wp_register_script( 'flatpickr-admin-js', $script_url . 'flatpickr.js', array( 'jquery' ), '2.1.64', true );
			wp_enqueue_script( 'flatpickr-admin-js' );

			wp_enqueue_script( 'ymapsl-admin-js', $script_url . 'ymapsl-admin.js', array(
				'jquery',
				'yandex-maps-admin-js',
                'flatpickr-admin-js'
			), YMAPSL_VERSION_NUM, true );

			wp_enqueue_script( 'parsley-admin-js', $script_url . 'parsley.min.js', array( 'jquery' ), YMAPSL_VERSION_NUM, true );
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
			'metro' => array(
				'label'    => 'Станция метро',
				'required' => false
			),
			'opening_hours' => array(
				'label'    => 'График работы',
				'required' => false
			),
			'hours'         => array(
				'label'    => 'Часы работы',
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

	$screen = 'ymapsl_stores';
	add_meta_box(
		'ymapsl_box_id',           // Unique ID
		'Данные ТА',  // Box title
		'ymapsl_custom_box_html',  // Content callback, must be of type callable
		$screen                   // Post type
	);
}

function ymapsl_custom_box_html( $post ) {

//	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' ); // Получаем все мета поля ymapsl_meta_fields
	wp_nonce_field( 'save_store_meta', 'ymapsl_meta_nonce' );

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
                <p id="ymapsl_metro_field">
                    <label for="ymapsl_field">Станция метро </label>
                    <input name="ymapsl_metro" id="ymapsl_metro"
                           value="<?= get_post_meta( $post->ID, '_ymapsl_metro', true ); ?>">
                </p>
            </div>
            <div class="ymapsl-fields-row">
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

            <div class="ymapsl-store-hours-row">
<!--                <table id="ymapsl-store-hours" class="ymapsl-twentyfour-format">-->
<!--                    <tbody>-->
<!--                    <tr>-->
<!--                        <th>Дни недели</th>-->
<!--                        <th>Часы работы</th>-->
<!--                        <th></th>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Понедельник</td>-->
<!--                        <td id="ymapsl-hours-monday" class="ymapsl-opening-hours ymapsl-active" data-day="monday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][monday_open]" value=""></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Вторник</td>-->
<!--                        <td id="ymapsl-hours-tuesday" class="ymapsl-opening-hours ymapsl-active" data-day="tuesday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][tuesday_open]" value=""></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Среда</td>-->
<!--                        <td id="ymapsl-hours-wednesday" class="ymapsl-opening-hours ymapsl-active" data-day="wednesday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][wednesday_open]" value=""></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Четверг</td>-->
<!--                        <td id="ymapsl-hours-thursday" class="ymapsl-opening-hours ymapsl-active" data-day="thursday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][thursday_open]" value=""></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Пятница</td>-->
<!--                        <td id="ymapsl-hours-friday" class="ymapsl-opening-hours ymapsl-active" data-day="friday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][friday_open]" value=""></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Суббота</td>-->
<!--                        <td id="ymapsl-hours-saturday" class="ymapsl-opening-hours ymapsl-active" data-day="saturday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][saturday]" value="closed"></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td class="ymapsl-opening-day">Воскресенье</td>-->
<!--                        <td id="ymapsl-hours-sunday" class="ymapsl-opening-hours ymapsl-active" data-day="sunday">-->
<!--                            <p class="ymapsl-store-closed">Закрыто<input type="hidden" name="ymapsl[hours][sunday]" value="closed"></p>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            <div class="ymapsl-add-period">-->
<!--                                <div class="ymapsl-icon-plus-circled"></div>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->

                <?php
                $opening_days = array(
	                'monday'    => 'Понедельник',
	                'tuesday'   => 'Вторник',
	                'wednesday' => 'Среда',
	                'thursday'  => 'Четверг',
	                'friday'    => 'Пятница',
	                'saturday'  => 'Суббота',
	                'sunday'    => 'Воскресенье'
                );
                $opening_hours_default = array(
	                'dropdown' => array(
		                'monday'    => '',
		                'tuesday'   => '',
		                'wednesday' => '',
		                'thursday'  => '',
		                'friday'    => '',
		                'saturday'  => '',
		                'sunday'    => ''
	                )
                );

                $opening_hours = get_post_meta( $post->ID, '_ymapsl_hours' );

//                file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/1hours.txt", print_r( $opening_hours, true ) . "\r\n", FILE_APPEND | LOCK_EX );


                if ( !isset( $opening_hours[0]['monday'] ) ) {
	                $opening_hours = $opening_hours_default['dropdown'];
                } else {
	                $opening_hours = $opening_hours[0];
                }
                ?>

                <table id="ymapsl-store-hours" class="ymapsl-twentyfour-format">
                    <tr>
                        <th>Дни недели</th>
                        <th>Часы работы</th>
                        <th></th>
                    </tr>
		            <?php
		            foreach ( $opening_days as $index => $day ) {
			            $i          = 0;
			            $hour_count = count( $opening_hours[$index] );
			            ?>
                        <tr>
                            <td class="ymapsl-opening-day"><?php echo esc_html( $day ); ?></td>
                            <td id="ymapsl-hours-<?php echo esc_attr( $index ); ?>" class="ymapsl-opening-hours" data-day="<?php echo esc_attr( $index ); ?>">
					            <?php
					            if ( $hour_count > 0 ) {
						            // Loop over the opening periods.
						            while ( $i < $hour_count ) {
							            if ( isset( $opening_hours[$index][$i] ) ) {
								            $hours = explode( '-', $opening_hours[$index][$i] );
							            } else {
								            $hours = '';
							            }

							            // If we don't have two parts or one of them is empty, then we set the store to closed.
							            if ( ( count( $hours ) == 2 ) && ( !empty( $hours[0] ) ) && ( !empty( $hours[1] ) ) ) {
								            ?>
                                            <div class="ymapsl-current-period <?php if ( $i > 0 ) { echo 'ymapsl-multiple-periods'; } ?>">
                                                <input name="ymapsl[hours][<?= $index ?>_open][]" class="ymapsl-open-hour" value="<?=$hours[0]?>">
                                                <span> - </span>
                                                <input name="ymapsl[hours][<?= $index ?>_close][]" class="ymapsl-close-hour" value="<?=$hours[1]?>">
                                                <div class="ymapsl-icon-cancel-circled"></div>
                                            </div>
								            <?php
							            } else {
								            echo '<p class="ymapsl-store-closed">Закрыто <input type="hidden" name="ymapsl[hours][' . esc_attr( $index ) . ']" value="closed"></p>';
							            }

							            $i++;
						            }
					            } else {
						            echo '<p class="ymapsl-store-closed">Закрыто <input type="hidden" name="ymapsl[hours][' . esc_attr( $index ) . ']" value="closed"></p>';
					            }
					            ?>
                            </td>
                            <td>
                                <div class="ymapsl-add-period">
                                    <div class="ymapsl-icon-plus-circled"></div>
                                </div>
                            </td>
                        </tr>
			            <?php
		            }
		            ?>
                </table>

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



	if ( empty( $_POST['ymapsl_meta_nonce'] ) || !wp_verify_nonce( $_POST['ymapsl_meta_nonce'], 'save_store_meta' ) )
		return;

	if ( !isset( $_POST['post_type'] ) || 'ymapsl_stores' !== $_POST['post_type'] )
		return;

	if ( ! isset( $_POST['ymapsl_city'] ) || ! isset( $_POST['ymapsl_address'] ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( is_int( wp_is_post_revision( $post_id ) ) )
		return;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return;

	add_filter( 'post_updated_messages', 'your_message' );



	$meta_fields = apply_filters( 'ymapsl_meta_fields', '' );
	foreach ( $meta_fields['Адрес'] as $field_key => $field_data ) {



//		if ( array_key_exists( 'ymapsl_' . $field_key, $_POST ) ) {

		    if ($field_key == 'hours'){

			    $week_days = array(
				    'monday'    => 'Понедельник',
				    'tuesday'   => 'Вторник',
				    'wednesday' => 'Среда',
				    'thursday'  => 'Четверг',
				    'friday'    => 'Пятница',
				    'saturday'  => 'Суббота',
				    'sunday'    => 'Воскресенье'
			    );

			    foreach ( $week_days as $day => $value ) {
				    $i       = 0;
				    $periods = array();
				    $store_hours = $_POST['ymapsl']['hours'];

				    if ( isset( $store_hours[$day . '_open'] ) && $store_hours[$day . '_open'] ) {
					    foreach ( $store_hours[$day . '_open'] as $opening_hour ) {
						    $hours     =  $store_hours[$day.'_open'][$i]  . '-' .  $store_hours[$day.'_close'][$i];
						    $periods[] = $hours;
						    $i++;
					    }
				    }

				    $opening_hours[$day] = $periods;
			    }

			    file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/1hours.txt", print_r( $opening_hours, true ) . "\r\n", FILE_APPEND | LOCK_EX );


			    update_post_meta( $post_id, '_ymapsl_' . $field_key, $opening_hours );
		        continue;
            }

			update_post_meta( $post_id, '_ymapsl_' . $field_key, $_POST[ 'ymapsl_' . $field_key ] );
//		}
	}
}

add_action( 'wp_enqueue_scripts', 'load_scripts_for_frontend' );
function load_scripts_for_frontend() {
	if ( ! is_front_page() ) { //Важно!!! для главной страницы заменить на ( is_front_page() т.е. убираем знак ! )

	    $plugin_url = plugins_url('/',__FILE__);
		$style_url  = plugins_url( '/css/', __FILE__ );
		$script_url = plugins_url( '/js/', __FILE__ );


//		wp_enqueue_style( 'ymapsl-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', false, YMAPSL_VERSION_NUM );
		wp_enqueue_style( 'ymapsl-frontend-css', $style_url . 'frontend-style.css', false, YMAPSL_VERSION_NUM );


		wp_localize_script( 'jquery', 'ymapsl_ajax',
			array(
				'url' => $plugin_url . '/ajax-handler-wp.php'
			)
		);

		wp_register_script( 'ymaps-frontend-js', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', array( 'jquery' ), '2.1.64', true );
		wp_enqueue_script( 'ymaps-frontend-js' );

		wp_enqueue_script( 'ymapsl-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2.min.js', array( 'jquery' ), YMAPSL_VERSION_NUM, true );
		wp_enqueue_script( 'ymapsl-frontend-js', $script_url . 'frontend-script.js', array(
			'jquery',
			'ymaps-frontend-js',
			'ymapsl-select2-js'
		), YMAPSL_VERSION_NUM, true );

	}
}


/**
 * custom option and settings
 */

function ymapsl_settings_init() {
	// register a new setting for "wporg" page
	register_setting( 'ymapsl_settings', 'ymapsl_settings' );

	// register a new section in the "wporg" page
	add_settings_section(
		'ymapsl_section_developers',
		__( 'The Matrix has you.', 'wporg' ),
		'ymapsl_section_developers_cb',
		'ymapsl_settings'
	);

	// register a new field in the "wporg_section_developers" section, inside the "wporg" page
	add_settings_field(
		'ymapsl_field_pill', // as of WP 4.6 this value is used only internally
		// use $args' label_for to populate the id inside the callback
		__( 'Pill', 'wporg' ),
		'ymapsl_field_pill_cb',
		'ymapsl_settings',
		'ymapsl_section_developers',
		[
			'label_for' => 'ymapsl_field_pill',
			'class' => 'ymapsl_row',
			'ymapsl_custom_data' => 'custom',
		]
	);
}

/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'ymapsl_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function ymapsl_section_developers_cb( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
	<?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function ymapsl_field_pill_cb( $args ) {
	// get the value of the setting we've registered with register_setting()
	$options = get_option( 'ymapsl_settings' );
//	file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/1options.txt", print_r( $options, true ) . "\r\n", FILE_APPEND | LOCK_EX );

	// output the field
	?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['ymapsl_custom_data'] ); ?>"
            name="ymapsl_settings[<?php echo esc_attr( $args['label_for'] ); ?>][]"
            multiple="multiple"
    >
        <option value="red" <?= (isset( $options[ $args['label_for'] ] ) && array_search('red', $options[ $args['label_for'] ]) !== false) ? ( 'selected="selected"' ) : ( '' ); ?>>
			<?php esc_html_e( 'red pill', 'wporg' ); ?>
        </option>
        <option value="blue" <?= (isset( $options[ $args['label_for'] ] ) && array_search('blue', $options[ $args['label_for'] ]) !== false ) ? ('selected="selected"') : ( '' ); ?>>
			<?php esc_html_e( 'blue pill', 'wporg' ); ?>
        </option>
    </select>
    <p class="description">
		<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
    </p>
    <p class="description">
		<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
    </p>
	<?php
}

/**
 * top level menu
 */
function ymapsl_settings_page() {
	// add top level menu page

	add_submenu_page(
		'edit.php?post_type=ymapsl_stores',
		'WPOrg Options',
		'WPOrg Options',
		'manage_options',
		'ymapsl_settings',
		'ymapsl_settings_page_html'
	);
}

/**
 * register our ymapsl_settings_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'ymapsl_settings_page' );

/**
 * top level menu:
 * callback functions
 */
function ymapsl_settings_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// wordpress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'ymapsl_messages', 'ymapsl_message', __( 'Settings Saved', 'wporg' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'ymapsl_messages' );
	?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "wporg"
			settings_fields( 'ymapsl_settings' );
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections( 'ymapsl_settings' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
        </form>
    </div>
	<?php
}













add_filter( 'ymapsl_cities', 'get_ymapsl_cities' );
function get_ymapsl_cities( $content ) {

	$ymapsl_cities = array(
		'Москва',
		'Санкт-Петербург',
		'Набережные Челны',
		'Воронеж',
		'Дзержинск',
		'Казань',
		'Калининград',
		'Краснодар',
		'Набережные Челны',
		'Оренбург',
		'Ростов-на-Дону',
		'Самара',
		'Ульяновск',
		'Уфа',
		'Ярославль'
	);

	return $ymapsl_cities;
}

add_shortcode( 'ymapsl', 'ymapsl_frontend' );
function ymapsl_frontend() {

	$ymapsl_cities = apply_filters( 'ymapsl_cities', '' );

	$output = '';
	$output .= '<div id="ymapsl_wrap">' . "\r\n";
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