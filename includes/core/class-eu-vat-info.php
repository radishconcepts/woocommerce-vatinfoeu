<?php

class VAT_Info_EU {
	public function __construct() {
		if ( is_admin() ) {
			new VIEU_Admin_Tax_Settings();
		}

		new WC_VIEU_Checkout();
		new VIEU_Tax();
	}
}