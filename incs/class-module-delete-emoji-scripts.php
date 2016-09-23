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
	 * Class DeleteEmojiScripts
	 *
	 * @package VAEXTRASETTINGS\Modules
	 */
	class DeleteEmojiScripts {
		use Instance, Variable;

		/**
		 * This hook is called once any activated plugins have been loaded.
		 */
		public function __construct() {
			$options = $this->get_options();
			$options = isset( $options['delete_emoji'] ) ? intval( $options['delete_emoji'] ) : 0;

			if ( 1 === $options ) {
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );
				remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
				remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
				remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
				add_filter( 'tiny_mce_plugins', [ &$this, 'disable_emojis_tinymce' ] );
			}
		}

		/**
		 * Filter function used to remove the tinymce emoji plugin.
		 *
		 * @param array $plugins TinyMCE plugins.
		 *
		 * @return array
		 */
		public function disable_emojis_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, [ 'wpemoji' ] );
			} else {
				return [];
			}
		}
	}
}
