<?php

class WC_VIEU_Checkout {
	public function __construct() {
		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'add_vat_number_field' ) );
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
}