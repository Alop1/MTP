<?php

global $recipe,$recipe_settings,$_cooked_settings;

$recipe_post = get_post( $recipe['id'] );
$recipe_settings = Cooked_Recipes::get( $recipe['id'], true );
$recipe_classes = apply_filters( 'cooked_single_recipe_classes', array( 'cooked-recipe', 'has-post-thumbnail' ), $recipe );
if ( is_array($recipe_classes) && !empty($recipe_classes) ):
	array_walk($recipe_classes, 'esc_attr');
else:
	$recipe_classes = array();
endif;

echo '<article data-permalink="' . get_permalink( $recipe['id'] ) . '" id="cooked-recipe-' . $recipe['id'] . '" class="' . implode( ' ', $recipe_classes ) . '">';

	do_action( 'cooked_recipe_grid_before_recipe', $recipe );

	echo '<div class="cooked-recipe-wrap">';

		do_action( 'cooked_recipe_grid_before_image', $recipe );

		if ( has_post_thumbnail( $recipe_post ) && $recipe_thumb = get_the_post_thumbnail( $recipe_post, 'cooked-medium' ) ):
			echo '<a href="' . get_permalink( $recipe['id'] ) . '" class="cooked-recipe-image">';
				echo $recipe_thumb;
			echo '</a>';
		else:
			echo '<span class="cooked-recipe-image-empty"></span>';
		endif;

		do_action( 'cooked_recipe_grid_after_image', $recipe );

		echo '<div class="cooked-recipe-inside">';

			do_action( 'cooked_recipe_before_name', $recipe );

			echo '<a href="' . get_permalink( $recipe['id'] ) . '" class="cooked-recipe-name">';
				echo $recipe['title'];
			echo '</a>';

			do_action( 'cooked_recipe_after_name', $recipe );

			if (in_array('excerpt',$_cooked_settings['recipe_info_display_options'])):
				echo ( isset($recipe['excerpt']) && $recipe['excerpt'] ? '<span class="cooked-recipe-excerpt">' . do_shortcode( $recipe['excerpt'] ) . '</span>' : '' );
			endif;

			echo do_shortcode( apply_filters( 'cooked_recipe_list_info_shortcode', '[cooked-info include="author"]', 'grid' ) );

		echo '</div>';

	echo '</div>';

	do_action( 'cooked_recipe_grid_after_recipe', $recipe );

echo '</article>';
