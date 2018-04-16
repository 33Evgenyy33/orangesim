<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.3.1
 */
class ACA_WC_Column_ShopOrder_Product extends AC_Column
	implements ACP_Column_SortingInterface, ACP_Column_FilteringInterface, ACP_Export_Column {

	public function __construct() {
		$this->set_group( 'woocommerce' );
		$this->set_type( 'column-wc-product' );
		$this->set_label( __( 'Product', 'woocommerce' ) );
	}

	// Display

	public function get_value( $order_id ) {
		$product_ids = (array) $this->get_raw_value( $order_id );

		if ( empty( $product_ids ) ) {
			return $this->get_empty_char();
		}

		$value = $this->get_formatted_value( new AC_Collection( $product_ids ) );

		if ( $value instanceof AC_Collection ) {
			$value = $value->filter()->implode( $this->get_separator() );
		}

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $order_id ) {
		global $wpdb;
		$product_ids = array();

		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT om.order_item_id as oid, om.meta_value as pid, om2.meta_value as vid
			FROM 
				{$wpdb->prefix}woocommerce_order_items AS oi
			INNER JOIN 
				{$wpdb->prefix}woocommerce_order_itemmeta AS om ON ( oi.order_item_id = om.order_item_id )
			LEFT JOIN 
				{$wpdb->prefix}woocommerce_order_itemmeta AS om2 ON ( oi.order_item_id = om2.order_item_id )
			WHERE 
				om.meta_key = %s 
			AND 
				om2.meta_key = %s
			AND 
				oi.order_id = %d"
			,
			'_product_id',
			'_variation_id',
			$order_id ) );

		foreach ( $results as $result ) {
			$variation_id = $result->vid;
			if ( $variation_id ) {
				$product_ids[] = $variation_id;

				continue;
			}

			$product_ids[] = $result->pid;
		}

		return $product_ids;
	}

	// Pro

	public function filtering() {
		if ( in_array( $this->get_product_property(), array( 'title', 'sku' ) ) ) {
			return new ACA_WC_Filtering_ShopOrder_Product( $this );
		}

		return new ACP_Filtering_Model_Disabled( $this );
	}

	public function sorting() {
		if ( 'custom_field' === $this->get_product_property() ) {
			return new ACP_Sorting_Model_Disabled( $this );
		}

		return new ACA_WC_Sorting_ShopOrder_Product( $this );
	}

	public function export() {
		return new ACP_Export_Model_StrippedValue( $this );
	}

	// Settings

	public function register_settings() {
		$this->add_setting( new ACA_WC_Settings_ShopOrder_Product( $this ) );
	}

	// Common

	public function get_product_property() {
		return $this->get_setting( 'post' )->get_value();
	}

}
