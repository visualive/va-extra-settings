<?php
/**
 * WordPress plugin admin class.
 *
 * @package    WordPress
 * @subpackage VA Extra Settings
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2015 KUCKLU and VisuAlive.
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

namespace VAEXTRASETTINGS\Modules {
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Class Admin
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	class Admin {
		use Instance, Variable;

		/**
		 * Setting items.
		 *
		 * @var array
		 */
		private $settings = [ ];

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		public function __construct() {
			$this->settings = $this->get_setting_labels();

			add_action( 'admin_init', [ &$this, 'admin_init' ] );
			add_action( 'admin_menu', [ &$this, 'admin_menu' ], -10 );
		}

		/**
		 * Add admin menu.
		 */
		public function admin_menu() {
			add_options_page( __( 'Extra Settings', 'va-extra-settings' ), __( 'Extra Settings', 'va-extra-settings' ), 'manage_options', trim( VAEXTRASETTINGS_PREFIX, '_' ), [
				&$this,
				'_options_page',
			] );
		}

		/**
		 * Render setting form and register option.
		 */
		public function admin_init() {
			$settings = $this->settings;

			add_settings_section( VAEXTRASETTINGS_PREFIX . 'section', null, null, VAEXTRASETTINGS_PREFIX . 'settings' );

			foreach ( $settings as $setting ) {
				register_setting(
					VAEXTRASETTINGS_PREFIX . 'settings',
					VAEXTRASETTINGS_PREFIX_OPTION . $setting['name'],
					$setting['sanitize']
				);
				add_settings_field(
					VAEXTRASETTINGS_PREFIX . $setting['name'],
					'<label for="' . esc_attr( VAEXTRASETTINGS_PREFIX . $setting['name'] ) . '">' . esc_html( $setting['label'] ) . '</label>',
					$setting['render'],
					VAEXTRASETTINGS_PREFIX . 'settings',
					VAEXTRASETTINGS_PREFIX . 'section'
				);
			}
		}

		/**
		 * Delete emoji scripts.
		 */
		public static function _render_delete_emoji() {
			echo self::_tmp_form_checkbox( 'delete_emoji' );
		}

		/**
		 * Disable REST API.
		 */
		public static function _render_rest_api() {
			echo self::_tmp_form_checkbox( 'rest_api' );
		}

		/**
		 * Move head scripts in footer.
		 */
		public static function _render_js_in_footer() {
			echo self::_tmp_form_checkbox( 'js_in_footer' );
		}

		/**
		 * Delete version info of the styles and scripts.
		 */
		public static function _render_delete_version() {
			echo self::_tmp_form_checkbox( 'delete_version' );
		}

		/**
		 * Template the checkbox.
		 *
		 * @param string $label Option label.
		 *
		 * @return string
		 */
		public static function _tmp_form_checkbox( $label = '' ) {
			$name     = VAEXTRASETTINGS_PREFIX_OPTION . $label;
			$id       = VAEXTRASETTINGS_PREFIX . $label;
			$option   = self::get_options();
			$output[] = '<label>';
			$output[] = '<input id="' . $id . '" type="checkbox" name="' . $name . '" value="1" ' . checked( intval( $option[ $label ] ), 1, false ) . '>';
			$output[] = ' ON</label>';

			return implode( PHP_EOL, $output );
		}

		/**
		 * Create option page.
		 */
		public function _options_page() {
			?>
			<div class="wrap">
				<h1><?php _e( 'Extra Settings', 'va-extra-settings' ); ?></h1>
				<form action="options.php" method="post">
					<?php
					settings_fields( VAEXTRASETTINGS_PREFIX . 'settings' );
					do_settings_sections( VAEXTRASETTINGS_PREFIX . 'settings' );
					submit_button();
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Sanitize the zero or one.
		 *
		 * @param string $option Settings.
		 *
		 * @return string
		 */
		public static function _sanitize_zero_one( $option = '' ) {
			$option = '1' === $option ? $option : '0';

			return $option;
		}
	}
}