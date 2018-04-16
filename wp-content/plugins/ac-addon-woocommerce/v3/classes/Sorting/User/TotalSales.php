<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACA_WC_Sorting_User_TotalSales extends ACP_Sorting_Model {

	private function get_lifetime_values() {
		global $wpdb;

		$sql = "
			SELECT SUM( pm2.meta_value ) AS total, pm.meta_value AS user_id
			FROM $wpdb->posts as posts
				LEFT JOIN $wpdb->postmeta AS pm ON posts.ID = pm.post_id
				LEFT JOIN $wpdb->postmeta AS pm2 ON posts.ID = pm2.post_id
			WHERE pm.meta_key = '_customer_user'
				AND posts.post_type = 'shop_order'
				AND posts.post_status IN ( 'wc-completed' )
				AND pm2.meta_key = '_order_total'
			GROUP BY pm.meta_value
		";

		return $wpdb->get_results( $sql );
	}

	public function get_sorting_vars() {
		$values = array();

		foreach ( $this->get_lifetime_values() as $value ) {
			$values[ $value->user_id ] = $value->total;
		}

		return array(
			'ids' => $this->sort( $values ),
		);
	}

}
