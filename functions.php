<?php
/**
 * Plugin Name: VA Extra Settings
 * Plugin URI: https://github.com/visualive/va-extra-settings
 * Description: Add extra settings to the WordPress.
 * Author: KUCKLU
 * Version: 1.0.0
 * WordPress Version: 4.6.1
 * PHP Version: 5.6
 * Author URI: https://www.visualive.jp/
 * Domain Path: /langs
 * Text Domain: va-extra-settings
 * Prefix: vaextrasettings_
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package    WordPress
 * @subpackage VA Extra Settings
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2016 KUCKLU & VisuAlive.
 *             This program is free software; you can redistribute it and/or modify
 *             it under the terms of the GNU General Public License as published by
 *             the Free Software Foundation; either version 2 of the License, or
 *             (at your option) any later version.
 *             This program is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License along
 *             with this program; if not, write to the Free Software Foundation, Inc.,
 *             51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *             It is also available through the world-wide-web at this URL:
 *             http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vaextrasettings_data = get_file_data( __FILE__, [
	'Name'             => 'Plugin Name',
	'PluginURI'        => 'Plugin URI',
	'Version'          => 'Version',
	'WordPressVersion' => 'WordPress Version',
	'PHPVersion'       => 'PHP Version',
	'Description'      => 'Description',
	'Author'           => 'Author',
	'AuthorURI'        => 'Author URI',
	'TextDomain'       => 'Text Domain',
	'DomainPath'       => 'Domain Path',
	'Prefix'           => 'Prefix',
	'Network'          => 'Network',
] );

define( 'VAEXTRASETTINGS_URL', plugin_dir_url( __FILE__ ) );
define( 'VAEXTRASETTINGS_PATH', plugin_dir_path( __FILE__ ) );
define( 'VAEXTRASETTINGS_NAME', $vaextrasettings_data['Name'] );
define( 'VAEXTRASETTINGS_VERSION', $vaextrasettings_data['Version'] );
define( 'VAEXTRASETTINGS_VERSION_WP', $vaextrasettings_data['WordPressVersion'] );
define( 'VAEXTRASETTINGS_VERSION_PHP', $vaextrasettings_data['PHPVersion'] );
define( 'VAEXTRASETTINGS_PREFIX', $vaextrasettings_data['Prefix'] );
define( 'VAEXTRASETTINGS_PREFIX_OPTION', VAEXTRASETTINGS_PREFIX . 'option_' );

unset( $vaextrasettings_data );

require_once dirname( __FILE__ ) . '/incs/trait-instance.php';
require_once dirname( __FILE__ ) . '/incs/trait-variables.php';
require_once dirname( __FILE__ ) . '/incs/class-module-admin.php';
require_once dirname( __FILE__ ) . '/incs/class-module-delete-emoji-scripts.php';
require_once dirname( __FILE__ ) . '/incs/class-module-delete-version-scripts.php';
require_once dirname( __FILE__ ) . '/incs/class-module-move-scripts.php';
require_once dirname( __FILE__ ) . '/incs/class-module-disable-rest-api.php';
require_once dirname( __FILE__ ) . '/incs/class-module-installer.php';

/**
 * Run plugin.
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'va-extra-settings', false, dirname( plugin_basename( __FILE__ ) ) . '/langs' );

	/**
	 * Plugin only works in WordPress 4.6 or later and PHP 5.6 or later.
	 */
	if ( version_compare( $GLOBALS['wp_version'], VAEXTRASETTINGS_VERSION_WP, '<' ) || version_compare( PHP_VERSION, VAEXTRASETTINGS_VERSION_PHP, '<' ) ) {
		add_action( 'admin_notices', function () {
			$message = sprintf( __( '%s requires at least WordPress version %s and PHP version %s. You are running WordPress version %s and PHP version %s. Please upgrade and try again.', 'va-extra-settings' ), VAEXTRASETTINGS_NAME, VAEXTRASETTINGS_VERSION_WP, VAEXTRASETTINGS_VERSION_PHP, $GLOBALS['wp_version'], PHP_VERSION );
			printf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
		} );

		return;
	}

	if ( is_admin() ) {
		$admin = apply_filters( 'va_extra_settings_module_admin', \VAEXTRASETTINGS\Modules\Admin::class );

		$admin::get_instance();
	}

	$delete_emoji_scripts   = apply_filters( 'va_extra_settings_module_delete_emoji_scripts', \VAEXTRASETTINGS\Modules\DeleteEmojiScripts::class );
	$move_scripts           = apply_filters( 'va_extra_settings_module_move_scripts', \VAEXTRASETTINGS\Modules\MoveScripts::class );
	$delete_scripts_version = apply_filters( 'va_extra_settings_module_delete_scripts_version', \VAEXTRASETTINGS\Modules\DeleteScriptsVersion::class );
	$disable_rest_api       = apply_filters( 'va_extra_settings_module_disable_rest_api', \VAEXTRASETTINGS\Modules\DisableRestAPI::class );

	$delete_emoji_scripts::get_instance();
	$move_scripts::get_instance();
	$delete_scripts_version::get_instance();
	$disable_rest_api::get_instance();
} );

/**
 * Uninstall.
 */
register_activation_hook( __FILE__, function () {
	register_uninstall_hook( __FILE__, [ \VAEXTRASETTINGS\Modules\Installer::class, 'uninstall' ] );
} );
if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
	register_deactivation_hook( __FILE__, [ \VAEXTRASETTINGS\Modules\Installer::class, 'uninstall' ] );
}
