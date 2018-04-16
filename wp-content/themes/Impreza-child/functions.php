<?php
/* Custom functions code goes here. */

//=======================================================================================================
// Пример редиректа на определенную страницу
//=======================================================================================================
## Проверяет есть указанная роль в ролях текущего/указанного пользователя
## $roles строка/массив - название роли которую нужно проверить у текущего пользователя
function is_user_role_in( $roles, $user = false ) {
	if ( ! $user ) {
		$user = wp_get_current_user();
	}
	if ( is_numeric( $user ) ) {
		$user = get_userdata( $user );
	}

	if ( empty( $user->ID ) ) {
		return false;
	}

	foreach ( (array) $roles as $role ) {
		if ( isset( $user->caps[ $role ] ) || in_array( $role, $user->roles ) ) {
			return true;
		}
	}

	return false;
}

//add_action( 'init', 'my_insert_post_hook' );
function my_insert_post_hook() {
	file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/server.txt", print_r( $_SERVER, true ), FILE_APPEND | LOCK_EX );
	if ( $_SERVER['REQUEST_URI'] == '/checkout/' && ! is_user_role_in( 'administrator' ) ) { // 94.181.179.216 || $_SERVER['REMOTE_ADDR'] !== '94.181.179.216' || ! is_user_role_in( 'administrator' )
		wp_redirect( '/', 301 );
		exit;
	}
}

//=======================================================================================================
// Настройка почты
//=======================================================================================================
add_action( 'phpmailer_init', 'tweak_mailer_ssl', 999 );
function tweak_mailer_ssl( $phpmailer ) {
	$phpmailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer'       => false,
			'verify_peer_name'  => false,
			'allow_self_signed' => true
		)
	);
}

//=======================================================================================================
// Отключение ненужных скриптов wordpress (с осторожностью)
//=======================================================================================================
//add_action( 'wp_print_scripts', 'de_script', 100 );
//function de_script() {
//
//	if ( ! is_admin() ) {
////		wp_dequeue_script( 'jquery-ui-datepicker' );
////		wp_dequeue_script( 'jquery-ui-datepicker-local' );
//		wp_dequeue_script( 'jquery-ui-datepicker' );
//		wp_deregister_script( 'jquery-ui-datepicker' );
//		wp_dequeue_script( 'jquery-ui-datepicker-local' );
//		wp_deregister_script( 'jquery-ui-datepicker-local' );
//	}
//}

//=======================================================================================================
// Подключение скриптов для разных страниц
//=======================================================================================================
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method() {
	global $post;

	if ( is_front_page() ) {
		wp_enqueue_script( 'my-variations-js', get_stylesheet_directory_uri() . '/js/my-variations.js', array( 'jquery' ), '6.0.0', true );
//		wp_enqueue_script( 'typed-js', get_stylesheet_directory_uri() . '/js/typed.min.js', array( 'jquery' ), '1.7', true );
		wp_enqueue_script( 'stacktable-js', get_stylesheet_directory_uri() . '/js/stacktable.min.js', array( 'jquery' ), '1.7', true );
	}

	wp_enqueue_style( 'fancybox-css', get_stylesheet_directory_uri() . '/css/jquery.fancybox.min.css' );
	wp_enqueue_script( 'fancybox-js', get_stylesheet_directory_uri() . '/js/jquery.fancybox.min.js', array(
		'jquery',
		'gform_gravityforms'
	), '1.7', true );

	if ( $_SERVER['REQUEST_URI'] == '/checkout/' ) { //is_page( 'checkout' ) &&

		wp_enqueue_style( 'suggestions-css', get_stylesheet_directory_uri() . '/css/suggestions.min.css' );
		wp_enqueue_script( 'jquery-xdomainrequest-js', get_stylesheet_directory_uri() . '/js/jquery.xdomainrequest.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'suggestions-js', get_stylesheet_directory_uri() . '/js/jquery.suggestions.min.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_style( 'dropzone-css', get_stylesheet_directory_uri() . '/css/dropzone.min.css' );
		wp_enqueue_script( 'dropzone-js', get_stylesheet_directory_uri() . '/js/dropzone.min.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri() . '/js/jquery.inputmask.bundle.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_style( 'flatpickr-css', get_stylesheet_directory_uri() . '/css/flatpickr.min.css' );
		wp_enqueue_script( 'flatpickr-js', get_stylesheet_directory_uri() . '/js/flatpickr.min.js', array( 'jquery' ), '1.0.0', true );

		wp_register_script( 'mycheckout-js', get_stylesheet_directory_uri() . '/js/mycheckout.js', array(
			'jquery',
			'suggestions-js'
		), '1.0.0', true );
		wp_localize_script( 'mycheckout-js', 'submit_dropzonejs',
			array(
				'url' => admin_url( 'admin-ajax.php' )
			)
		);
		wp_enqueue_script( 'mycheckout-js' );

	}

//	wp_enqueue_script('trianglify-js', 'https://cdnjs.cloudflare.com/ajax/libs/trianglify/1.2.0/trianglify.min.js', array('jquery'), '1.7', true);


//	wp_enqueue_style( 'owl-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css' );
//	wp_enqueue_script('owl-carousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js', array('jquery'), '1.3', true);
}

add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'my-wp-admin', get_stylesheet_directory_uri() . '/css/admin-style.css' );
	if ( 'shop_order' == get_post_type() ) {
		wp_enqueue_script( 'admin-script-js', get_stylesheet_directory_uri() . '/js/admin-script.js', array(
			'jquery',
			'wc-orders'
		), '1.0.0', true );
	}

}, 99 );

//=======================================================================================================
// Префикс заказа
//=======================================================================================================
add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );
function change_woocommerce_order_number( $order_id ) {
	$prefix       = 'R';
	$new_order_id = $prefix . $order_id;

	return $new_order_id;
}

//=======================================================================================================
// Возможность изменить стоимость заказа в админке после оплаты
//=======================================================================================================
add_filter( 'wc_order_is_editable', 'wc_make_processing_orders_editable', 10, 2 );
function wc_make_processing_orders_editable( $is_editable, $order ) {
	if ( $order->get_status() == 'processing' ) {
		$is_editable = true;
	}

	return $is_editable;
}


/**
 * Add a 1% surcharge to your cart / checkout based on delivery country
 * Taxes, shipping costs and order subtotal are all included in the surcharge amount
 *
 * Change $percentage to set the surcharge to a value to suit
 *
 * Add countries to array('US'); to include more countries to surcharge
 * http://en.wikipedia.org/wiki/ISO_3166-1#Current_codes for available alpha-2 country codes
 *
 * Change in_array to !in_array to EXCLUDE the $countries array from surcharges
 *
 * Uses the WooCommerce fees API
 * Add to theme functions.php
 */
add_action( 'woocommerce_cart_calculate_fees', 'woocommerce_custom_surcharge', 10, 2 );
//add_action( 'woocommerce_calculate_totals','woocommerce_custom_surcharge' );

function woocommerce_custom_surcharge( $cart_obj ) {
	global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;


//	file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/cart_calculate_fees.txt", print_r( $test_s, true ), FILE_APPEND | LOCK_EX );

//	$woocommerce->cart->add_fee( 'Paypal Avgift', floatval(100), 1, 'Пониженная ставка' ); // Tax enabled for the fee

    $taxes = 100.00;
	if ( ! empty( $_REQUEST && isset( $_REQUEST['post_data'] ) ) ) {
		$get_string = $_REQUEST['post_data'];
		$get_array  = array();
		parse_str( $get_string, $get_array );
		$fees_array = array(
			'paypal-avgift',
            'Paypal Avgift',
            $taxes
        );
		if ( isset( $get_array['orange_replenishment'] ) ) {
			if ( $get_array['orange_replenishment'] == 611111111 && empty($woocommerce->cart->get_fees())) {
                $woocommerce->cart->fees_api( )->add_fee($fees_array); // Tax enabled for the fee
            }
		}
//		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/cart_calculate_fees.txt", print_r( $woocommerce->cart->get_fees(), true ), FILE_APPEND | LOCK_EX );


	}
}

add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	$items = WC()->cart->get_cart_contents();

	$is_orange = false;
	foreach ( $items as $item ) {
		if ( $item['product_id'] == 1346 ) {
			$is_orange = true;
			break;
		}
	}
	if ( ! $is_orange ) {
		echo '1';
		echo '<style>#dropzone-wordpress, .col-2, .pricing-switcher{display: none}</style>';
		unset( $fields['billing']['autofill_address'] );
		unset( $fields['billing']['billing_country'] );
		unset( $fields['billing']['billing_state'] );
		unset( $fields['billing']['billing_city'] );
		unset( $fields['billing']['billing_address_1'] );
		unset( $fields['billing']['billing_postcode'] );
		unset( $fields['billing']['fias'] );
		unset( $fields['billing']['uploaded_files'] );

		unset( $fields['billing']['activation_date'] );
		unset( $fields['billing']['passport'] );
		unset( $fields['billing']['activation_conditions'] );
		unset( $fields['order']['order_comments'] );
	} else {
		unset( $fields['billing']['orange_replenishment'] );
	}

//	echo '<pre>' . print_r( $items, true ) . '</pre>';
	return $fields;
}

function my_hide_shipping_when_free_is_available( $rates, $package ) {
//	$free = array();
//	echo '<pre>' . print_r( $_POST[], true ) . '</pre>';
	file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/rates.txt", print_r( $package, true ), FILE_APPEND | LOCK_EX );

	$excluded_states = array( 'Пензенская обл', 'HI' );
	if ( ! in_array( WC()->customer->shipping_state, $excluded_states ) ) :
		// Get Free Shipping array into a new array
		$freeshipping = array();
		$freeshipping = $rates['free_shipping:1'];

		// Empty the $available_methods array
		unset( $rates );

		// Add Free Shipping back into $avaialble_methods
		$rates   = array();
		$rates[] = $freeshipping;

	endif;

	return $rates;
}

//add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 10,2 );

add_shortcode( 'quick_buy', 'quick_buy_func' );
function quick_buy_func( $atts ) {
	if ( empty( $atts ) ) {
		return '';
	}

	if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {
		return '';
	}

	return '';

//	$url =  home_url('/cart/?add-to-cart='.'1072');
//
//
//	//$output = '<a href="'.$url.'">Buy Now</a>';
//    $output = '<div class="w-btn-wrapper align_center"><a class="w-btn style_solid color_midnight icon_none" href="'.$url.'" style="font-size:16px;"><span class="w-btn-label">Купить сим-карту</span></a></div>';
//
//	return $output;
}


add_filter( 'woocommerce_add_to_cart_redirect', 'woo_redirect_to_checkout' );
function woo_redirect_to_checkout() {
	$checkout_url = wc_get_checkout_url();

	return $checkout_url;
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
function woo_custom_single_add_to_cart_text() {

	return __( 'Оформить', 'woocommerce' );

}

add_action( 'wcopc_product_selection_fields_after', 'my_scripts_method1' );
function my_scripts_method1() {
	echo '<div>';
}

//=======================================================================================================
// Добавление областе для woocommerce
//=======================================================================================================
add_filter( 'woocommerce_states', 'custom_woocommerce_states' );
function custom_woocommerce_states( $states ) {

	$states['RU'] = array(
		'Респ Адыгея'                              => 'Республика Адыгея',
		'Респ Алтай'                               => 'Республика Алтай',
		'Респ Башкортостан'                        => 'Республика Башкортостан',
		'Респ Бурятия'                             => 'Республика Бурятия',
		'Респ Дагестан'                            => 'Республика Дагестан',
		'Респ Ингушетия'                           => 'Республика Ингушетия',
		'Кабардино-Балкарская Респ'                => 'Кабардино-Балкарская республика',
		'Респ Калмыкия'                            => 'Республика Калмыкия',
		'Карачаево-Черкесская Респ'                => 'Карачаево-Черкесская республика',
		'Респ Карелия'                             => 'Республика Карелия',
		'Респ Коми'                                => 'Республика Коми',
		'Респ Крым'                                => 'Крым',
		'Респ Марий Эл'                            => 'Республика Марий Эл',
		'Респ Мордовия'                            => 'Республика Мордовия',
		'Респ Саха /Якутия/'                       => 'Республика Саха (Якутия)',
		'Респ Северная Осетия - Алания'            => 'Респ Северная Осетия-Алания',
		'Респ Татарстан'                           => 'Республика Татарстан',
		'Респ Тува'                                => 'Республика Тыва',
		'Удмуртская Респ'                          => 'Удмуртская республика',
		'Респ Хакасия'                             => 'Республика Хакасия',
		'Чеченская Респ'                           => 'Чеченская республика',
		'Чувашская Республика - Чувашия'           => 'Чувашская республика',
		'Алтайский край'                           => 'Алтайский край',
		'Забайкальский край'                       => 'Забайкальский край',
		'Камчатский край'                          => 'Камчатский край',
		'Краснодарский край'                       => 'Краснодарский край',
		'Красноярский край'                        => 'Красноярский край',
		'Пермский край'                            => 'Пермский край',
		'Приморский край'                          => 'Приморский край',
		'Ставропольский край'                      => 'Ставропольский край',
		'Хабаровский край'                         => 'Хабаровский край',
		'Амурская обл'                             => 'Амурская область',
		'Архангельская обл'                        => 'Архангельская область',
		'Астраханская обл'                         => 'Астраханская область',
		'Белгородская обл'                         => 'Белгородская область',
		'Брянская обл'                             => 'Брянская область',
		'Владимирская обл'                         => 'Владимирская область',
		'Волгоградская обл'                        => 'Волгоградская область',
		'Вологодская обл'                          => 'Вологодская область',
		'Воронежская обл'                          => 'Воронежская область',
		'Ивановская обл'                           => 'Ивановская область',
		'Иркутская обл'                            => 'Иркутская область',
		'Калининградская обл'                      => 'Калининградская область',
		'Калужская обл'                            => 'Калужская область',
		'Кемеровская обл'                          => 'Кемеровская область',
		'Кировская обл'                            => 'Кировская область',
		'Костромская обл'                          => 'Костромская область',
		'Курганская обл'                           => 'Курганская область',
		'Курская обл'                              => 'Курская область',
		'Ленинградская обл'                        => 'Ленинградская область',
		'Липецкая обл'                             => 'Липецкая область',
		'Магаданская обл'                          => 'Магаданская область',
		'Московская обл'                           => 'Московская область',
		'Мурманская обл'                           => 'Мурманская область',
		'Нижегородская обл'                        => 'Нижегородская область',
		'Новгородская обл'                         => 'Новгородская область',
		'Новосибирская обл'                        => 'Новосибирская область',
		'Омская обл'                               => 'Омская область',
		'Оренбургская обл'                         => 'Оренбургская область',
		'Орловская обл'                            => 'Орловская область',
		'Пензенская обл'                           => 'Пензенская область',
		'Псковская обл'                            => 'Псковская область',
		'Ростовская обл'                           => 'Ростовская область',
		'Рязанская обл'                            => 'Рязанская область',
		'Самарская обл'                            => 'Самарская область',
		'Саратовская обл'                          => 'Саратовская область',
		'Сахалинская обл'                          => 'Сахалинская область',
		'Свердловская обл'                         => 'Свердловская область',
		'Смоленская обл'                           => 'Смоленская область',
		'Тамбовская обл'                           => 'Тамбовская область',
		'Тверская обл'                             => 'Тверская область',
		'Томская обл'                              => 'Томская область',
		'Тульская обл'                             => 'Тульская область',
		'Тюменская обл'                            => 'Тюменская область',
		'Ульяновская обл'                          => 'Ульяновская область',
		'Челябинская обл'                          => 'Челябинская область',
		'Ярославская обл'                          => 'Ярославская область',
		'г Москва'                                 => 'Москва',
		'г Санкт-Петербург'                        => 'Санкт-Петербург',
		'г Севастополь'                            => 'Севастополь',
		'Еврейская Аобл'                           => 'Еврейская автономная область',
		'Ненецкий АО'                              => 'Ненецкий автономный округ',
		'Ханты-Мансийский Автономный округ - Югра' => 'Ханты-Мансийский автономный округ - Югра',
		'Чукотский АО'                             => 'Чукотский автономный округ',
		'Ямало-Ненецкий АО'                        => 'Ямало-Ненецкий автономный округ',
		'-'                                        => '-',
	);

	return $states;
}

//=======================================================================================================
// Название полей формы заказа
//=======================================================================================================
add_filter( 'woocommerce_default_address_fields', 'bbloomer_override_postcode_validation', 10, 1 );
function bbloomer_override_postcode_validation( $address_fields ) {
	$address_fields['postcode']['required']     = false;
	$address_fields['postcode']['label']        = 'Почтовый индекс (для почты РФ)';
	$address_fields['city']['label']            = 'Город (населенный пункт)';
	$address_fields['address_1']['placeholder'] = 'Улица, номер дома ';

	$address_fields['country']['required']   = false;
	$address_fields['state']['required']     = false;
	$address_fields['city']['required']      = false;
	$address_fields['address_1']['required'] = false;

	return $address_fields;
}

//=======================================================================================================
// Данные для email письма
//=======================================================================================================
add_action( 'woocommerce_email_header', 'wc_email_header_order_id', 10, 2 );
function wc_email_header_order_id( $email_heading, $email ) {
	// Set global variable data: user ID and Order ID
	$GLOBALS['emails_custom_data'] = array(
//		'user_id' => get_post_meta( $email->object->ID, '_customer_user', true ), // Set the user ID
		'order_id' => $email->object->get_id(), // Set the Order ID
		'email'    => $email,
	);
}

add_filter( 'woocommerce_available_payment_gateways', 'filter_gateways' );
function filter_gateways( $gateways ) {
	if ( is_checkout() ) {
		$payment_NAME = 'cheque';

		$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
		//var_dump($chosen_methods);
		$chosen_shipping = $chosen_methods[0];

		if ( $chosen_shipping == 'local_pickup_plus' ) {
			unset( $gateways[ $payment_NAME ] );
		}

		if ( $chosen_shipping == null ) {
			unset( $gateways[ $payment_NAME ] );
		}

		return $gateways;
	}
}

//add_filter( 'gettext', 'bbloomer_translate_woocommerce_string', 999 );
//function bbloomer_translate_woocommerce_string( $translated ) {
//	$translated = str_ireplace( 'Order #%s', 'Заказ № %s', $translated );
//	return $translated;
//}

//function my_text_strings( $translated_text, $text, $domain ) {
//	switch ( $translated_text ) {
//		case 'Order #%s' :
//			$translated_text = __( 'Заказ № %s', 'woocommerce' );
//			break;
//	}
//	return $translated_text;
//}
//add_filter( 'gettext', 'my_text_strings', 20, 3 );

//=======================================================================================================
// Загрузка кода callback после футора
//=======================================================================================================
add_action( 'us_after_footer', 'callback_us_after_footer' );
function callback_us_after_footer() {
	//echo get_the_ID();
	echo '<div style="display: none;" id="callback_request">';
	gravity_form( 1, true, false, false, '', true, 12 );
//	echo do_shortcode('[product_page id="1346"]');
	echo '</div>';
	echo '<div class="log-in">';
	echo '  <a data-fancybox data-src="#callback_request" href="javascript:;" class="callback-modal" id="popup__toggle">';
	echo '  <div class="circlephone" style="transform-origin: center;"></div>';
	echo '  <div class="circle-fill" style="transform-origin: center;"></div>';
	echo '  <div class="img-circle" style="transform-origin: center;">';
	echo '    <i style="" class="fas fa-phone img-circleblock"></i>';
	echo '  </div>';
	echo '  </a>';
	echo '</div>';

	?>

    <!--	<script>-->
    <!--        jQuery(document).ready(function($) {-->
    <!---->
    <!--            $(document).on('click', '.myBtn', function(e) {-->
    <!--                e.preventDefault();-->
    <!--                $.fancybox.open({-->
    <!--                    src  : 'https://orangesim.ru/wp-admin/admin-ajax.php?action=show_product',-->
    <!--                    type : 'ajax',-->
    <!--                    opts : {-->
    <!--                        onComplete : function() {-->
    <!---->
    <!--                        }-->
    <!--                    }-->
    <!--                });-->
    <!--                return false;-->
    <!--            });-->
    <!--        });-->
    <!--	</script>-->
	<?php
}

// Conditional Show hide checkout fields based on chosen shipping methods
add_action( 'wp_footer', 'conditionally_hidding_billing_company' );
function conditionally_hidding_billing_company() {
	// Only on checkout page
	if ( ! is_checkout() ) {
		return;
	}

	// HERE your shipping methods rate ID "Home delivery"
	$home_delivery = 'local_pickup_plus';
	?>
    <script>
        jQuery(function ($) {
            // Choosen shipping method selectors slug
            // var shipMethod = 'input[name^="shipping_method"]',
            //     shipMethodChecked = shipMethod+':checked';
            var shipMethodChecked = '#shipping_method_0';
            var shipMethod = 'input[name^="shipping_method"]';

            // Function that shows or hide imput select fields
            function showHide(actionToDo = 'show', selector = '') {
                if (actionToDo == 'show')
                    $(selector).show(200, function () {
                        $(this).addClass("validate-required");
                    });
                else
                    $(selector).hide(200, function () {
                        $(this).removeClass("validate-required");
                    });
                $(selector).removeClass("woocommerce-validated");
                $(selector).removeClass("woocommerce-invalid woocommerce-invalid-required-field");
            }

            // Initialising: Hide if choosen shipping method is "Home delivery"
            if ($(shipMethodChecked).val() == '<?php echo $home_delivery; ?>')
                showHide('hide', '#billing_first_name_field');

            // Live event (When shipping method is changed)
            $('form.checkout').on('change', shipMethod, function () {
                if ($(shipMethodChecked).val() == '<?php echo $home_delivery; ?>')
                    showHide('hide', '#billing_first_name_field');
                else
                    showHide('show', '#billing_first_name_field');
            });
        });
    </script>
	<?php
}

//add_filter( 'the_content', 'wpse_225562_replace_for_signup' );
function wpse_225562_replace_for_signup( $content ) {

	$my_postid = 83082;//5
//	$content = apply_filters('the_content', get_post_field('post_content', $my_postid));
	$content = get_post_field( 'post_content', $my_postid );
//	$content = apply_filters('the_content', $content);
//	$content = str_replace(']]>', ']]>', $content);


	return $content;
}

//=======================================================================================================
// Данные для email письма
//=======================================================================================================
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_shipping', 10, 2 );
function woocommerce_checkout_shipping() {
	?>
    <div class="pricing-switcher">
        <p class="fieldset">
            <input type="radio" name="duration-1" value="shipping" id="monthly-1" checked>
            <label for="monthly-1">Доставка</label>
            <input type="radio" name="duration-1" value="pickup" id="yearly-1">
            <label for="yearly-1">Самовывоз</label>
            <span class="switch"></span>
        </p>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $("#activation_date_field label").html("<p style='margin-bottom: 0;line-height: 17px'><strong>Желаемая дата активации. <abbr class='required' title='обязательно'>*</abbr></strong><br><span style='font-size: 14px;font-weight: 400;color: #000'>Активация сим-карты производится в будние дни. В праздничные и выходные дни сим-карты не активируются.</span></p>")

            // $('form[name="checkout"]').attr("id","checkout").addClass("wc-checkout-add-ons-dropzone");
            // $('form[name="checkout"]').attr("id","checkout").addClass("dropzone")

            // var myDropzone = new Dropzone("div#dropzone-wordpress-form", { url: "https://orangesim.ru/wp-admin/admin-ajax.php"});


            var count_load = 0;
            $(document.body).on('updated_checkout', function () {

                console.log('count_load: ' + count_load);

                if ($('#shipping_method input[value="local_pickup_plus"]:checked').length > 0 && $('.pricing-switcher input[value="shipping"]:checked').length > 0 && count_load <= 0) {
                    // $('.pricing-switcher input[value="shipping"]').prop("checked", false);
                    $('.pricing-switcher input[value="shipping"]').removeAttr('checked');
                    $('.pricing-switcher input[value="pickup"]').prop("checked", true);

                    $("#autofill_address").val('-');
                    // showShippingMethod('pickup');
                    // console.log($('.pricing-switcher input[value="pickup"]').is(':checked'));
                    count_load++;
                } else if (count_load <= 0) {
                    $('#shipping_method input[value="local_pickup_plus"]').parent().hide();
                    // showShippingMethod('shipping');
                    count_load++;
                }
                if ($('.pricing-switcher input:checked').val() == 'pickup' && count_load >= 1) {//&& $('#shipping_method input[value="local_pickup_plus"]:checked').length > 0
                    if ($('#shipping_method input[value="local_pickup_plus"]:checked').length <= 0) {
                        $('#shipping_method input[value="local_pickup_plus"]').prop("checked", true).change();
                    }
                    showShippingMethod('pickup');
                } else if ($('.pricing-switcher input:checked').val() == 'shipping' && count_load >= 1) {
                    showShippingMethod('shipping');
                }


                // var switcher_val = $('.pricing-switcher input:checked').val();
                //
                // if (switcher_val == 'shipping') {
                //
                // } else {
                //
                // }

            });


            // Function that shows or hide imput select fields
            function showShippingMethod(selector = '') {
                if (selector === "shipping") {
                    $("#autofill_address_field").show();
                    $('tr.shipping th').text('Доставка');
                    $('#shipping_method input[value="local_pickup_plus"]').parent().hide();
                    $("#shipping_method input").each(function () {
                        if ($(this).val() !== 'local_pickup_plus') {
                            $(this).parent().show();
                        }
                    });

                } else {
                    $("#autofill_address_field").hide();
                    $('div#order_review tr.shipping th').text('Самовывоз');
                    $('div#order_review #shipping_method input[value="local_pickup_plus"]').parent().show();
                    $("div#order_review #shipping_method input").each(function () {
                        if ($(this).val() !== 'local_pickup_plus') {
                            $(this).parent().hide();
                        }
                    });

                }
            }

            // var filter_radios = $('.pricing-switcher').find('input[type="radio"]');
            $('.pricing-switcher').find('input[type="radio"]').on("change", function (event) {
                var selected_filter = $(event.target).val();
                console.log(selected_filter);
                if (selected_filter === "shipping") {

                    $("#autofill_address_field").show();
                    $("#autofill_address").val('');
                    $("#billing_country").val('RU');
                    $("#billing_state").val('-');
                    $("#billing_city").val('');
                    $("#billing_address_1").val('');
                    $("#billing_postcode").val('');
                    $("#billing_address_1").change();
                    $(document.body).trigger('update_checkout');

                    showShippingMethod('shipping');
                } else {

                    $("#autofill_address_field").hide();
                    $("#autofill_address").val('-');
                    $("#billing_country").val('RU');
                    $("#billing_state").val('-');
                    $("#billing_city").val('-');
                    $("#billing_postcode").val('-');
                    $("#billing_address_1").val('Самовывоз');
                    $("#billing_address_1").change();
                    $(document.body).trigger('update_checkout');

                    // $("#billing_address_1_field").hide();


                    showShippingMethod('pickup');
                }


            });
        });
    </script>
    <style>
        .pricing-switcher {
            text-align: center;
            margin-bottom: 50px;
        }

        .pricing-switcher .fieldset {
            display: inline-block;
            position: relative;
            padding: 2px;
            border-radius: 0em;
            border: 2px solid #423d70;
            border: 2px solid #3659db;
        }

        .pricing-switcher input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .pricing-switcher label {
            position: relative;
            z-index: 1;
            display: inline-block;
            float: left;
            width: 150px;
            height: 50px;
            line-height: 50px;
            cursor: pointer;
            font-size: 1.2rem;
            color: #ffffff;
        }

        .pricing-switcher input:not(:checked) + label {
            position: relative;
            z-index: 1;
            display: inline-block;
            float: left;
            width: 150px;
            height: 50px;
            line-height: 50px;
            cursor: pointer;
            font-size: 1.2rem;
            color: #423d71;
            color: #133bd2;
            transition: color 0.3s ease;
        }

        .pricing-switcher .switch {
            position: absolute;
            top: 2px;
            left: 2px;
            height: 50px;
            width: 150px;
            background: linear-gradient(45deg, #5769ff 50%, #3659db 100%);
            background: linear-gradient(45deg, #504a87 50%, #3f3a6c 100%);
            background: linear-gradient(45deg, #5769ff 50%, #3659db 100%);
            border-radius: 0em;
            -webkit-transition: -webkit-transform 0.5s;
            -moz-transition: -moz-transform 0.5s;
            transition: transform 0.5s;
        }

        .pricing-switcher input[type="radio"]:checked + label + .switch,
        .pricing-switcher input[type="radio"]:checked + label:nth-of-type(n) + .switch {
            -webkit-transform: translateX(150px);
            -moz-transform: translateX(150px);
            -ms-transform: translateX(150px);
            -o-transform: translateX(150px);
            transform: translateX(150px);
        }

        .no-js .pricing-switcher {
            display: none;
        }
    </style>
	<?php
}


//add_action( 'woocommerce_email_before_order_table', 'my_completed_order_email_instructions', 10, 4 );
//add_action( 'woocommerce_email_order_details', 'my_completed_order_email_instructions', 100, 4 );

//function my_completed_order_email_instructions( $order, $sent_to_admin, $plain_text, $email ) {
//
//	// Only for "Customer Completed Order" email notification
////	if( 'wc_order_status_email_19300' != $email->id ) return;
//	if( 'customer_completed_order' != $email->id ) return;
//
//
//	// Comptibility With WC 3.0+
//	if ( method_exists( $order, 'get_id' ) ) {
//		$order_id = $order->get_id();
//	} else {
//		$order_id = $order->id;
//	}
//	//$order->has_shipping_method('')
//	$payment_method = get_post_meta($order_id, '_payment_method', true);
//	$shipping_method_arr = get_post_meta($order_id, '_shipping_method', false); // an array
//	$method_id = explode( ':', $shipping_method_arr[0][0] );
//	$method_id = $method_id[0];  // We get the slug type method
//
//
//	if ( 'cod' == $payment_method && 'local_pickup' == $method_id ){
//		echo "something1";
//	} elseif ( 'bacs' == $payment_method && 'local_pickup' == $method_id ){
//		echo "something2";
//	} else {
//		echo "something3";
//	}
//}


//add_action('woocommerce_after_checkout_validation', 'm_prevent_submission', 10,2);
//
//function m_prevent_submission($errors) {
//
////	file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/file_vali.txt", print_r( $_POST, true )."\r\n", FILE_APPEND | LOCK_EX );
//
//	if ( empty( $errors ) ) {
//		wc_add_notice(__('All <strong>Fine</strong>!'), 'success');
////		return;
//	}
//
////	if ( isset($_POST['m_prevent_submit']) && wc_notice_count( 'error' ) === 0 ) {
////
////		wc_add_notice( __( "custom_notice", 'm_example' ), 'error');
////// change the data in $posted here
////
////	}
//
//}

/**
 * Save the custom field at shipping calculator.
 */
//function my_custom_shipping_calculator_field() {
//	$area = isset( $_REQUEST['fias'] ) ? $_REQUEST['fias'] : '';
//	if ( $area ) {
//		WC()->customer->__set( 'fias', $area );
//	}
//}
//
//add_action( 'woocommerce_calculated_shipping', 'my_custom_shipping_calculator_field' );

//add_filter( 'woocommerce_shipping_calculator_enable_fias', '__return_true' );

add_action( 'woocommerce_checkout_create_order', 'add_domain_to_order_meta', 10, 2 );
function add_domain_to_order_meta( $order, $data ) {
	$order->add_meta_data( 'euro_rate', '78' );
}