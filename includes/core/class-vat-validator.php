<?php

class VIEU_VAT_Validator {
	public function validate_vat($countryCode, $vatNumber) {
		$handler = new VIEU_WC_API_Handler();
		$data = $handler->handle_request( 'validate-vat', array(
			'countryCode' => $countryCode,
			'vatNumber' => $vatNumber
		) );

		$response = json_decode( $data['body'] );
		return ($response->valid === true );
	}
}