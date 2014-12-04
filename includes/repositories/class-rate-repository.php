<?php

class VIEU_Rate_Repository extends VIEU_Abstract_Repository {
	private $rates = array();

	public function get_rates( $country_id, $type_id, $category_id = null ) {
		if ( empty( $this->rates ) ) {
			$this->rates = $this->query_rates( $country_id, $type_id, $category_id );
		}

		return $this->rates;
	}

	private function query_rates( $country_id, $type_id, $category_id = null ) {
		$handler = new VIEU_WC_API_Handler();
		$arguments = array(
			'category' => intval( $category_id ),
			'country' => intval( $country_id ),
		);

		if ( null !== $type_id ) {
			$arguments['type'] = intval( $type_id );
		}

		$data = $handler->handle_request( 'rate', $arguments );

		$return_array = array();

		if ( $this->is_ok_response( $data ) ) {
			$rates = json_decode( $data['body'] )->data;

			foreach ( $rates as $rate ) {
				$return_array[] = $rate;
			}
		}

		return $return_array;
	}
}