<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACA_WC_Filtering_ProductVariation_Downloadable extends ACP_Filtering_Model_Meta {

	public function get_filtering_data() {
		$available_options = array(
			'yes' => __( 'Is Downloadable', 'codepress-admin-columns' ),
			'no'  => sprintf( __( 'Exclude %s', 'codepress-admin-columns' ), __( 'Downloadable', 'woocommerce' ) ),
		);

		$options = array();

		foreach ( $this->get_meta_values() as $value ) {
			if ( isset( $available_options[ $value ] ) ) {
				$options[ $value ] = $available_options[ $value ];
			}
		}

		return array(
			'options' => $options,
		);
	}

}
