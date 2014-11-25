<?php

/**
 * Plugin Name: EU VAT Info
 * Author: Radish Concepts
 * Author URI: http://www.radishconcepts.com
 * Version: 0.1
 */

if ( ! class_exists( 'Radish_Autoload_Tester' ) ) {
	include( 'vendor/radishconcepts/radish-autoload-tester/classes/radish-autoload-tester.php' );
}

include('vendor/autoload.php');

define( 'EUVI_PLUGIN_FILE_PATH', __FILE__ );

$autoloader = new Radish_Autoload_Tester( 'EU_VAT_Info' );
new EU_VAT_Info();