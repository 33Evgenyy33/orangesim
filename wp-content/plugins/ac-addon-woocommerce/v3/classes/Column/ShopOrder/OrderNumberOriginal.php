<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 3.0.4
 */
class ACA_WC_Column_ShopOrder_OrderNumberOriginal extends AC_Column {

	public function __construct() {
		$this->set_type( 'order_number' );
		$this->set_original( true );
	}

	protected function register_settings() {
		$width = $this->get_setting( 'width' );

		$width->set_default( 300 );
		$width->set_default( 'px', 'width_unit' );
	}

	public function get_value( $id ) {
		return null;
	}

	public function get_raw_value( $id ) {
		$order = wc_get_order( $id );

		return $order->get_order_number();
	}

}
