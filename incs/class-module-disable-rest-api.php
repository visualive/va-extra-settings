<?php
/**
 * WordPress plugin emoji class.
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
	 * Disable REST API.
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	class DisableRestAPI {
		use Instance, Variable;

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		public function __construct() {
			$options = $this->get_options();
			$options = isset( $options['rest_api'] ) ? intval( $options['rest_api'] ) : 0;

			if ( 1 === $options && ! is_admin() ) {
				remove_action( 'wp_head', 'wp_oembed_add_host_js' );
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
				remove_action( 'wp_head', 'rest_output_link_wp_head' );
				remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
				remove_action( 'template_redirect', 'rest_output_link_header', 11 );
				add_filter( 'rest_enabled', '__return_false' );
				add_action( 'rest_api_init', [ &$this, 'rest_api_init' ], -1 );
			}
		}

		/**
		 * The REST API is 404 Error.
		 */
		public function rest_api_init() {
			global $wp_query;

			$template = ! empty( get_404_template() ) ? get_404_template() : get_index_template();

			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();

			require_once( $template );

			exit;
		}
	}
}
