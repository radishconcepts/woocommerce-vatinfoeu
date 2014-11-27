<?php

class EUVI_Rate_Repository extends EUVI_Abstract_Repository {
	private $rates = array();

	public function get_rates( $category_id, $country_id, $type_id = null ) {
		$this->id = 'rates_' . $category_id . '_' . $country_id . '_' . $type_id;

		if ( empty( $this->rates ) ) {
			if ( false === $this->rates = $this->get_data() ) {
				$this->rates = $this->query_rates( $category_id, $country_id, $type_id );
			}
		}

		return $this->rates;
	}

	private function query_rates( $category_id, $country_id, $type_id = null ) {
		$handler = new EUVI_WC_API_Handler();
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

		$this->save_data($return_array);

		return $return_array;
	}
}