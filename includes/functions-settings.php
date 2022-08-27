<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Get options including defaults.
 */
function get_option_with_defaults( $option_name ) {

	$defaults = array(
		'enabled' => 1,
		'names'   => array(
			'separator1'         => '<em>Separator 1</em>',
			'separator2'         => '<em>Separator 2</em>',
			'separator-last'     => '<em>Separator 3</em>',
			'edit-comments.php'  => 'Comments',
			'plugins.php'        => 'Plugins',
			'azrcrv-plugin-menu' => 'azurecurve Plugin Menu',
		),
	);

	$options = get_option( $option_name, $defaults );

	$options = recursive_parse_args( $options, $defaults );

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 */
function recursive_parse_args( $args, $defaults ) {
	$new_args = (array) $defaults;

	foreach ( $args as $key => $value ) {
		if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
			$new_args[ $key ] = recursive_parse_args( $value, $new_args[ $key ] );
		} else {
			$new_args[ $key ] = $value;
		}
	}

	return $new_args;
}

/**
 * Display Settings page.
 */
function display_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-uam' ) );
	}

	// Retrieve plugin configuration options from database.
	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	echo '<div id="' . esc_attr( PLUGIN_HYPHEN ) . '-general" class="wrap">';

		echo '<h1>';
			echo '<a href="' . esc_url_raw( DEVELOPER_RAW_LINK ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
			echo esc_html( get_admin_page_title() );
		echo '</h1>';

	// phpcs:ignore.
	if ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible">
					<p><strong>' . esc_html__( 'Settings have been saved.', 'azrcrv-uam' ) . '</strong></p>
				</div>';
	}

		require_once 'tab-settings.php';
		require_once 'tab-instructions.php';
		require_once 'tab-other-plugins.php';
		require_once 'tabs-output.php';
	?>
		
	</div>
	<?php
}

/**
 * Save settings.
 */
function save_options() {
	// Check that user has proper security level.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action', 'azrcrv-uam' ) );
	}
	// Check that nonce field created in configuration form is present.
	if ( ! empty( $_POST ) && check_admin_referer( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ) ) {

		// Retrieve original plugin options array.
		$options = get_option_with_defaults( PLUGIN_HYPHEN );

		// enable ordering of admin menu.
		$option_name = 'enabled';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options[ $option_name ] = 1;
		} else {
			$options[ $option_name ] = 0;
		}

		// save selected order of admin menu.
		$ordered_menu = $_POST['ordered_menu'];
		asort( $ordered_menu );
		$updated_menu = array();
		$order        = 0;
		foreach ( $ordered_menu as $key => $value ) {
			$updated_menu[ sanitize_text_field( $key ) ] = $order;
			$order                                      += 1;
		}
		$options['updated-menu'] = $updated_menu;

		// save selected order of admin menu.
		$removed_menu = $_POST['removed_menu'];
		$updated_menu = array();
		foreach ( $removed_menu as $key => $value ) {
			$updated_menu[ sanitize_text_field( $key ) ] = 1;
		}
		$options['removed-menu'] = $updated_menu;

		// Store updated options array to database.
		update_option( PLUGIN_HYPHEN, $options );

		// Redirect the page to the configuration form that was processed.
		wp_safe_redirect( add_query_arg( 'page', PLUGIN_HYPHEN . '&settings-updated', admin_url( 'admin.php' ) ) );
		exit;
	}
}
