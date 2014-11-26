<?php

class EUVI_Rate_Repository extends EUVI_Abstract_Repository {
	private $rates = array();

	public function __construct() {
		$this->id = 'rates';
	}

	public function get_rates($country_id, $category_id) {
		$this->id = 'rates_'.$country_id.'_'.$category_id;

		if ( empty( $this->rates ) ) {
			if ( false === $this->rates = $this->get_data() ) {
				$this->rates = $this->query_rates($country_id, $category_id);
			}
		}

		return $this->rates;
	}

	private function query_rates($country_id, $category_id) {
		$handler = new EUVI_API_Handler();
		$endpoint = 'rate?country='.$country_id.'&category='.$category_id;
		$data = $handler->handle_request( $endpoint, array() );
		$return_array = array();

		if ( $this->is_ok_response( $data ) ) {
			$rates = json_decode( $data['body'] )->data;

			foreach ( $rates as $rate ) {
				$return_array[] = $rate;
			}
		}

		$this->save_data($return_array);

		return $return_array;
	}
}