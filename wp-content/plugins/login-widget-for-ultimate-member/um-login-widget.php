<?php
/*
 * Plugin Name: Login Widget for Ultimate Member
 * Plugin URI: http://www.suiteplugins.com
 * Description: A login widget for Ultimate Member.
 * Author: SuitePlugins
 * Version: 1.0.6
 * Author URI: http://www.suiteplugins.com
 * Text Domain: um-login-widget
 */


define( 'UM_LOGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'UM_LOGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'UM_LOGIN_PLUGIN', plugin_basename( __FILE__ ) );

function um_login_widget_plugin_textdomain() {
	load_plugin_textdomain( 'login-widget-for-ultimate-member', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'um_login_widget_plugin_textdomain' );


/**
 * Adds UM_Login_Widget widget.
 */
class UM_Login_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'UM_Login_Widget', // Base ID
			__( 'UM Login', 'login-widget-for-ultimate-member' ), // Name
			array( 'description' => __( 'Login form for Ultimate Member', 'login-widget-for-ultimate-member' ) ) // Args
		);
		add_action( 'wp_head', array( $this, 'add_styles' ) );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args	 Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		if ( empty( $instance['title'] ) ) {
			$instance['title'] = '';
		}
		if ( empty( $instance['before_form'] ) ) {
			$instance['before_form'] = '';
		}
		if ( empty( $instance['after_form'] ) ) {
			$instance['after_form'] = '';
		}
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<div id="um-login-widget-<?php echo $this->number; ?>" class="um-login-widget">
			<?php
			if ( is_user_logged_in() ) :
				$this->load_template( 'login-widget/login-view', $instance );
			else :
				$this->load_template( 'login-widget/login-form', $instance );
			endif;
			?>
		</div>
		<?php
		echo $after_widget;
	}

	public function add_styles() {
		?>
		<style type="text/css">
			.uml-header-info{
				float: left;
				width: 67%;
				margin-left: 4px;
			}
			.uml-header-info h3{
				margin:0 !important;
			}
			.umlw-login-avatar img{
				display: block;
				width: 100%;
				height: 100%;
			}
		</style>
		<?php
	}

	/**
	 * Load Template
	 *
	 * @param  string $tpl   Template File
	 * @param  array  $param Params
	 *
	 * @return void
	 */
	public function load_template( $tpl = '', $params = array() ) {
		global $ultimatemember;
		extract( $params, EXTR_SKIP );
		$file = UM_LOGIN_PATH . 'templates/' . $tpl . '.php';
		$theme_file = get_stylesheet_directory() . '/ultimate-member/templates/' . $tpl . '.php';

		if ( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title 	= $instance['title'];
		} else {
			$title = __( 'Login', 'login-widget-for-ultimate-member' );
		}
		if ( empty( $instance['before_form'] ) ) {
			$instance['before_form'] = '';
		}
		if ( empty( $instance['after_form'] ) ) {
			$instance['after_form'] = '';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:','login-widget-for-ultimate-member' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'before_form' ); ?>"><?php _e( 'Before Form Text:','login-widget-for-ultimate-member' ); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'before_form' ); ?>" name="<?php echo $this->get_field_name( 'before_form' ); ?>" class="widefat"><?php echo $instance['before_form']; ?></textarea>
			<span class="description"><?php _e( 'Shortcodes accepted', 'login-widget-for-ultimate-member' ); ?></span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'after_form' ); ?>"><?php _e( 'After Form Text:','login-widget-for-ultimate-member' ); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'after_form' ); ?>" name="<?php echo $this->get_field_name( 'after_form' ); ?>" class="widefat"><?php echo $instance['after_form']; ?></textarea>
			<span class="description"><?php _e( 'Shortcodes accepted', 'login-widget-for-ultimate-member' ); ?></span>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['before_form'] = ( ! empty( $new_instance['before_form'] ) ) ? wp_kses_post( $new_instance['before_form'] ) : '';
		$instance['after_form'] = ( ! empty( $new_instance['after_form'] ) ) ? wp_kses_post( $new_instance['after_form'] ) : '';
		return $instance;
	}

} // class UM_Login_Widget

// Register UM_Login_Widget widget
add_action( 'widgets_init', function() { register_widget( 'UM_Login_Widget' ); } );
/**
 * Lost password link
 *
 * @since 1.0.1
 */
add_action( 'login_form_middle', 'um_login_lost_password_link' );
function um_login_lost_password_link() {
	return '<a href="' . wp_lostpassword_url() . '" title="' . __( 'Lost Password?', 'login-widget-for-ultimate-member' ) . '">' . __( 'Lost Password?', 'login-widget-for-ultimate-member' ) . '</a>';
}

add_action( 'plugins_loaded', 'um_login_widget_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.1
 */
function um_login_widget_load_textdomain() {
	load_plugin_textdomain( 'login-widget-for-ultimate-member', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
