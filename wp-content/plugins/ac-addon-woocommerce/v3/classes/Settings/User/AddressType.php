<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 3.0.4
 */
class ACA_WC_Settings_User_AddressType extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $address_type;

	protected function set_name() {
		$this->name = 'address_type';
	}

	protected function define_options() {
		return array(
			'address_type' => 'billing',
		);
	}

	public function get_dependent_settings() {
		$settings = array();

		switch ( $this->get_address_type() ) {
			case 'shipping':
				$settings[] = new ACA_WC_Settings_Address( $this->column );
				break;
			case 'billing':
				$settings[] = new ACA_WC_Settings_Address_Billing( $this->column );
				break;

		}

		return $settings;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		$options = array(
			'shipping' => __( 'Shipping', 'codepress-admin-columns' ),
			'billing'  => __( 'Billing', 'codepress-admin-columns' ),
		);

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_address_type() {
		return $this->address_type;
	}

	/**
	 * @param string $address_type
	 */
	public function set_address_type( $address_type ) {
		$this->address_type = $address_type;
	}

}
