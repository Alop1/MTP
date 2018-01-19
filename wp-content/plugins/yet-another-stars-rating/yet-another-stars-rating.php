<?php
/**
 * Plugin Name:  Yet Another Stars Rating
 * Plugin URI: http://wordpress.org/plugins/yet-another-stars-rating/
 * Description: Yet Another Stars Rating turn your WordPress into a complete review website.
 * Version: 1.4.9
 * Author: Dario Curvino
 * Author URI: https://yetanotherstarsrating.com/
 * Text Domain: yet-another-stars-rating
 * Domain Path: languages
 * License: GPL2
 */

/*

Copyright 2015 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if ( ! defined( 'ABSPATH' ) ) exit('You\'re not allowed to see this page'); // Exit if accessed directly

define('YASR_VERSION_NUM', '1.4.9');

//Plugin relative path
define( "YASR_ABSOLUTE_PATH", dirname(__FILE__) );

//Plugin RELATIVE PATH without slashes (just the directory's name)
define( "YASR_RELATIVE_PATH", dirname( plugin_basename(__FILE__) ) );

//Plugin language directory: here I've to use relative path
//because load_plugin_textdomain wants relative and not absolute path
define( "YASR_LANG_DIR", YASR_RELATIVE_PATH . '/languages/' );

//Js directory absolute
define ("YASR_JS_DIR",  plugins_url() .'/'. YASR_RELATIVE_PATH . '/js/');

//CSS directory absolute
define ("YASR_CSS_DIR", plugins_url() .'/'. YASR_RELATIVE_PATH . '/css/');

//IMG directory absolute
define ("YASR_IMG_DIR", plugins_url() .'/'. YASR_RELATIVE_PATH . '/img/');



/****** Getting options ******/

//Get general options
$yasr_stored_options = get_option( 'yasr_general_options' );

global $yasr_stored_options;

define ("YASR_AUTO_INSERT_ENABLED", $yasr_stored_options['auto_insert_enabled']);

if ( YASR_AUTO_INSERT_ENABLED == 1 ) {

	define ("YASR_AUTO_INSERT_WHAT", $yasr_stored_options['auto_insert_what']);
	define ("YASR_AUTO_INSERT_WHERE", $yasr_stored_options['auto_insert_where']);
	define ("YASR_AUTO_INSERT_SIZE", $yasr_stored_options['auto_insert_size']);
	define ("YASR_AUTO_INSERT_EXCLUDE_PAGES", $yasr_stored_options['auto_insert_exclude_pages']);
	define ("YASR_AUTO_INSERT_CUSTOM_POST_ONLY", $yasr_stored_options['auto_insert_custom_post_only']);

}

//Avoid undefined index
else {

	define ("YASR_AUTO_INSERT_WHAT", NULL);
	define ("YASR_AUTO_INSERT_WHERE", NULL);
	define ("YASR_AUTO_INSERT_SIZE", NULL);
	define ("YASR_AUTO_INSERT_EXCLUDE_PAGES", NULL);
	define ("YASR_AUTO_INSERT_CUSTOM_POST_ONLY", NULL);

}

define ("YASR_SHOW_OVERALL_IN_LOOP", $yasr_stored_options['show_overall_in_loop']);
define ("YASR_SHOW_VISITOR_VOTES_IN_LOOP", $yasr_stored_options['show_visitor_votes_in_loop']);
define ("YASR_TEXT_BEFORE_STARS", $yasr_stored_options['text_before_stars']);

if ( YASR_TEXT_BEFORE_STARS == 1 ) {

	define ("YASR_TEXT_BEFORE_OVERALL", $yasr_stored_options['text_before_overall']);
	define ("YASR_TEXT_BEFORE_VISITOR_RATING", $yasr_stored_options['text_before_visitor_rating']);
	define ("YASR_TEXT_AFTER_VISITOR_RATING", $yasr_stored_options['text_after_visitor_rating']);
	define ("YASR_CUSTOM_TEXT_USER_VOTED", $yasr_stored_options['custom_text_user_voted']);

}

define ("YASR_VISITORS_STATS", $yasr_stored_options['visitors_stats']);
define ("YASR_ALLOWED_USER", $yasr_stored_options['allowed_user']);
define ("YASR_SNIPPET", $yasr_stored_options['snippet']);
define ("YASR_ITEMTYPE", $yasr_stored_options['snippet_itemtype']);

if (isset($yasr_stored_options['blogposting_organization_name'])) {

	define ("YASR_BLOGPOSTING_ORGANIZATION_NAME", $yasr_stored_options['blogposting_organization_name']);

}

else {

	define ("YASR_BLOGPOSTING_ORGANIZATION_NAME", '');

}


if (isset($yasr_stored_options['blogposting_organization_logo'])) {

	if (filter_var($yasr_stored_options['blogposting_organization_logo'], FILTER_VALIDATE_URL) !== FALSE) {

		define ("YASR_BLOGPOSTING_ORGANIZATION_LOGO", $yasr_stored_options['blogposting_organization_logo']);

	}

}

define ("YASR_METABOX_OVERALL_RATING", $yasr_stored_options['metabox_overall_rating']);


// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'YASR_EDD_SL_STORE_URL', 'http://yetanotherstarsrating.com' );


//Get stored style options
$style_options = get_option ('yasr_style_options');


if ($style_options) {

	if(isset($style_options['textarea'])) {

		define ("YASR_CUSTOM_CSS_RULES", $style_options['textarea']);

	}

	else {

		define ("YASR_CUSTOM_CSS_RULES", NULL);

	}

	if(isset($style_options['scheme_color_multiset'])) {

		define ("YASR_SCHEME_COLOR", $style_options['scheme_color_multiset']);

	}

	else {

		define ("YASR_SCHEME_COLOR", NULL);

	}

	if(isset($style_options['stars_set_free'])) {

		define ("YASR_STARS_SET", $style_options['stars_set_free']);

	}

	else {

		define ("YASR_STARS_SET", NULL);

	}


}

else {

	define ("YASR_CUSTOM_CSS_RULES", NULL);
	define ("YASR_SCHEME_COLOR", NULL);
	define ("YASR_STARS_SET", NULL);


}

//Multi set options
$multi_set_options = get_option ('yasr_multiset_options');


if($multi_set_options) {

	if(isset($multi_set_options['show_average'])) {

		define ("YASR_MULTI_SHOW_AVERAGE", $multi_set_options['show_average']);

	}

	else {

		define ("YASR_MULTI_SHOW_AVERAGE", 'yes');

	}

}


/****** End Getting options ******/



// Include function file
require (YASR_ABSOLUTE_PATH . '/lib/yasr-functions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-admin-actions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-settings-functions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-db-functions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-ajax-functions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-shortcode-functions.php');

require (YASR_ABSOLUTE_PATH . '/lib/yasr-widgets.php');

global $wpdb;

define ("YASR_VOTES_TABLE", $wpdb->prefix . 'yasr_votes');

define ("YASR_MULTI_SET_NAME_TABLE", $wpdb->prefix . 'yasr_multi_set');

define ("YASR_MULTI_SET_FIELDS_TABLE", $wpdb->prefix . 'yasr_multi_set_fields');

define ("YASR_MULTI_SET_VALUES_TABLE", $wpdb->prefix . 'yasr_multi_values');

define ("YASR_LOG_TABLE", $wpdb->prefix . 'yasr_log');

define ("YASR_LOADER_IMAGE", YASR_IMG_DIR . "/loader.gif");


/****** backward compatibility functions ******/

$yasr_version_installed = get_option('yasr-version');

global $yasr_version_installed;


//Run this only on plugin activation (doesn't work on update)
register_activation_hook( __FILE__, 'yasr_on_activation' );

function yasr_on_activation () {

	global $yasr_version_installed;

	//If this is a fresh new installation
	if (!$yasr_version_installed ) {

		yasr_install();

	}

}



add_action('plugins_loaded', 'yasr_update_version' );

function yasr_update_version() {

	//do only in admin

	if (is_admin()) {

		global $wpdb;

		global $yasr_version_installed;

		global $yasr_stored_options;


		//Remove end March 2017
		//This is a very important update: yasr_votes table will not be used anymore, using post meta instead
		//

		if ($yasr_version_installed && $yasr_version_installed < '1.3.5') {

			$overall_rating_array=$wpdb->get_results("SELECT post_id, overall_rating, review_type
			FROM " . YASR_VOTES_TABLE ."
			WHERE overall_rating > 0
			OR review_type IS NOT NULL
			ORDER BY post_id ASC");

			foreach ($overall_rating_array as $rating) {

				//add post meta only if overall rating is > 0
				if($rating->overall_rating > 0) {

					add_post_meta($rating->post_id, 'yasr_overall_rating', $rating->overall_rating, TRUE);

				}

				//add review type only if is not empty
				if($rating->review_type != '') {

					add_post_meta($rating->post_id, 'yasr_review_type', $rating->review_type, TRUE);

				}

			}

		}


		//Remove end Sept 2016
		if ($yasr_version_installed && $yasr_version_installed < '1.2.2') {

			$yasr_stored_options['blogposting_organization_name'] = get_bloginfo('name');
			$yasr_stored_options['blogposting_organization_logo'] = get_site_icon_url();

			update_option("yasr_general_options", $yasr_stored_options);

		}


		/****** End backward compatibility functions ******/


		if ($yasr_version_installed != YASR_VERSION_NUM) {

		    update_option('yasr-version', YASR_VERSION_NUM);

		}

	}

}


//this add a link under the plugin name, must be in the main plugin file
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'yasr_add_links_below_plugin_name' );

	function yasr_add_links_below_plugin_name ( $links ) {

		$settings_link = '<a href="' . admin_url( 'options-general.php?page=yasr_settings_page' ) . '">General Settings</a>';

		//array_unshit adds to the begin of array
		array_unshift ($links, $settings_link);

		return $links;

	}

//this add a link under the plugin description
add_filter( 'plugin_row_meta', 'yasr_plugin_row_meta', 10, 2 );

	function yasr_plugin_row_meta( $links, $file ) {

		$plugin = plugin_basename(__FILE__);

		// create link
		if ( $file == $plugin ) {

			$links[] = '<a href="https://yetanotherstarsrating.com/extensions/">Buy Yasr Extensions</a>';

		}

		return $links;
	}

?>
