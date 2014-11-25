<?php

class EU_VAT_Info {
	public function __construct() {
		if ( is_admin() ) {
			add_action('init', array( $this, 'init' ) );

			new EUVI_Admin_Tax_Settings();
		}

		new EUVI_Tax();
	}

	public function init() {
		$config = array(
			'slug' => basename(EUVI_PLUGIN_FILE_PATH),
			'proper_folder_name' => 'woocommerce-euvatinfo',
			'plugin_path' => EUVI_PLUGIN_FILE_PATH,
			'api_url' => 'https://api.github.com/repos/radishconcepts/woocommerce-euvatinfo',
			'raw_url' => 'https://raw.github.com/radishconcepts/woocommerce-euvatinfo/master',
			'github_url' => 'https://github.com/radishconcepts/woocommerce-euvatinfo',
			'zip_url' => 'https://github.com/radishconcepts/woocommerce-euvatinfo/zipball/master',
			'sslverify' => true,
			'requires' => '4.0',
			'tested' => '4.0',
			'readme' => 'README.md',
			'access_token' => '',
		);

		new WP_GitHub_Updater($config);
	}
}