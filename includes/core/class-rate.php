<?php

class EUVI_Rate {
	public function get_rate( $category_id, $country_id, $type_id ) {
		$handler = new EUVI_API_Handler();
		$data = $handler->handle_request( 'rate', array(
			'category' => intval( $category_id ),
			'country' => intval( $country_id ),
			'type' => intval( $type_id ),
		) );
		$rates = json_decode( $data['body'] );
		$rates = $rates->data;
		$highest_rate_key = 0;
		foreach ( $rates as $key => $value ) {
			if ( 0 == $highest_rate_key ) {
				$highest_rate_key = $key;
			} else {
				if ( $rates[ $highest_rate_key ] < $rates[ $key ] ) {
					$highest_rate_key = $key;
				}
			}
		}

		return $rates[ $highest_rate_key];
	}
}