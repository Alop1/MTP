<?php
/**
 * Cooked AJAX-Specific Functions
 *
 * @package     Cooked
 * @subpackage  AJAX-Specific Functions
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cooked_Ajax Class
 *
 * This class handles the Cooked Recipe Meta Box creation.
 *
 * @since 1.0.0
 */
class Cooked_Ajax {

	function __construct(){

		/**
		 * Back-End Ajax
		 */

		// Save Default Template
		add_action( 'wp_ajax_cooked_save_default', array(&$this,'save_default') );

		// Load Default Template
		add_action( 'wp_ajax_cooked_load_default', array(&$this,'load_default') );
	
	}

	public function save_default(){

		if ( !current_user_can('edit_cooked_recipes') ):
			wp_die();
		endif;

		global $_cooked_settings;

		if ( isset($_POST['default_content']) ):
			$_cooked_settings['default_content'] = wp_kses_post( $_POST['default_content'] );
			update_option( 'cooked_settings',$_cooked_settings );
		else:
			echo 'No default content provided.';
		endif;

		if ( isset($_POST['update_all']) && esc_attr( $_POST['update_all'] ) ):
			$args = array(
				'post_type' => 'cp_recipe',
				'posts_per_page' => -1,
				'post_status' => 'any'
			);
			$recipes = Cooked_Recipes::get( $args );
			if ( !empty($recipes) ):
				foreach( $recipes as $recipe ):
					$recipe_settings = Cooked_Recipes::get_settings( $recipe['id'] );
					$recipe_settings['content'] = wp_kses_post( $_POST['default_content'] );
			 		update_post_meta( $recipe['id'], '_recipe_settings', $recipe_settings );
			 		echo $recipe['id'];
			 	endforeach;
			endif;
		endif;

		wp_die();

	}

	public function save_default_new(){

		if ( !current_user_can('edit_cooked_recipes') ):
			wp_die();
		endif;

		global $_cooked_settings;

		if ( isset($_POST['default_content']) ):
			$_cooked_settings['default_content'] = esc_textarea( $_POST['default_content'] );
			update_option( 'cooked_settings',$_cooked_settings );
		else:
			echo 'No default content provided.';
		endif;

		wp_die();

	}

	public function load_default(){

		if ( !current_user_can('edit_cooked_recipes') ):
			wp_die();
		endif;

		$default_content = Cooked_Recipes::default_content();
		echo $default_content;

		wp_die();

	}
	
}