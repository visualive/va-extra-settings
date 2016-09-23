<?php
/**
 * WordPress plugin move scripts class.
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
	 * Class Emoji
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	class MoveScripts {
		use Instance, Variable;

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		public function __construct() {
			$options = $this->get_options();
			$options = isset( $options['js_in_footer'] ) ? intval( $options['js_in_footer'] ) : 0;

			if ( 1 !== $options || is_admin() ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ), - 1 );
		}

		/**
		 * Load the scripts of the header in the footer.
		 */
		public function wp_enqueue_scripts() {
			remove_action( 'wp_head', 'wp_print_scripts' );
			remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
			remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
			add_action( 'wp_footer', 'wp_print_scripts', 5 );
			add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
			add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
		}
	}
}
