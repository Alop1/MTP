<?php
/**
 * Widgets
 *
 * @package     Cooked
 * @subpackage  Widgets
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'widgets/init.php';

class Cooked_Widgets {

	public function __construct() {
		add_action( 'widgets_init', array(&$this, 'register_widgets'), 10, 1 );
	}

	public function register_widgets() {
		$widgets = apply_filters( 'cooked_widgets', array(
			'Cooked_Widget_Nutrition',
			'Cooked_Widget_Search',
		));
		if ( !empty($widgets) ):
			foreach( $widgets as $widget ):
				register_widget( $widget );
			endforeach;
		endif;
	}

}
