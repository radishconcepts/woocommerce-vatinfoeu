<?php

class EUVI_Type_Repository extends EUVI_Abstract_Repository {
	private $types = array();

	public function get_types() {
		if ( empty( $this->types ) ) {
			$this->types = $this->query_types();
		}

		return $this->types;
	}

	private function query_types() {
		$handler = new EUVI_API_Handler();
		$data = $handler->handle_request( 'types', array() );
		$return_array = array();

		if ( $this->is_ok_response( $data ) ) {
			$types = json_decode( $data['body'] )->data;

			foreach ( $types as $type ) {
				$return_array[ $type->id ] = $type->name;
			}
		}

		return $return_array;
	}
}