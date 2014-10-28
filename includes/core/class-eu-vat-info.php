<?php

class EU_VAT_Info {
	public function __construct() {
		if ( is_admin() ) {
			new EUVI_Admin_Tax_Settings();
		}

		new EUVI_Tax();
	}
}