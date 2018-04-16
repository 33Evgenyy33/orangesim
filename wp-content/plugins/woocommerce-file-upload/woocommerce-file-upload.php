<?php
/**
 * Plugin Name: WooCommerce File Upload
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: Your extension's description text.
 * Version: 1.0.0
 * Author: WooCommerce
 * Author URI: http://woocommerce.com/
 * Developer: Your Name
 * Developer URI: http://yourdomain.com/
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

if ( is_woocommerce_active() ) {


	//Adds 'Passport' column header to 'Orders' page
	add_filter( 'manage_edit-shop_order_columns', 'sv_wc_cogs_add_order_passport_column_header', 20 );
	function sv_wc_cogs_add_order_passport_column_header( $columns ) {

		$new_columns = array();

		foreach ( $columns as $column_name => $column_info ) {

			$new_columns[ $column_name ] = $column_info;
			if ( 'order_total' === $column_name ) {
				$new_columns['order_passport'] = __( 'Passport', 'my-textdomain' );
			}
		}

		return $new_columns;
	}


	if ( ! function_exists( 'sv_helper_get_order_meta' ) ) :

		//Helper function to get meta for an order.
		function sv_helper_get_order_meta( $order, $key = '', $single = true, $context = 'edit' ) {

			// WooCommerce > 3.0
			if ( defined( 'WC_VERSION' ) && WC_VERSION && version_compare( WC_VERSION, '3.0', '>=' ) ) {

				$value = $order->get_meta( $key, $single, $context );

			} else {

				// have the $order->get_id() check here just in case the WC_VERSION isn't defined correctly
				$order_id = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;
				$value    = get_post_meta( $order_id, $key, $single );
			}

			return $value;
		}

	endif;

	//Adds 'Passport' column content to 'Orders' page
	add_action( 'manage_shop_order_posts_custom_column', 'sv_wc_cogs_add_order_passport_column_content' );
	function sv_wc_cogs_add_order_passport_column_content( $column ) {
		global $post;

		if ( 'order_passport' === $column ) {

			$order = wc_get_order( $post->ID );
			$cost  = sv_helper_get_order_meta( $order, 'uploaded_files' );

			// don't check for empty() since cost can be '0'
			if ( '' !== $cost ) {
				echo '<input type="hidden" name="mv_other_meta_field_nonce" value="' . wp_create_nonce() . '">';

				$html = '';
				$urld = explode( ",", $cost );
				$i    = 1;
				foreach ( $urld as $datum ) {
					$html .= '<p><a target="_blank" href="/wp-content/uploads/passports-from-tourist/' . $datum . '">Скан ' . $i . '</a></p>';
					$i ++;
				}
				echo $html;
			}
		}
	}

	// Добавление metabox загранпаспорта в заказ
	add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
	if ( ! function_exists( 'mv_add_meta_boxes' ) ) {
		function mv_add_meta_boxes() {
			global $woocommerce, $order, $post;

			add_meta_box( 'mv_other_fields', 'Загранпаспорт', 'mv_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
		}
	}

	// добавление в metabox загранпаспорта заказа ссылок на файлы
	if ( ! function_exists( 'mv_save_wc_order_other_fields' ) ) {
		function mv_add_other_fields_for_packaging() {
			global $post;

			$order = wc_get_order( $post->ID );
			$cost  = sv_helper_get_order_meta( $order, 'uploaded_files' );

			// don't check for empty() since cost can be '0'
			if ( '' !== $cost ) {
				echo '<input type="hidden" name="mv_other_meta_field_nonce" value="' . wp_create_nonce() . '">';

				$html = '';
				$urld = explode( ",", $cost );
				$i    = 1;
				foreach ( $urld as $datum ) {
					$html .= '<p><a target="_blank" href="/wp-content/uploads/passports-from-tourist/' . $datum . '">Скан ' . $i . '</a></p>';
					$i ++;
				}
				// print_r($meta_field_data[0]);
				echo $html;
			}

		}
	}

	// Добавляем поле dropzone после деталей клиента на страницу Checkout
	add_action( 'woocommerce_after_checkout_billing_form', 'action_woocommerce_checkout_after_customer_details', 10, 1 );
	function action_woocommerce_checkout_after_customer_details() {
		?>
        <div id="dropzone-wordpress">
            <div id="dropzone-wordpress-form" class="dropzone">
                <div class="dz-message needsclick">
                    Перетащите сюда скан <strong>загранпаспорта</strong><br> или нажмите, чтобы загрузить.
                </div>
            </div>
        </div>
		<?php
	}

	add_action( 'wp_ajax_nopriv_submit_dropzonejs', 'dropzonejs_upload', 10, 1 ); //allow on front-end
	add_action( 'wp_ajax_submit_dropzonejs', 'dropzonejs_upload', 10, 1 );
	function dropzonejs_upload() {
		if ( ! empty( $_FILES ) ) {

			$cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у',
				'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У',
				'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я');
			$lat = array('a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u',
				'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Zh',
				'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U',
				'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'Y', 'Yu', 'Ya');

			$tmp_name = $_FILES["file"]["tmp_name"][0];

			$unic_id_for_file = uniqid( 'pass_' );
			$transform_name   = str_replace( ",", "", str_replace( " ", "-", str_replace( $cyr, $lat, $unic_id_for_file . '-' . $_FILES["file"]["name"][0] ) ) );
			$name             = basename( $transform_name );

			$upload_dir   = wp_get_upload_dir()['basedir'];
			$passport_dir = "$upload_dir/passports-from-tourist";

			wp_mkdir_p( $passport_dir );

			move_uploaded_file( $tmp_name, "$passport_dir/$name" );

			$upload_url   = wp_get_upload_dir()['baseurl'];
			$passport_url = "$upload_url/passports-from-tourist/$name";

			echo $name;
		}

		wp_die();
	}

	add_action( 'wp_ajax_nopriv_remove_dropzonejs_file', 'dropzonejs_remove', 10, 1 ); //allow on front-end
	add_action( 'wp_ajax_remove_dropzonejs_file', 'dropzonejs_remove', 10, 1 );
	function dropzonejs_remove() {

		$fileList   = $_POST['fileList'];
		$upload_dir = wp_get_upload_dir()['basedir'];

		if ( isset( $fileList ) ) {
			unlink( "$upload_dir/passports-from-tourist/$fileList" );
		}

		//$whatever += 10;
		print_r( $fileList );

		wp_die();
	}

}