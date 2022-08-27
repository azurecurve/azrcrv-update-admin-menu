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

	$options = get_option_with_defaults( 'azrcrv-uam' );

	$menu = array();
	if ( $options['enabled'] == 1 and isset( $options['updated-menu'] ) and is_array( $options['updated-menu'] ) ) {

		foreach ( $options['updated-menu'] as $key => $value ) {
			$menu[] = $key;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] != PLUGIN_HYPHEN ) {

			foreach ( $options['removed-menu'] as $key => $value ) {

				remove_menu_page( $key );

			}
		}
	}

	return $menu;
}
