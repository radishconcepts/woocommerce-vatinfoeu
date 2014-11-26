<?php

class EUVI_Rate {
	public function get_rate( $category_id, $country_id, $type_id ) {
		$repository = new EUVI_Rate_Repository();
		$rates = $repository->get_rates( $category_id, $country_id, $type_id );

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