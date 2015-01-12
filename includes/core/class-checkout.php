<?php

class WC_VIEU_Checkout {
	private $countries = array();

	public function __construct() {
		$country_repo = new VIEU_Country_Repository();
		$this->countries = $country_repo->get_countries();

		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'add_vat_number_field' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'checkout_process' ) );
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'update_checkout_totals' ) );
		add_action( 'woocommerce_review_order_before_submit', array( $this, 'location_confirmation' ) );

		// Save VAT number in order meta
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'order_data' ), 10, 1 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ), 10, 2 );
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

	public function location_confirmation() {
		if ( $this->location_confirmation_required() ) {
			$location_confirmation_is_checked = isset( $_POST['location_confirmation'] );
			$countries                        = WC()->countries->get_countries();

			echo '<p class="form-row location_confirmation terms">';
			echo '<label for="location_confirmation" class="checkbox">' . sprintf( 'I am established, have my permanent address, or usually reside within <strong>%s</strong>.', $countries[ WC()->customer->get_country() ] ) . '</label>';
			echo '<input type="checkbox" class="input-checkbox" name="location_confirmation"'. checked( $location_confirmation_is_checked, true ) .' id="location_confirmation" />';
			echo '</p>';
		}
	}

	private function location_confirmation_required() {
		$taxed_country = WC()->customer->get_country();

		$ip_country = $this->get_country_by_ip();

		return ( $taxed_country !== $ip_country );
	}

	public function checkout_process() {
		$this->reset();

		$taxed_country = WC()->customer->get_country();

		$ip_country = $this->get_country_by_ip();

		if ( $ip_country !== $taxed_country && empty( $_POST['location_confirmation'] ) ) {
			wc_add_notice( 'Your IP Address does not match your billing country. Please confirm you are located within your billing country using the checkbox below.', 'error' );
		}

		if ( empty( $_POST['vat_number'] ) ) {
			return;
		}

		if ( $this->validate( wc_clean( $_POST['vat_number'] ), $taxed_country ) ) {
			$this->set_vat_excempt();
		} else {
			wc_add_notice( sprintf( 'The VAT number (%s) is invalid for your billing country.', $_POST['vat_number'] ), 'error' );
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

	public function order_data($order) {
		echo '<p><strong>'.__('VAT Number').':</strong> ' . get_post_meta( $order->id, '_vat_number', true ) . '</p>';
	}

	public function update_order_meta( $order_id, $posted ) {
		if ( ! empty( $_POST['vat_number'] ) ) {
			update_post_meta( $order_id, '_vat_number', sanitize_text_field( $_POST['vat_number'] ) );
		}

		// We can assume that the VAT number has been validated if it is posted since we enforce it to be valid if entered
		update_post_meta( $order_id, '_vat_number_is_validated', true );
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

		$validator = new VIEU_VAT_Validator();
		return $validator->validate_vat($country_code, $vat_number);
	}

	private function get_country_by_ip() {
		$geolocate = new VIEU_Geolocate();
		return $geolocate->geolocate_ip();
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