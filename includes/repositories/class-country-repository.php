<?php

class EUVI_Country_Repository extends EUVI_Abstract_Repository {
	private $countries = array();

	public function __construct() {
		$this->id = 'countries';
	}

	public function get_countries() {
		if ( empty( $this->countries ) ) {
			if ( false === $this->countries = $this->get_data() ) {
				$this->countries = $this->query_countries();
			}
		}

		return $this->countries;
	}

	public function get_country_by_code( $code ) {
		if ( empty( $this->countries ) ) {
			$this->countries = $this->query_countries();
		}

		foreach ( $this->countries as $country ) {
			if ( $code == $country['code'] ) {
				return $country;
			}
		}

		return false;
	}

	private function query_countries() {
		$handler = new EUVI_API_Handler();
		$data = $handler->handle_request( 'countries', array() );
		$return_array = array();

		if ( $this->is_ok_response( $data ) ) {
			$countries = json_decode( $data['body'] )->data;

			foreach ( $countries as $country ) {
				$return_array[] = $country;
			}
		}

		$this->save_data($return_array);

		return $return_array;
	}
}