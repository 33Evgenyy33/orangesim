<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 3.0
 */
class ACA_WC_Column_ShopOrder_OrderNumber extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-order_number' );
		$this->set_group( 'woocommerce' );
		$this->set_label( __( 'Order Number', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return ac_helper()->html->link( get_edit_post_link( $id ), $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		$order = wc_get_order( $id );

		return $order->get_order_number();
	}

}
