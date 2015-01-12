<?php

class VIEU_Geolocate {
	public function geolocate_ip( $ip_address = '', $fallback = true ) {
		// If GEOIP is enabled in CloudFlare, we can use that (Settings -> CloudFlare Settings -> Settings Overview)
		// Thanks for this, WooCommerce EU VAT Number extension :)
		if ( ! empty( $_SERVER[ "HTTP_CF_IPCOUNTRY" ] ) ) {
			$country_code = sanitize_text_field( strtoupper( $_SERVER["HTTP_CF_IPCOUNTRY"] ) );
		} else {
			$ip_address = ! empty( $ip_address ) ? $ip_address : $this->get_ip_address();
			$country_code = $this->geolocate_via_api( $ip_address );
		}

		return $country_code;
	}

	private function get_ip_address() {
		return isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	}

	private function geolocate_via_api( $ip_address ) {
		$country_code = get_transient( 'euvi_geoip_' . $ip_address );

		if ( false === $country_code ) {
			$geo_api = new VIEU_Geolocate_API();
			$country_code = $geo_api->get_country_by_ip( $ip_address );
			$country_code = sanitize_text_field( strtoupper( $country_code ) );

			set_transient( 'euvi_geoip_' . $ip_address, $country_code, WEEK_IN_SECONDS );
		}

		return $country_code;
	}
}