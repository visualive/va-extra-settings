<?php
/**
 * WordPress plugin delete scripts version class.
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
	 * Delete version of the scripts
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	class DeleteScriptsVersion {
		use Instance, Variable;

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		public function __construct() {
			$options = $this->get_options();
			$options = isset( $options['delete_version'] ) ? intval( $options['delete_version'] ) : 0;

			if ( 1 !== $options || is_admin() ) {
				return;
			}

			add_filter( 'script_loader_src', [ &$this, 'delete_src_version' ], 99 );
			add_filter( 'style_loader_src', [ &$this, 'delete_src_version' ], 99 );
		}

		/**
		 * Delete version pf the scripts and styles.
		 *
		 * @param string $src The source URL of the enqueued script/style.
		 *
		 * @return string
		 */
		public static function delete_src_version( $src ) {
			if ( strpos( $src, 'ver=' ) ) {
				$src = remove_query_arg( 'ver', $src );
			}

			return $src;
		}
	}
}
