<?php

class VIEU_Geolocate_API {
	public function get_country_by_ip( $ip ) {
		$handler = new VIEU_WC_API_Handler();
		$data    = $handler->handle_request( 'geolocate-ip', array(
			'ip' => $ip,
		) );

		$response = json_decode( $data['body'] );
		return $response->country;
	}
}