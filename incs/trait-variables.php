<?php
/**
 * WordPress plugin variable class.
 *
 * @package    WordPress
 * @subpackage VA Extra Settings
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2016 KUCKLU and VisuAlive.
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
	 * Class Variable
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	trait Variable {
		/**
		 * Get setting labels.
		 *
		 * @return array
		 */
		public static function get_setting_labels() {
			return apply_filters( 'va_extra_settings_admin_settings', [
				[
					'name'     => 'delete_emoji',
					'label'    => __( 'Delete emoji scripts', 'va-extra-settings' ),
					'render'   => [ Admin::class, '_render_delete_emoji' ],
					'sanitize' => [ Admin::class, '_sanitize_zero_one' ],
					'_builtin' => true,
				],
				[
					'name'     => 'rest_api',
					'label'    => __( 'Disable REST API', 'va-extra-settings' ),
					'render'   => [ Admin::class, '_render_rest_api' ],
					'sanitize' => [ Admin::class, '_sanitize_zero_one' ],
					'_builtin' => true,
				],
				[
					'name'     => 'js_in_footer',
					'label'    => __( 'Move scripts of header to footer', 'va-extra-settings' ),
					'render'   => [ Admin::class, '_render_js_in_footer' ],
					'sanitize' => [ Admin::class, '_sanitize_zero_one' ],
					'_builtin' => true,
				],
				[
					'name'     => 'delete_version',
					'label'    => __( 'Delete version info of the styles and scripts', 'va-extra-settings' ),
					'render'   => [ Admin::class, '_render_delete_version' ],
					'sanitize' => [ Admin::class, '_sanitize_zero_one' ],
					'_builtin' => true,
				],
			] );
		}

		/**
		 * Get options.
		 *
		 * @return array
		 */
		public static function get_options() {
			$labels  = self::get_setting_labels();
			$options = [];

			foreach ( $labels as $label ) {
				if ( true === $label['_builtin'] ) {
					$options[ $label['name'] ] = get_option( VAEXTRASETTINGS_PREFIX_OPTION . $label['name'], '0' );
				}
			}

			return $options;
		}
	}
}
