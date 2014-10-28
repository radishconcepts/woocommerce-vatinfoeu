<?php

abstract class EUVI_Abstract_Repository {
	protected function is_ok_response( $data ) {
		if ( is_wp_error( $data ) ) {
			return false;
		}

		if ( ! isset( $data['response']['code'] ) || 200 !== $data['response']['code'] ) {
			return false;
		}

		return true;
	}
}