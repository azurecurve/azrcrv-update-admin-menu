<?php
/*
	language functions
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Load language files.
 */
function load_languages() {
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '../assets/languages';
	load_plugin_textdomain( 'azrcrv-uam', false, $plugin_rel_path );
}
