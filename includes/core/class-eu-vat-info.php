<?php

class VAT_Info_EU {
	public function __construct() {
		if ( is_admin() ) {
			add_action('init', array( $this, 'init' ) );

			new VIEU_Admin_Tax_Settings();
		} else {
			new WC_VIEU_Checkout();
		}

		new VIEU_Tax();
	}

	public function init() {
		$config = array(
			'slug' => plugin_basename(VIEU_PLUGIN_FILE_PATH),
			'proper_folder_name' => 'woocommerce-vatinfoeu',
			'api_url' => 'https://api.github.com/repos/radishconcepts/woocommerce-vatinfoeu',
			'raw_url' => 'https://raw.github.com/radishconcepts/woocommerce-vatinfoeu/master',
			'github_url' => 'https://github.com/radishconcepts/woocommerce-vatinfoeu',
			'zip_url' => 'https://github.com/radishconcepts/woocommerce-vatinfoeu/zipball/master',
			'sslverify' => true,
			'requires' => '4.0',
			'tested' => '4.1',
			'readme' => 'README.md',
			'access_token' => '',
		);

		new WP_GitHub_Updater($config);
	}
}