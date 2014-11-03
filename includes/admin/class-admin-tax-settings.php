<?php

class EUVI_Admin_Tax_Settings {
	public function __construct() {
		add_filter( 'woocommerce_tax_settings', array( $this, 'tax_settings' ), 10, 1 );
	}

	public function tax_settings( $settings ) {
		$category_repo = new EUVI_Category_Repository();
		$categories[0] = 'Select your category';
		$categories = array_merge( $categories, $category_repo->get_categories() );

		$type_repo = new EUVI_Type_Repository();
		$types[0] = 'Select your type';
		$types = array_merge( $types, $type_repo->get_types() );

		$settings_array = array(
			1 => array(
				'id' => 'euvi_enabled',
				'title' => 'EU VAT Info enabled',
				'desc' => 'Enable the tax rate calculation via the EU VAT Info API',
				'type' => 'checkbox',
				'default' => false,
			),
			2 => array(
				'id' => 'euvi_api_key',
				'title' => 'EU VAT Info API key',
				'type' => 'text',
			),
			3 => array(
				'id' => 'euvi_category',
				'title' => 'EU VAT Info category',
				'desc' => 'The category that should be used to determine tax rates for your products.',
				'type' => 'select',
				'options' => $categories,
			),
			4 => array(
				'id' => 'euvi_type',
				'title' => 'EU VAT Info type',
				'desc' => 'The type that should be used to determine tax rates for your products.',
				'type' => 'select',
				'options' => $types,
			)
		);

		return array_merge( array_slice($settings, 0, 3, true), $settings_array, array_slice($settings, 3, count($settings)-3, false) );
	}
}