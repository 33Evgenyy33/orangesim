<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.3
 */
class ACA_WC_Column_User_TotalSales extends AC_Column
	implements ACP_Column_SortingInterface, ACP_Export_Column {

	public function __construct() {
		$this->set_type( 'column-wc-user-total-sales' );
		$this->set_label( __( 'Total Sales', 'codepress-admin-columns' ) );
		$this->set_group( 'woocommerce' );
	}

	// Display

	public function get_value( $user_id ) {
		$values = array();

		foreach ( ac_addon_wc_helper()->get_totals_for_user( $user_id ) as $currency => $total ) {
			if ( $total ) {
				$values[] = wc_price( $total );
			}
		}

		if ( ! $values ) {
			return $this->get_empty_char();
		}

		return implode( ' | ', $values );
	}

	public function get_raw_value( $user_id ) {
		return ac_addon_wc_helper()->get_totals_for_user( $user_id );
	}

	// Pro

	public function sorting() {
		return new ACA_WC_Sorting_User_TotalSales( $this );
	}

	public function export() {
		return new ACA_WC_Export_User_TotalSales( $this );
	}

}
