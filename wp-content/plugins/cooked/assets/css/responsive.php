<?php

$absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $absolute_path[0] . 'wp-load.php';
require_once($wp_load);

$_cooked_settings = Cooked_Settings::get();

header('Content-type: text/css');
header('Cache-control: must-revalidate');

?>@media screen and ( max-width: <?php echo esc_html( $_cooked_settings['responsive_breakpoint_1'] ); ?>px ) {

	#cooked-timers-wrap { width:90%; margin:0 -45% 0 0; }

	.cooked-recipe-grid { margin:3% -1.5%; width: 103%; }
	.cooked-recipe-grid.cooked-columns-3 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-4 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-5 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-6 .cooked-recipe { width: 50%; margin: 0 0 3%; padding: 0 1.5%; }

}

@media screen and ( max-width: <?php echo esc_html( $_cooked_settings['responsive_breakpoint_2'] ); ?>px ) {

	.cooked-recipe-grid { margin:5% -2.5%; width:105%; }
	.cooked-recipe-grid.cooked-columns-2 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-3 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-4 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-5 .cooked-recipe,
	.cooked-recipe-grid.cooked-columns-6 .cooked-recipe
	.cooked-recipe-grid .cooked-recipe { width:100%; margin:0 0 5%; padding:0 2.5%; }

	.cooked-recipe-info .cooked-left, .cooked-recipe-info .cooked-right { float:none; display:block; text-align:center; }
	.cooked-recipe-info > section.cooked-right > span, .cooked-recipe-info > section.cooked-left > span { margin:0.5rem 1rem 1rem; }
	.cooked-recipe-info > section.cooked-left > span:last-child, .cooked-recipe-info > span:last-child { margin-right:1rem; }
	.cooked-recipe-info > section.cooked-right > span:first-child { margin-left:1rem; }

	.cooked-recipe-search .cooked-fields-wrap { padding:0; display:block; }
	.cooked-recipe-search .cooked-fields-wrap > .cooked-browse-search { width:100%; border-right:1px solid rgba(0,0,0,.15); margin:2% 0; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-1-search-fields .cooked-browse-select-block { width:100%; left:0; }
	.cooked-recipe-search .cooked-fields-wrap > .cooked-field-wrap-select { display:block; width:100%; }
	.cooked-recipe-search .cooked-sortby-wrap { display:block; position:relative; width:68%; right:auto; top:auto; float:left; margin:0; }
	.cooked-recipe-search .cooked-sortby-wrap > select { position:absolute; width:100%; border:1px solid rgba(0,0,0,.15); }
	.cooked-recipe-search .cooked-browse-search-button { width:30%; right:auto; position:relative; display:block; float:right; }
	.cooked-recipe-search .cooked-browse-select-block { top:3rem; left:0; max-height:16rem; overflow:auto; transform:translate3d(0,-1px,0); }
	.cooked-recipe-search .cooked-fields-wrap.cooked-1-search-fields .cooked-browse-select-block { width:100%; padding:1.5rem; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-2-search-fields .cooked-browse-select-block { width:100%; padding:1.5rem; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-3-search-fields .cooked-browse-select-block { width:100%; padding:1.5rem; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-4-search-fields .cooked-browse-select-block { width:100%; padding:1.5rem; }
	.cooked-recipe-search .cooked-browse-select-block .cooked-tax-column { float:none; padding:0 0 1.5rem 0; }
	.cooked-recipe-search .cooked-browse-select-block .cooked-tax-column:last-child { padding:0; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-1-search-fields .cooked-browse-select-block .cooked-tax-column { width:100%; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-2-search-fields .cooked-browse-select-block .cooked-tax-column { width:100%; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-3-search-fields .cooked-browse-select-block .cooked-tax-column { width:100%; }
	.cooked-recipe-search .cooked-fields-wrap.cooked-4-search-fields .cooked-browse-select-block .cooked-tax-column { width:100%; }

	#cooked-timers-wrap { width:20rem; right:50%; margin:0 -10rem 0 0; transform:translate3d(0,11.2em,0); }
	#cooked-timers-wrap.cooked-multiples { margin:0; right:0; border-radius:10px 0 0 0; width:20rem; }
	#cooked-timers-wrap .cooked-timer-block { padding-left:3.25rem; }
	#cooked-timers-wrap .cooked-timer-block.cooked-visible { padding-top:1rem; line-height:1.5rem; padding-left:3.25rem; }
	#cooked-timers-wrap .cooked-timer-block .cooked-timer-step { font-size:0.9rem; }
	#cooked-timers-wrap .cooked-timer-block .cooked-timer-desc { font-size:1rem; padding:0; }
	#cooked-timers-wrap .cooked-timer-block .cooked-timer-obj { top:auto; right:auto; width:auto; font-size:1.5rem; line-height:2rem; }
	#cooked-timers-wrap .cooked-timer-block .cooked-timer-obj > i.cooked-icon { font-size:1.5rem; width:1.3rem; margin-right:0.5rem; }
	#cooked-timers-wrap .cooked-timer-block i.cooked-icon-times { line-height:1rem; font-size:1rem; top:1.4rem; left:1.2rem; }

	body.cooked-fsm-active #cooked-timers-wrap { bottom:4rem; }

	.cooked-fsm .cooked-fsm-mobile-nav { display:block; }
	.cooked-fsm .cooked-fsm-ingredients,
	.cooked-fsm .cooked-fsm-directions { width:100%; display:none; left:0; background:#fff; }
	.cooked-fsm .cooked-fsm-ingredients.cooked-active,
	.cooked-fsm .cooked-fsm-directions.cooked-active { display:block; }

}

@media screen and ( max-width: <?php echo esc_html( $_cooked_settings['responsive_breakpoint_3'] ); ?>px ) {

	.cooked-fsm .cooked-fsm-directions p { font-size:1rem; }
	.cooked-fsm .cooked-recipe-directions .cooked-heading { font-size:1.25rem; }

	#cooked-timers-wrap { width:100%; right:0; margin:0; border-radius:0; }

	.cooked-recipe-search .cooked-fields-wrap > .cooked-browse-search { margin:3% 0; }
	.cooked-recipe-search .cooked-sortby-wrap { width:67%; }

}
