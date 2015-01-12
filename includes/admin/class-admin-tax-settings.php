<?php

class VIEU_Admin_Tax_Settings {
	public function __construct() {
		add_filter( 'woocommerce_tax_settings', array( $this, 'tax_settings' ), 10, 1 );
	}

	public function tax_settings( $settings ) {
		$api_key = get_option('vieu_api_key');

		$categories = array();
		$categories[0] = 'Select your category (optional)';

		if ( false !== $api_key && ! empty( $api_key ) ) {
			$category_repo = new VIEU_Category_Repository();

			foreach ( $category_repo->get_categories() as $category ) {
				$categories[ $category->id ] = $category->name;
			}
		}

		$settings_array = array(
			1 => array(
				'id' => 'vieu_enabled',
				'title' => 'EU VAT Info enabled',
				'desc' => 'Enable the tax rate calculation via the EU VAT Info API for customers within the European Union.',
				'type' => 'checkbox',
				'default' => false,
			),
			2 => array(
				'id' => 'vieu_api_key',
				'title' => 'EU VAT Info API key',
				'desc' => 'Enter your API key as provided when you ordered your subscription at <a href="http://vatinfo.eu">vatinfo.eu</a>.',
				'type' => 'text',
			),
			3 => array(
				'id' => 'vieu_category',
				'title' => 'EU VAT Info category',
				'desc' => 'The rate category that should be used to determine tax rates for your products. If none specified, the standard rates of your customers country will be used. This list will only be populated when a valid API key is set.',
				'type' => 'select',
				'options' => $categories,
			)
		);

		return array_merge( array_slice($settings, 0, 2, true), $settings_array, array_slice($settings, 2, count($settings)-2, false) );
	}
}