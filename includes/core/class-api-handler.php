<?php

class VIEU_WC_API_Handler {
	private $endpoint = 'http://api.vatinfo.eu/api/v1/';

	public function handle_request( $target, $params = array() ) {
		$url = $this->generate_api_endpoint_url( $target, $params );
		$data = $this->get_request( $url, $params );

		return $data;
	}

	private function generate_api_endpoint_url( $target, $params ) {
		$url = trailingslashit( trailingslashit( $this->endpoint ) . $target );

		foreach ( $params as $key => $value ) {
			$url = add_query_arg( $key, $value, $url );
		}

		return $url;
	}

	private function get_request( $url, $params ) {
		$params['headers'] = array(
			'X-Auth-Token' => get_option('euvi_api_key'),
		);
		return wp_remote_get( $url, $params );
	}
}