<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Filters ClassicPress' default menu order
 */
function update_admin_menu_order( $menu_order ) {

	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	$menu = array();
	if ( $options['enabled'] == 1 && isset( $options['updated-menu'] ) && is_array( $options['updated-menu'] ) ) {

		foreach ( $options['updated-menu'] as $key => $value ) {
			$menu[] = $key;
		}

		//if ( isset( $_GET['page'] ) && $_GET['page'] != PLUGIN_HYPHEN ) {
		if ( !isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && $_GET['page'] != PLUGIN_HYPHEN ) ) {
			
			if ( isset( $options['removed-menu'] ) && is_array( $options['removed-menu'] ) ) {
				
				foreach ( $options['removed-menu'] as $key => $value ) {

					remove_menu_page( $key );

				}
				
			}
			
		}
		
	}

	return $menu;
}