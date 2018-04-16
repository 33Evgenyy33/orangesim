<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 3.0.4
 */
class ACA_WC_Column_User_Address extends ACP_Column_Meta
	implements ACP_Export_Column {

	public function __construct() {
		$this->set_type( 'column-wc-user-address' );
		$this->set_label( __( 'Address', 'woocommerce' ) );
		$this->set_group( 'woocommerce' );
	}

	public function get_raw_value( $id ) {
		$meta_key = $this->get_meta_key();

		if ( ! $meta_key ) {
			return wc_get_account_formatted_address( $this->get_address_type(), $id );
		}

		return parent::get_raw_value( $id );
	}

	public function get_meta_key() {
		if ( ! $this->get_address_property() ) {
			return false;
		}

		return $this->get_address_type() . '_' . $this->get_address_property();
	}

	public function export() {
		return new ACP_Export_Model_Value( $this );
	}

	public function filtering() {
		if ( ! $this->get_meta_key() ) {
			return new ACP_Filtering_Model_Disabled( $this );
		}

		return new ACP_Filtering_Model_Meta( $this );
	}

	public function sorting() {
		if ( ! $this->get_meta_key() ) {
			return new ACP_Sorting_Model_Disabled( $this );
		}

		return new ACP_Sorting_Model_Meta( $this );
	}

	public function editing() {
		switch ( $this->get_address_property() ) {
			case '' :
				return new ACP_Editing_Model_Disabled( $this );

			case 'country' :
				return new ACA_WC_Editing_MetaCountry( $this );

			default :
				return new ACP_Editing_Model_Meta( $this );
		}
	}

	private function get_address_type() {
		return $this->get_setting( 'address_type' )->get_value();
	}

	private function get_address_property() {
		$setting = $this->get_setting( 'address_property' );

		if ( ! $setting instanceof ACA_WC_Settings_Address ) {
			return false;
		}

		return $setting->get_value();
	}

	public function register_settings() {
		$this->add_setting( new ACA_WC_Settings_User_AddressType( $this ) );
	}

}
