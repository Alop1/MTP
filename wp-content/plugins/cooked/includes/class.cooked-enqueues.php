<?php
/**
 * Admin Enqueues
 *
 * @package     Cooked
 * @subpackage  Enqueues
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
class Cooked_Enqueues {

	function __construct() {
		add_action( 'wp_enqueue_scripts', array(&$this, 'enqueues'), 10, 1 );
		add_action( 'wp_footer', array(&$this, 'footer_enqueues') );
	}

	public function enqueues( $hook ) {

		global $_cooked_settings;

		$cooked_js_vars = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'i18n_timer' => esc_html__( 'Timer','cooked' ),
		);

		$min = ( COOKED_DEV ? '' : '.min' );

		wp_enqueue_style( 'cooked-essentials', COOKED_URL . 'assets/admin/css/essentials'.$min.'.css', array(), COOKED_VERSION );
		wp_enqueue_style( 'cooked-icons', COOKED_URL . 'assets/css/icons'.$min.'.css', array(), COOKED_VERSION );
		wp_enqueue_style( 'cooked-styling', COOKED_URL . 'assets/css/style'.$min.'.css', array(), COOKED_VERSION );
		wp_enqueue_style( 'cooked-colors', COOKED_URL . 'assets/css/colors.php', array(), COOKED_VERSION );
		wp_enqueue_style( 'cooked-responsive', COOKED_URL . 'assets/css/responsive.php', array(), COOKED_VERSION );

		if ( isset( $_cooked_settings['garnish'][0] ) ):
			wp_enqueue_style( 'cooked-garnish', COOKED_URL . 'assets/css/garnish'.$min.'.css', array(), COOKED_VERSION );
		endif;

		wp_register_style( 'cooked-fotorama-style', '//cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css', array(), '4.6.4' );
		wp_register_script( 'cooked-fotorama-js', '//cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js', array('jquery'), '4.6.4', true );
		wp_register_script( 'cooked-masonry', COOKED_URL . 'assets/js/masonry/masonry.pkgd.min.js', array('jquery'), '4.2.0', true );
		wp_register_script( 'cooked-imagesLoaded', '//npmcdn.com/imagesloaded@4.1.3/imagesloaded.pkgd.min.js', array('jquery'), '4.1.3', true );
		if ( !defined('QODE_ROOT') ): // Compatibility with the Bridge Theme
			wp_register_script( 'cooked-appear-js', COOKED_URL . 'assets/js/appear/jquery.appear.min.js', array('jquery'), COOKED_VERSION, true );
		endif;
		wp_register_script( 'cooked-timer', COOKED_URL . 'assets/js/timer/jquery.simple.timer.min.js', array('jquery'), COOKED_VERSION, true );

		wp_register_script( 'cooked-functions-js', COOKED_URL . 'assets/js/cooked-functions'.$min.'.js', array('jquery'), COOKED_VERSION, true );
		wp_localize_script( 'cooked-functions-js', 'cooked_js_vars', $cooked_js_vars );

	}

	public function footer_enqueues() {
		wp_enqueue_script('cooked-functions-js');
	}

}
