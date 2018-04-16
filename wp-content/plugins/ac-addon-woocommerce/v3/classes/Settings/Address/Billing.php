<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 3.0
 */
class ACA_WC_Settings_Address_Billing extends ACA_WC_Settings_Address {

	public function get_display_options() {
		$options = parent::get_display_options();

		$options['email'] = __( 'Email', 'woocommerce' );
		$options['phone'] = __( 'Phone', 'woocommerce' );

		return $options;
	}

}
