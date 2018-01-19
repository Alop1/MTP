<?php

/*

Plugin Name: 	Cooked - Recipes for WordPress
Plugin URI: 	https://cooked.pro
Description: 	The absolute best way to create & display recipes on WordPress-powered sites.
Author: 		Boxy Studio
Author URI: 	https://boxystudio.com
Version: 		1.2.0
Text Domain: 	cooked
Domain Path: 	languages
License:     	GPL2

Cooked is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Cooked is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Cooked. If not, see http://www.gnu.org/licenses/.

*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'COOKED_VERSION', '1.2.0' );
define( 'COOKED_VUE', '2.4.2' );

define( 'COOKED_DEV', false );

if ( ! class_exists( 'Cooked_Plugin' ) ) :

/**
 * Cooked_Plugin Class.
 *
 * @since 1.0.0
 */
final class Cooked_Plugin {

	/**
	 * @var Cooked_Plugin
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Cooked Roles Object.
	 *
	 * @var object|Cooked_Roles
	 * @since 1.0.0
	 */
	public $roles;

	/**
	 * Main Cooked_Plugin Instance.
	 *
	 * Insures that only one instance of Cooked_Plugin exists in memory at any one
	 * time. Also prevents needing to define globals everywhere.
	 *
	 * @since 1.0.0
	 * @static
	 * @staticvar array $instance
	 * @uses Cooked_Plugin::setup_constants() Setup the constants needed.
	 * @uses Cooked_Plugin::includes() Include the required files.
	 * @uses Cooked_Plugin::load_textdomain() load the language files.
	 * @see Cooked()
	 * @return object|Cooked_Plugin
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Cooked_Plugin ) ) {

			self::$instance = new Cooked_Plugin;
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			self::$instance->roles = new Cooked_Roles();
			self::$instance->admin_menus = new Cooked_Admin_Menus();

			if (is_admin()):
				self::$instance->admin_enqueues = new Cooked_Admin_Enqueues();
			else:
				self::$instance->enqueues = new Cooked_Enqueues();
			endif;

			self::$instance->admin_settings = new Cooked_Settings();
			self::$instance->post_types = new Cooked_Post_Types();
			self::$instance->recipe_meta = new Cooked_Recipe_Meta();
			self::$instance->recipe_meta = new Cooked_Measurements();
			self::$instance->users = new Cooked_Users();
			self::$instance->recipes = new Cooked_Recipes();
			self::$instance->shortcodes = new Cooked_Shortcodes();
			self::$instance->ajax = new Cooked_Ajax();
			self::$instance->functions = new Cooked_Functions();
			self::$instance->widget = new Cooked_Widgets();

			self::$instance->module_setup();

		}

		return self::$instance;

	}

	private function module_setup(){

		// Look for Cooked Modules
		$modules = file_exists( COOKED_DIR . 'modules' ) ? scandir( COOKED_DIR . 'modules' ) : false;
		if ($modules):
			foreach($modules as $module_name):
				if ($module_name !== '.' && $module_name !== '..'):
					if ( file_exists( COOKED_DIR . 'modules/'.$module_name.'/'.$module_name.'.php' ) ):
						require_once COOKED_DIR . 'modules/'.$module_name.'/'.$module_name.'.php';
					endif;
				endif;
			endforeach;
		endif;

		// Look for Cooked Modules in Parent Theme
		$parent_theme_folder = trailingslashit( get_template_directory() );
		$modules = file_exists( $parent_theme_folder . 'cooked-modules' ) ? scandir( $parent_theme_folder . 'cooked-modules' ) : false;
		if ($modules):
			foreach($modules as $module_name):
				if ($module_name !== '.' && $module_name !== '..'):
					if ( file_exists( $parent_theme_folder . 'cooked-modules/'.$module_name.'/'.$module_name.'.php' ) ):
						require_once $parent_theme_folder . 'cooked-modules/'.$module_name.'/'.$module_name.'.php';
					endif;
				endif;
			endforeach;
		endif;

		// Look for Cooked Modules in Child Theme (if one is active)
		$child_theme_folder = trailingslashit( get_stylesheet_directory() );
		if ($child_theme_folder != $parent_theme_folder):
			$modules = file_exists( $child_theme_folder . 'cooked-modules' ) ? scandir( $child_theme_folder . 'cooked-modules' ) : false;
			if ($modules):
				foreach($modules as $module_name):
					if ($module_name !== '.' && $module_name !== '..'):
						if ( file_exists( $child_theme_folder . 'cooked-modules/'.$module_name.'/'.$module_name.'.php' ) ):
							require_once $child_theme_folder . 'cooked-modules/'.$module_name.'/'.$module_name.'.php';
						endif;
					endif;
				endforeach;
			endif;
		endif;

	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Nope, can't do that.
		return false;
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Nope, can't do that either.
		return false;
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {

		// Plugin Folder Path.
		if ( ! defined( 'COOKED_DIR' ) ) {
			define( 'COOKED_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'COOKED_PLUGIN_FILE' ) ) {
 			define( 'COOKED_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Folder URL.
		if ( ! defined( 'COOKED_URL' ) ) {
			define( 'COOKED_URL', plugin_dir_url( __FILE__ ) );
		}

		// WordPress Ajax URL.
		if ( ! defined( 'COOKED_AJAX_URL' ) ) {
			define( 'COOKED_AJAX_URL', admin_url('admin-ajax.php') );
		}

		// Make sure CAL_GREGORIAN is defined.
		if ( ! defined( 'CAL_GREGORIAN' ) ) {
			define( 'CAL_GREGORIAN', 1 );
		}

		// Time Format
		if ( ! defined( 'COOKED_TIME_FORMAT' ) ) {
			define( 'COOKED_TIME_FORMAT', get_option('time_format','g:ia') );
		}

		// Date Format
		if ( ! defined( 'COOKED_DATE_FORMAT' ) ) {
			define( 'COOKED_DATE_FORMAT', get_option('date_format','F j, Y') );
		}

	}

	public function activation(){
		flush_rewrite_rules();
	}

	public function deactivation(){
		flush_rewrite_rules();
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {

		require_once COOKED_DIR . 'includes/class.cooked-users.php';
		require_once COOKED_DIR . 'includes/class.cooked-roles.php';
		require_once COOKED_DIR . 'includes/class.cooked-taxonomies.php';
		require_once COOKED_DIR . 'includes/class.cooked-post-types.php';
		require_once COOKED_DIR . 'includes/class.cooked-seo.php';
		require_once COOKED_DIR . 'includes/class.cooked-recipes.php';
		require_once COOKED_DIR . 'includes/class.cooked-recipe-meta.php';
		require_once COOKED_DIR . 'includes/class.cooked-shortcodes.php';
		require_once COOKED_DIR . 'includes/class.cooked-measurements.php';
		require_once COOKED_DIR . 'includes/class.cooked-admin-enqueues.php';
		require_once COOKED_DIR . 'includes/class.cooked-enqueues.php';
		require_once COOKED_DIR . 'includes/class.cooked-admin-menus.php';
		require_once COOKED_DIR . 'includes/class.cooked-settings.php';
		require_once COOKED_DIR . 'includes/class.cooked-ajax.php';
		require_once COOKED_DIR . 'includes/class.cooked-functions.php';
		require_once COOKED_DIR . 'includes/class.cooked-widgets.php';

	}

	/**
	 * Loads the plugin language files.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_textdomain() {

		/*
		 * When translating Cooked, be sure to move your language file into the proper location:
		 *
		 * - wp-content/languages/plugins/cooked
		 *
		 * If you do not move custom language files here, they will be lost when updating Cooked. Boxy Studio
		 * recommends Loco Translate for easy translations: https://boxystudio.ticksy.com/article/3235/
		 */

		// Set filter for plugin's languages directory.
		$cooked_lang_dir = COOKED_DIR . 'languages/';
		$cooked_lang_dir = apply_filters( 'cooked_languages_directory', $cooked_lang_dir );

		// Traditional WordPress plugin locale filter.
		$locale = apply_filters( 'plugin_locale',  get_locale(), 'cooked' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'cooked', $locale );

		// Look in wp-content/languages/plugins/cooked
		$lang_file_ext = WP_LANG_DIR . '/plugins/cooked/' . $mofile;

		if ( file_exists( $lang_file_ext ) ) {

			// Load the externally located language files.
			load_textdomain( 'cooked', $lang_file_ext );

		} else {

			// Load the default language files.
			load_plugin_textdomain( 'cooked', false, $cooked_lang_dir );

		}

	}

}

endif; // End if class_exists check.


/**
 * The main function for that returns Cooked_Plugin
 *
 * The main function responsible for returning the Cooked_Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $cooked = Cooked(); ?>
 *
 * @since 1.0.0
 * @return object|Cooked_Plugin
 */
function Cooked() {
	return Cooked_Plugin::instance();
}

// Let's get cooking!
$CookedPlugin = Cooked();
