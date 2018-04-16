<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 2.0
 */
class ACA_WC_Column_ShopOrder_Date extends AC_Column
	implements ACP_Column_FilteringInterface, ACP_Export_Column {

	public function __construct() {
		$this->set_type( 'order_date' );
		$this->set_original( true );
	}

	protected function register_settings() {
		$width = $this->get_setting( 'width' );

		$width->set_default( 120 );
		$width->set_default( 'px', 'width_unit' );
	}

	public function filtering() {
		return new ACP_Filtering_Model_Post_Date( $this );
	}

	public function export() {
		return new ACP_Export_Model_Post_Date( $this );
	}

}
