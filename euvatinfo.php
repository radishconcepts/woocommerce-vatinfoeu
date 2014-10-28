<?php

/**
 * Plugin Name: EU VAT Info
 * Author: Radish Concepts
 * Author URI: http://www.radishconcepts.com
 * Version: 1.0-very-beta
 */

add_action( 'plugins_loaded', 'euvi_load' );

function euvi_load() {
	// Load PHP 5.2 compatible autoloader if required
	if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
		include('vendor/autoload.php');
	} else {
		include('vendor/autoload_52.php');
	}

	// Setup the core class instance
	new EU_VAT_Info();
}