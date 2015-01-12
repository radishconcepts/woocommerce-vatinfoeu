<?php

class VIEU_Tax {
	public function __construct() {
		add_filter( 'woocommerce_rate_code', array( $this, 'rate_code' ), 10, 2 );
		add_filter( 'pre_option_woocommerce_tax_based_on', array( $this, 'option_tax_based_on' ), 10, 1 );
		add_filter( 'woocommerce_matched_tax_rates', array( $this, 'matched_tax_rates' ), 10, 2 );
	}

	public function rate_code( $code_string, $key ) {
		if ( 'yes' !== get_option( 'vieu_enabled' ) ) {
			return $code_string;
		}

		return 'VAT' . $key;
	}

	public function option_tax_based_on( $value ) {
		if ( 'yes' === get_option( 'vieu_enabled' ) ) {
			$value = 'shipping';
		}

		return $value;
	}

	public function matched_tax_rates( $matched_tax_rates, $country ) {
		$api_key = get_option('vieu_api_key');
		if ( 'yes' === get_option( 'vieu_enabled' ) ) {
			if ( false !== $api_key && ! empty( $api_key ) ) {
				$country_repo = new VIEU_Country_Repository();
				$vieu_country = $country_repo->get_country_by_code( $country );

				// This method will return false when country is not found in the API
				// In most cases this means that a country is not within the EU.
				if ( false === $vieu_country ) {
					return $matched_tax_rates;
				}

				$category_id = get_option( 'vieu_category', null );

				$rate_calc = new VIEU_Rate();
				$rate      = $rate_calc->get_rate( $vieu_country->id, $category_id );

				$new_tax_rates[1] = array(
					'rate'     => $rate->rate,
					'label'    => 'Yo',
					'shipping' => 'yes',
					'compound' => 'no',
				);

				$matched_tax_rates = $new_tax_rates;
			}
		}

		return $matched_tax_rates;
	}
}