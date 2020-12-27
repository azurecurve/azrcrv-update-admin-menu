<?php
/** 
 * ------------------------------------------------------------------------------
 * Plugin Name: Update Admin Menu 
 * Description: Change the order of the menu items on the admin dashboard.
 * Version: 1.0.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/
 * Text Domain: update-admin-menu
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free sottware released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname(__FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_uam');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_uam_create_admin_menu');
add_action('admin_enqueue_scripts', 'azrcrv_uam_load_admin_css');
add_action('admin_post_azrcrv_uam_save_options', 'azrcrv_uam_save_options');
add_action('plugins_loaded', 'azrcrv_uam_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_uam_add_plugin_action_link', 10, 2);
add_filter('codepotent_update_manager_image_path', 'azrcrv_uam_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_uam_custom_image_url');
add_filter('custom_menu_order', '__return_true'); //enable custom menu order
add_filter('menu_order', 'azrcrv_uam_update_admin_menu_order'); //call function to update menu order

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_uam_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('update-admin-menu', false, $plugin_rel_path);
}

/**
 * Custom plugin image path.
 *
 * @since 1.12.0
 *
 */
function azrcrv_uam_custom_image_path($path){
    if (strpos($path, 'azrcrv-update-admin-menu') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.12.0
 *
 */
function azrcrv_uam_custom_image_url($url){
    if (strpos($url, 'azrcrv-update-admin-menu') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Load css for admin page.
 *
 * @since 1.12.0
 *
 */
function azrcrv_uam_load_admin_css($hook){
	
	if ('azurecurve_page_azrcrv-uam' != $hook){ return; }

	wp_register_style('azrcrv-uam-admin-css', plugin_dir_url(__FILE__).'assets/css/admin.css', false, '1.0.0');
	wp_enqueue_style('azrcrv-uam-admin-css');

}

/**
 * Get options including defaults.
 *
 * @since 1.12.0
 *
 */
function azrcrv_uam_get_option($option_name){
 
	$defaults = array(
						'enabled' => 1,
						'names' => array(
										'separator1' => '<em>Separator 1</em>',
										'separator2' => '<em>Separator 2</em>',
										'separator-last' => '<em>Separator 3</em>',
										'edit-comments.php' => 'Comments',
										'plugins.php' => 'Plugins',
										'azrcrv-plugin-menu' => 'azurecurve Plugin Menu',
									),
					);

	$options = get_option($option_name, $defaults);

	$options = azrcrv_uam_recursive_parse_args($options, $defaults);

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 *
 * @since 1.14.0
 *
 */
function azrcrv_uam_recursive_parse_args( $args, $defaults ) {
	$new_args = (array) $defaults;

	foreach ( $args as $key => $value ) {
		if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
			$new_args[ $key ] = azrcrv_uam_recursive_parse_args( $value, $new_args[ $key ] );
		}
		else {
			$new_args[ $key ] = $value;
		}
	}

	return $new_args;
}

/**
 * Add action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_uam_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-uam').'"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'update-admin-menu').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_uam_create_admin_menu(){
	
	// add settings to from twitter submenu
	$options = azrcrv_uam_get_option('azrcrv-uam');
	
	add_submenu_page("azrcrv-plugin-menu"
						,__("Update Admin Menu Settings", "update-admin-menu")
						,__("Update Admin Menu", "update-admin-menu")
						,'manage_options'
						,'azrcrv-uam'
						,'azrcrv_uam_display_options');
}

/*
 * Display admin page for this plugin
 *
 * @since 1.0.0
 *
 */
function azrcrv_uam_display_options(){

	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.', 'update-admin-menu'));
	}
	
	$options = azrcrv_uam_get_option('azrcrv-uam');
	
	global $menu;
	
	echo '<div id="azrcrv-uam-general" class="wrap azrcrv-uam">
		<fieldset>
			<h1>'.esc_html(get_admin_page_title()).'</h1>';
			
			if(isset($_GET['settings-updated'])){
				echo '<div class="notice notice-success is-dismissible">
					<p><strong>'.esc_html('Settings have been saved.', 'update-admin-menu').'</strong></p>
				</div>';
			}
			
			echo '<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_uam_save_options" />';
				
				wp_nonce_field('azrcrv-uam', 'azrcrv-uam-nonce');
				
				echo '<table class="form-table">
					
					<tr>
						<th scope="row"><label for="widget-width">
							'.__('Enable menu update', 'update-admin-menu').'
						</th>
						<td>
							<input name="enabled" type="checkbox" id="enabled" value="1" '.checked('1', $options['enabled'], false).' />
							<label for="enabled"><span class="description">
								'.__('Enable update of menu order.', 'update-admin-menu').'
							</span></label
						</td>
					</tr>
					
					<tr>
						<th scope="row"><label for="widget-width">
							'.__('Menu Order', 'update-admin-menu').'
						</th>
						<td>
							<span class="description">
								'.__('Adjust the entries below update the menu positions.', 'update-admin-menu').'
							</span>
							<table class="azrcrv-uam">
								<tr>
									<th  class="azrcrv-uam">
										'.__('Menu Item', 'update-admin-menu').'
									</th>
									<th  class="azrcrv-uam">
										'.__('Order', 'update-admin-menu').'
									</th>
								</tr>';
								
								$order = 10000;
								$ordered_menu = array();
								foreach ($menu as $key => $menu_item){
									$order += 10000;
									$ordered_menu[$menu_item[2]] = array(
																			'name' => (isset($options['names'][$menu_item[2]]) ? $options['names'][$menu_item[2]] : $menu_item[0]),
																			'order' => (isset($options['updated-menu'][$menu_item[2]]) ? $options['updated-menu'][$menu_item[2]] : $order),
																		);
								}
								$col = array_column($ordered_menu, "order");
								array_multisort($col, SORT_ASC, $ordered_menu);
								
								$order = 0;
								foreach ($ordered_menu as $key => $menu_item){
									$order += 10;
									echo '<tr class="azrcrv-uam">
										<td class="azrcrv-uam">'.$menu_item['name'].'</td>
										<td class="azrcrv-uam"><input type="number" step=1 name="ordered_menu['.$key.']" class="small-text" value="'.$order.'"></td>
									</tr>';
								}
							
							echo '</table>
						</td>
					</tr>
				
				</table>
				
				<input type="submit" value="'.__('Save Changes', 'update-admin-menu').'" class="button-primary"/>
				
			</form>
		</fieldset>
	</div>';
}

function azrcrv_uam_save_options(){

	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'update-admin-menu'));
	}
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-uam', 'azrcrv-uam-nonce')){
		
		/*
		* UPDATE CPT
		*/
		$option_name = 'enabled';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		/*
		* process queries
		*/
		$ordered_menu = $_POST['ordered_menu'];
		asort($ordered_menu);
		$updated_menu = array();
		$order = 0;
		foreach ($ordered_menu as $key => $value){
			$updated_menu[sanitize_text_field($key)] = $order;
			$order += 1;
		}
		$options['updated-menu'] = $updated_menu;
		
		/*
		* Update options
		*/
		update_option('azrcrv-uam', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-uam&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
* Filters WordPress' default menu order
*/
function azrcrv_uam_update_admin_menu_order($menu_order){
	
	$options = azrcrv_uam_get_option('azrcrv-uam');
	
	if ($options['enabled'] == 1){
		$menu = array();
		foreach ($options['updated-menu'] as $key => $value){
			$menu[] = $key;
		}
	}
	return $menu;
}