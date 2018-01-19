<?php
/**
 * Admin Enqueues
 *
 * @package     Cooked
 * @subpackage  Admin Enqueues
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cooked_Post_Types Class
 *
 * This class handles the post type creation.
 *
 * @since 1.0.0
 */
class Cooked_Admin_Enqueues {
	
	public static $admin_colors;

	function __construct() {

		add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueues'), 10, 1 );
	
	}
	
	public function admin_enqueues( $hook ) {
		
		global $post,$typenow,$pagenow;
		
		$cooked_admin_hooks = array(
			'index.php',
			'post-new.php',
			'post.php',
			'edit.php',
			'cooked_settings',
			'cooked_welcome',
			'cooked_pro'
		);

		$min = ( COOKED_DEV ? '' : '.min' );

		// Required Assets for Entire Admin (icons, etc.)
		wp_enqueue_style( 'cooked-essentials', COOKED_URL . 'assets/admin/css/essentials'.$min.'.css', array(), COOKED_VERSION );
		wp_enqueue_style( 'cooked-icons', COOKED_URL . 'assets/css/icons'.$min.'.css', array(), COOKED_VERSION );

		$load_cooked_admin_assets = false;

		foreach( $cooked_admin_hooks as $hook_slug ):
			if ( strpos( $hook,$hook_slug ) || $hook_slug == $hook ):
				$load_cooked_admin_assets = true;
			endif;
		endforeach;

	    if ( $load_cooked_admin_assets ) {
		    
		    if (function_exists('get_current_screen')):

		    	$screen = get_current_screen();
		    	$post_type = $screen->post_type;

				if ($hook != 'post-new.php' && $hook != 'post.php' && $hook != 'index.php' && $hook != 'edit.php' || $hook === 'post-new.php' && $post_type === 'cp_recipe' || $hook === 'post.php' && $post_type === 'cp_recipe' || $hook === 'edit.php' && $post_type === 'cp_recipe' || $hook === 'index.php'):
					$enqueue = true;
					add_thickbox();
				else:
					$enqueue = false;
				endif;
			else:
				$enqueue = true;
			endif;
			
			if ($enqueue):
			
				wp_enqueue_style( 'cooked-switchery', COOKED_URL . 'assets/admin/css/switchery/switchery.min.css', array(), COOKED_VERSION );
	    		wp_enqueue_script( 'cooked-switchery', COOKED_URL . 'assets/admin/js/switchery/switchery.min.js', array(), COOKED_VERSION, true );
	    		wp_enqueue_script( 'cooked-vue', COOKED_URL . 'assets/admin/js/vue/vue'.$min.'.js', array(), null, false );
	    		
		        $cooked_js_vars = array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'cooked_plugin_url' => COOKED_URL,
					'time_format' => get_option('time_format','g:ia'),
					'i18n_image_title' => esc_html__( 'Add Image', 'cooked' ),
					'i18n_image_change' => esc_html__( 'Change Image', 'cooked' ),
	                'i18n_image_button' => esc_html__( 'Use this Image', 'cooked' ),
	                'i18n_gallery_image_title' => esc_html__( 'Add to Gallery', 'cooked' ),
	                'i18n_edit_image_title' => esc_html__( 'Edit Gallery Item', 'cooked' ),
	                'i18n_edit_image_button' => esc_html__( 'Update Gallery Item', 'cooked' ),
	                'i18n_saved' => esc_html__('Saved','cooked'),
	                'i18n_applied' => esc_html__('Applied','cooked'),
					'i18n_confirm_save_default_all' => esc_html__('Are you sure you want to apply this new template to all of your recipes?','cooked'),
					'i18n_confirm_load_default' => esc_html__('Are you sure you want to reset this recipe template to the Cooked plugin default?','cooked'),
				);
				
				// Cooked Admin Style Assets
		    	wp_register_script( 'cooked-functions', COOKED_URL . 'assets/admin/js/cooked-functions'.$min.'.js', array('jquery'), COOKED_VERSION, true );
				wp_enqueue_style( 'cooked-admin', COOKED_URL . 'assets/admin/css/style'.$min.'.css', array(), COOKED_VERSION );
				wp_enqueue_style( 'wp-color-picker' );
		          
		        // Cooked Admin Script Assets
		        wp_enqueue_media();
	            wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-resizable' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-slider' );
				
				// Tooltipster
				wp_enqueue_script('cooked-tooltipster', COOKED_URL . 'assets/admin/js/tooltipster/jquery.tooltipster.min.js', array('jquery'), COOKED_VERSION, true );
				wp_enqueue_style('cooked-tooltipster-core', COOKED_URL . 'assets/admin/css/tooltipster/tooltipster.min.css', array(), COOKED_VERSION, 'screen' );
				wp_enqueue_style('cooked-tooltipster-theme', COOKED_URL . 'assets/admin/css/tooltipster/themes/tooltipster-light.min.css', array(), COOKED_VERSION, 'screen' );
				
				// Cooked Admin Script
				wp_localize_script('cooked-functions', 'cooked_js_vars', $cooked_js_vars );
				wp_enqueue_script('cooked-functions');

			endif;
	
	    }
		
	}

}