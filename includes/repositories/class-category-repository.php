<?php

class EUVI_Category_Repository extends EUVI_Abstract_Repository {
	private $categories = array();

	public function get_categories() {
		if ( empty( $this->categories ) ) {
			$this->categories = $this->query_categories();
		}

		return $this->categories;
	}

	private function query_categories() {
		$handler = new EUVI_API_Handler();
		$data = $handler->handle_request( 'categories', array() );
		$return_array = array();

		if ( $this->is_ok_response( $data ) ) {
			$categories = json_decode( $data['body'] )->data;

			foreach ( $categories as $category ) {
				$return_array[ $category->id ] = $category->name;
			}
		}

		return $return_array;
	}
}