<?php

/**
 * Plugin Name: VAT Info EU
 * Description: Integrates the EU VAT API with your WooCommerce powered website
 * Author: Radish Concepts
 * Author URI: http://www.radishconcepts.com
 * Version: 0.3
 */

if ( ! class_exists( 'VAT_Info_EU' ) ) {
	// Load PHP 5.2 compatible autoloader if required
	if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
		include( 'vendor/autoload.php' );
	} else {
		include( 'vendor/autoload_52.php' );
	}
}

define( 'VIEU_PLUGIN_FILE_PATH', __FILE__ );
new VAT_Info_EU();
