<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

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

if ( !current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
}

$ajax_nonce_hide_ask_rating = wp_create_nonce( "yasr_nonce_hide_ask_rating" );

yasr_include_fb_sdk ();

$n_multi_set = NULL; //Evoid undefined variable when printed outside multiset tab


?>

	<div class="wrap">

        <h2>Yet Another Stars Rating: <?php _e("Settings", 'yet-another-stars-rating'); ?></h2>

        <?php

        $error_new_multi_set=yasr_process_new_multi_set_form(); //defined in yasr-settings-functions

        $error_edit_multi_set=yasr_process_edit_multi_set_form(); //defined in yasr-settings-functions

        if ($error_new_multi_set) {
        	echo "<div class=\"error\"> <p> <strong>";

      		foreach ($error_new_multi_set as $error) {
      			_e($error, 'yet-another-stars-rating');
      			echo "<br />";
      		}

    		echo "</strong></p></div>";
    	}

        if ($error_edit_multi_set) {
        	echo "<div class=\"error\"> <p> <strong>";

      		foreach ($error_edit_multi_set as $error) {
      			_e($error, 'yet-another-stars-rating');
      			echo "<br />";
      		}

    		echo "</strong></p></div>";
    	}


		if (isset($_GET['tab'])) {
    		$active_tab = $_GET[ 'tab' ];
		}

		else {
			$active_tab = 'general_settings';
		}


		?>

        <h2 class="nav-tab-wrapper yasr-no-underline">

            <a href="?page=yasr_settings_page&tab=general_settings" class="nav-tab <?php if ($active_tab == 'general_settings') echo 'nav-tab-active'; ?>" > <?php _e("General Settings", 'yet-another-stars-rating'); ?> </a>
            <a href="?page=yasr_settings_page&tab=manage_multi" class="nav-tab <?php if ($active_tab == 'manage_multi') echo 'nav-tab-active'; ?>" > <?php _e("Multi Sets", 'yet-another-stars-rating'); ?> </a>
            <a href="?page=yasr_settings_page&tab=style_options" class="nav-tab <?php if ($active_tab == 'style_options') echo 'nav-tab-active'; ?>" > <?php _e("Aspect & Styles", 'yet-another-stars-rating'); ?> </a>
            <?php do_action( 'yasr_add_settings_tab', $active_tab ); ?>
            <a href="?page=yasr_settings_page&tab=extensions" class="nav-tab <?php if ($active_tab == 'extensions') echo 'nav-tab-active'; ?>" > <?php _e("Extensions", 'yet-another-stars-rating'); ?> </a>

        </h2>



	    <?php

	    if ($active_tab == 'general_settings') {

	    	?>

		    <div class="yasr-settingsdiv">
		        <form action="options.php" method="post" id="yasr_settings_form">
		            <?php
			            settings_fields( 'yasr_general_options_group' );
			            do_settings_sections('yasr_general_settings_tab' );
		            	submit_button( __('Save Settings') );
		           	?>
		       	</form>
		    </div>

	        <?php

		            yasr_fb_box ();
			        yasr_ask_rating ();

		        ?>

				<div class="yasr-space-settings-div">
				</div>

				<?php


		} //End if tab 'general_settings'


		if ($active_tab == 'manage_multi') {

			$multi_set=yasr_get_multi_set();

			global $wpdb;

			$n_multi_set = $wpdb->num_rows; //wpdb->num_rows always store the last of the last query

			?>

			<div class="yasr-settingsdiv">

				<h3> <?php _e("Manage Multi Set", 'yet-another-stars-rating'); ?></h3>

				<p>

					<a href="#" id="yasr-multi-set-doc-link"><?php _e("What is a Multi Set?", 'yet-another-stars-rating') ?></a>

				</p>

				<div id="yasr-multi-set-doc-box" style="display:none">
					<?php _e("Multi Set allows you to insert a rate for each aspect about the product / local business / whetever you're reviewing, example in the image below.", 'yet-another-stars-rating');

					echo "<br /><br /><img src=" . YASR_IMG_DIR . "/yasr-multi-set.png> <br /> <br />";

					_e("You can create up to 99 different Multi Set and each one can contain up to 9 different fields. Once you've saved it, you can insert the rates while typing your article in the box below the editor, as you can see in this image (click to see it larger)", 'yet-another-stars-rating');

					echo "<br /><br /><a href=\"" . YASR_IMG_DIR ."yasr-multi-set-insert-rate.jpg\"><img src=" . YASR_IMG_DIR . "/yasr-multi-set-insert-rate-small.jpg></a> <br /> <br />";

					_e("In order to insert your Multi Sets into a post or page, you can either past the short code that will appear at the bottom of the box or just click on the star in the graphic editor and select \"Insert Multi Set\".", 'yet-another-stars-rating');

					?>

					<br /> <br />

					<a href="#" id="yasr-multi-set-doc-link-hide"><?php _e("Close this message", 'yet-another-stars-rating') ?></a>

				</div>

				<div class="yasr-multi-set-left">

					<div class="yasr-new-multi-set" >

						<?php yasr_display_multi_set_form(); ?>

					</div> <!--yasr-new-multi-set-->

				</div> <!--End yasr-multi-set-left-->

				<div class="yasr-multi-set-right">

					<?php yasr_edit_multi_form(); ?>

					<div id="yasr-multi-set-response" style="display:none">

					</div>

				</div> <!--End yasr-multi-set-right-->

				<div class="yasr-space-settings-div">
				</div>


				<div class="yasr-multi-set-choose-theme">

					<!--This allow to choose color for multiset-->
					<form action="options.php" method="post" id="yasr_multiset_form">
				            <?php
					            settings_fields( 'yasr_multiset_options_group' );
					            do_settings_sections('yasr_multiset_tab' );
				            	submit_button( __('Save') );
				           	?>
				    </form>

				</div>


			</div>

			<?php
				yasr_fb_box ();
		        yasr_ask_rating ();
	        ?>

			<div class="yasr-space-settings-div">
			</div>

			<?php

		} //End if ($active_tab=='manage_multi')


		if ($active_tab == 'style_options') {

			?>

			<?php do_action('yasr_add_content_top_style_options_tab', $active_tab); ?>

			<div class="yasr-settingsdiv">
		        <form action="options.php" method="post" enctype='multipart/form-data' id="yasr_settings_form">
		            <?php
			            settings_fields( 'yasr_style_options_group' );
			            do_settings_sections('yasr_style_tab' );
		            	submit_button( __('Save') );
		           	?>
		       	</form>
			</div>

			<?php
				yasr_fb_box ();
		        yasr_ask_rating ();
	        ?>

			<div class="yasr-space-settings-div">
			</div>

			<?php do_action('yasr_add_content_bottom_style_options_tab', $active_tab); ?>


			<?php

		} //End tab style


		if ($active_tab == 'extensions') {

            ?>

            	<div class="yasr-extensionsdiv">

            	<h1><?php _e("Extensions for Yet Another Stars Rating", "yet-another-stars-rating");?></h1>

            		<div class="yasr-indented-answer">
            			<?php _e("Extensions add functionality to your Yasr installation", "yet-another-stars-rating"); ?>
            		</div>

            		<div class="yasr-space-settings-div">
					</div>
					<div class="yasr-space-settings-div">
					</div>

            		<div class="yasr-extension">

            			<span class="yasr-extension-title">Yasr User Reviews</span>

            			<img src=<?php echo YASR_IMG_DIR . '/yasr-user-reviews.png'?> alt='Yasr User Reviews' class="yasr-extension-image"/>

            			<span class="yasr-extension-description">
            				<?php _e("Start accepting reviews and ratings for your post or pages using Yasr User Reviews extension.", "yet-another-stars-rating"); ?>
            			</span>

            			<div class="yasr-space-settings-div">
						</div>

						<?php

            				if (function_exists('yasr_ur_license_page')) {

								yasr_ur_license_page ();

							}

            				else {

            					?>

            					<a href="https://yetanotherstarsrating.com/extensions/reviews-in-comments/" target="_blank" class="button-secondary"><?php _e("Get this extension", "yet-another-stars-rating") ?></a>

            					<?php

            				}


            				?>

            			<p>

            		</div>

            		<div class="yasr-extension">

            			<span class="yasr-extension-title">Yasr Stylish</span>

            			<img src=<?php echo YASR_IMG_DIR . '/Yasr-Stylish.png'?> alt='Yasr Stylish' class="yasr-extension-image-no-width"/>

            			<span class="yasr-extension-description">
            				<?php _e("Choose between ready to use image or upload your own!", "yet-another-stars-rating"); ?>
            			</span>

            			<div class="yasr-space-settings-div">
						</div>

						<?php

            				if (function_exists('yasr_st_license_page')) {

								yasr_st_license_page ();

							}

            				else {

            					?>

            					<a href="https://yetanotherstarsrating.com/extensions/yasr-stylish/" target="_blank" class="button-secondary"><?php _e("Get this extension", "yet-another-stars-rating") ?></a>

            					<?php

            				}


            				?>

            			<p>

            		</div>

            		<div class="yasr-extension">

            			<span class="yasr-extension-title">Yasr Custom Rankings</span>

            			<img src=<?php echo YASR_IMG_DIR . '/create-ranking.png'?> alt='Yasr Custom Rankings' class="yasr-extension-image"/>

            			<span class="yasr-extension-description">
            				<?php _e("Unleash all the power of your rankings with just a click!", "yet-another-stars-rating"); ?>
            			</span>

            			<div class="yasr-space-settings-div">
						</div>

						<?php

							if (function_exists('yasr_cr_license_page')) {

								yasr_cr_license_page ();

							}

            				else {

            					?>

            					<a href="https://yetanotherstarsrating.com/extensions/yasr-custom-rankings/" target="_blank" class="button-secondary"><?php _e("Get this extension", "yet-another-stars-rating") ?></a>

            					<?php

            				}

            			?>

            			<p>
            		</div>

            <?php

		}

		do_action( 'yasr_settings_check_active_tab', $active_tab );



	yasr_fb_box("bottom");
	yasr_ask_rating ("bottom");

	?>

	<!--End div wrap-->
	</div>


    <script type="text/javascript">

	    jQuery( document ).ready(function() {

	    	var activeTab = <?php echo (json_encode("$active_tab")); ?>;

   			var nMultiSet = <?php echo (json_encode("$n_multi_set")); ?> ;//Null in php is different from javascript NULL

   			var autoInsertEnabled = <?php echo (json_encode(YASR_AUTO_INSERT_ENABLED)); ?>;

			var textBeforeStars = <?php echo (json_encode(YASR_TEXT_BEFORE_STARS)); ?>;

   			var nonceHideAskRating = <?php echo (json_encode("$ajax_nonce_hide_ask_rating")); ?>

		   	YasrSettingsPage(activeTab, nMultiSet, autoInsertEnabled, textBeforeStars);

		   	YasrAsk5Stars(nonceHideAskRating);

	    }); //End jquery document ready

	</script>
