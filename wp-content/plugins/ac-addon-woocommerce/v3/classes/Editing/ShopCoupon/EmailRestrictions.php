<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACA_WC_Editing_ShopCoupon_EmailRestrictions extends ACP_Editing_Model {

	public function save( $id, $value ) {
		$coupon = new WC_Coupon( $id );

		try {
			$coupon->set_email_restrictions( $value );
		} catch ( WC_Data_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage() );
		}

		return $coupon->save();
	}

	public function get_edit_value( $id ) {
		$values = explode( ',', $this->column->get_value( $id ) );

		return array_combine( $values, $values );
	}

	public function get_view_settings() {
		return array(
			'type' => 'multi_input',
		);
	}

}
