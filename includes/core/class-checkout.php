<?php

class WC_VIEU_Checkout {
	private $countries = array();

	public function __construct() {
		$country_repo = new VIEU_Country_Repository();
		$this->countries = $country_repo->get_countries();

		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'add_vat_number_field' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'checkout_process' ) );
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'update_checkout_totals' ) );
	}

	public function add_vat_number_field() {
		woocommerce_form_field(
			'vat_number',
			array(
				'type'        => 'text',
				'class'       => array(
					'update_totals_on_change',
					'address-field form-row-wide'
				),
				'label'       => 'VAT Number',
				'placeholder' => 'VAT Number',
				'description' => '',
				'default'     => get_user_meta( get_current_user_id(), 'vat_number', true )
			)
		);
	}

	public function checkout_process() {
		$tax_country = WC()->customer->get_country();
		if ( $this->is_valid_eu_country( $tax_country ) && ! empty( $_POST['vat_number'] ) ) {

		}
	}

	public function update_checkout_totals( $form_data ) {
		$this->reset();

		parse_str( $form_data );

		if ( empty( $billing_country ) && empty( $shipping_country ) ) {
			return;
		}

		if ( empty( $vat_number ) ) {
			return;
		}

		$taxed_country = ( ! empty( $billing_country ) ) ? $billing_country : '';

		if ( $this->validate( wc_clean( $vat_number ), $taxed_country ) ) {
			$this->set_vat_excempt();
		}
	}

	private function is_valid_eu_country( $country_code ) {
		foreach ( $this->countries as $country ) {
			if ( $country->codes->alpha_2 == $country_code ) {
				return true;
			}
		}

		return false;
	}

	private function validate( $vat_number, $country_code ) {
		$this->reset();

		if ( $country_code == WC()->countries->get_base_country() ) {
			return false;
		}

		if ( ! $this->is_valid_eu_country( $country_code ) ) {
			return false;
		}

		// @todo Implement actual check for valid VAT number
		return true;
	}

	private function reset() {
		$this->remove_vat_excempt();
	}

	private function remove_vat_excempt() {
		WC()->customer->set_is_vat_exempt( false );
	}

	private function set_vat_excempt() {
		WC()->customer->set_is_vat_exempt( true );
	}
}