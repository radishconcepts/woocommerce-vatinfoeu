<?php

class EUVI_Tax {
	public function __construct() {
		add_filter( 'woocommerce_cart_tax_totals', array( $this, 'cart_tax_totals' ), 10, 2 );
		add_filter( 'woocommerce_rate_code', array( $this, 'rate_code' ), 10, 2 );
		add_filter( 'pre_option_woocommerce_tax_based_on', array( $this, 'option_tax_based_on' ), 10, 1 );
		add_filter( 'woocommerce_matched_tax_rates', array( $this, 'matched_tax_rates' ), 10, 2 );
	}

	public function cart_tax_totals( $tax_totals, $cart ) {
		if ( 'yes' !== get_option( 'euvi_enabled' ) ) {
			return $tax_totals;
		}

		$taxes = $cart->get_taxes();
		$key = key( $taxes );

		$tax_totals_class = new stdClass();
		$tax_totals_class->tax_rate_id       = 'VAT' . $key;
		$tax_totals_class->is_compound       = $cart->tax->is_compound( $key );
		$tax_totals_class->label             = 'VAT';
		$tax_totals_class->amount            = wc_round_tax_total( $cart->tax_total );
		$tax_totals_class->formatted_amount  = wc_price( wc_round_tax_total( $cart->tax_total ) );
		$tax_totals[] = $tax_totals_class;

		return $tax_totals;
	}

	public function rate_code( $code_string, $key ) {
		if ( 'yes' !== get_option( 'euvi_enabled' ) ) {
			return $code_string;
		}

		return 'VAT' . $key;
	}

	public function option_tax_based_on( $value ) {
		if ( 'yes' === get_option( 'euvi_enabled' ) ) {
			$value = 'shipping';
		}

		return $value;
	}

	public function matched_tax_rates( $matched_tax_rates, $country ) {
		if ( 'yes' === get_option( 'euvi_enabled' ) ) {
			$country_repo = new EUVI_Country_Repository();
			$euvi_country = $country_repo->get_country_by_code( $country );
			$category_id = intval( get_option('euvi_category' ) );

			$rate_calc = new EUVI_Rate();
			$rate = $rate_calc->get_rate( $category_id, $euvi_country['id'] );

			$new_tax_rates[1] = array(
				'rate' => $rate->rate,
				'label' => 'Yo',
				'shipping' => 'yes',
				'compound' => 'no',
			);

			$matched_tax_rates = $new_tax_rates;
		}

		return $matched_tax_rates;
	}
}