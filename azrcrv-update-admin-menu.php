<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: 	Update Admin Menu
 * Description: 	Reorganise the ClassicPress admin menu by reordering or removing entries.
 * Version: 		2.0.5
 * Requires CP:		1.0
 * Requires PHP:	7.4
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		azrcrv-uam
 * Domain Path:		/assets/languages
 * License:         GPLv2 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Define constants.
 */
const DEVELOPER_SHORTNAME = 'azurecurve';
const DEVELOPER_NAME      = DEVELOPER_SHORTNAME . ' | Development';
const DEVELOPER_RAW_LINK  = 'https://development.azurecurve.co.uk/classicpress-plugins/';
const DEVELOPER_LINK      = '<a href="' . DEVELOPER_RAW_LINK . '">' . DEVELOPER_NAME . '</a>';

const PLUGIN_NAME       = 'Update Admin Menu';
const PLUGIN_SHORT_SLUG = 'update-admin-menu';
const PLUGIN_SLUG       = 'azrcrv-' . PLUGIN_SHORT_SLUG;
const PLUGIN_HYPHEN     = 'azrcrv-uam';
const PLUGIN_UNDERSCORE = 'azrcrv_uam';
const PLUGIN_FILE       = __FILE__;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Include plugin Menu Client.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-populate.php';
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-display.php';

/**
 * Include Update Client.
 */
require_once dirname( PLUGIN_FILE ) . '/libraries/updateclient/UpdateClient.class.php';

/**
 * Include setup of registration activation hook, actions, filters and shortcodes.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/setup.php';

/**
 * Load styles functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-styles.php';

/**
 * Load scripts functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-scripts.php';

/**
 * Load menu functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-menu.php';

/**
 * Load language functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-language.php';

/**
 * Load plugin image functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-plugin-images.php';

/**
 * Load settings functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-settings.php';

/**
 * Load plugin functionality.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/plugin-functionality.php';
