<?php

$absolute_path = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
$wp_load = $absolute_path[0] . 'wp-load.php';
require_once( $wp_load );

$_cooked_settings = Cooked_Settings::get();

header('Content-type: text/css');
header('Cache-control: must-revalidate');

?>/* Main Color */
.cooked-button,
.cooked-fsm .cooked-fsm-top,
.cooked-fsm .cooked-fsm-mobile-nav,
.cooked-fsm .cooked-fsm-mobile-nav a.cooked-active,
.cooked-browse-search-button,
.cooked-icon-loading,
.cooked-progress span { background:<?php echo $_cooked_settings['main_color']; ?>; }
.cooked-recipe-search .cooked-taxonomy-selected { background:<?php echo $_cooked_settings['main_color']; ?>; }
.cooked-timer-obj,
.cooked-fsm a { color:<?php echo $_cooked_settings['main_color']; ?>; }

/* Main Color Darker */
.cooked-button:hover,
.cooked-recipe-search .cooked-taxonomy-selected:hover,
.cooked-browse-search-button:hover { background:<?php echo $_cooked_settings['main_color_hover']; ?>; }
