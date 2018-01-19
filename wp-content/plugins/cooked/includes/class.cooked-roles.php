<?php
/**
 * Roles and Capabilities
 *
 * @package     Cooked
 * @subpackage  Roles
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Cooked_Roles {

	public function add_roles() {
		add_role( 'cooked_recipe_editor', esc_html__( 'Recipe Editor', 'cooked' ), array(
			'read' => true
		) );
	}

	public static function add_caps() {
		global $wp_roles;

		if ( class_exists('WP_Roles') ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {
			$wp_roles->add_cap( 'cooked_recipe_editor', 'edit_cooked_recipes' );
			$wp_roles->add_cap( 'editor', 'edit_cooked_recipes' );
			$wp_roles->add_cap( 'administrator', 'edit_cooked_recipes' );
		}
	}

	public static function remove_caps() {

		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {
			$wp_roles->remove_cap( 'cooked_recipe_editor', 'edit_cooked_recipes' );
			$wp_roles->remove_cap( 'editor', 'edit_cooked_recipes' );
			$wp_roles->remove_cap( 'administrator', 'edit_cooked_recipes' );
		}
	}
}