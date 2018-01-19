<?php
/**
 * Post Types
 *
 * @package     Cooked
 * @subpackage  Taxonomies
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cooked_Taxonomies Class
 *
 * This class handles the taxonomy creation.
 *
 * @since 1.0.0
 */
class Cooked_Taxonomies {

	public static function get(){

		global $query_var;

		$_cooked_settings = Cooked_Settings::get();

		$front_page_id = get_option( 'page_on_front' );
		if ( $_cooked_settings['browse_page'] == $front_page_id ):
			$query_var = false;
		else:
			$query_var = true;
		endif;

		$taxonomy_permalinks = apply_filters( 'cooked_taxonomy_settings', array(
			'cp_recipe_category' => ( isset($_cooked_settings['recipe_category_permalink']) && $_cooked_settings['recipe_category_permalink'] ? $_cooked_settings['recipe_category_permalink'] : 'recipe-category' )
		));

		$taxonomies = apply_filters( 'cooked_taxonomies', array(

			'cp_recipe_category' => array(
				'hierarchical'        => true,
				'labels'              => array(
					'name'                => esc_html__('Categories', 'cooked'),
					'singular_name'       => esc_html__('Category', 'cooked'),
					'search_items'        => esc_html__('Search Categories', 'cooked'),
					'all_items'           => esc_html__('All Categories', 'cooked'),
					'parent_item'         => esc_html__('Parent Category', 'cooked'),
					'parent_item_colon'   => esc_html__('Parent Category:', 'cooked'),
					'edit_item'           => esc_html__('Edit Category', 'cooked'),
					'update_item'         => esc_html__('Update Category', 'cooked'),
					'add_new_item'        => esc_html__('Add New Category', 'cooked'),
					'new_item_name'       => esc_html__('New Category Name', 'cooked'),
					'menu_name'           => esc_html__('Categories', 'cooked'),
					'not_found'           => esc_html__('No Categories', 'cooked')
				),
				'show_ui'             => true,
				'show_admin_column'   => true,
				'show_in_menu'		  => false,
				'rest_base'             => 'recipe_category',
    			'rest_controller_class' => 'WP_REST_Terms_Controller',
				'query_var'           => $query_var,
				'rewrite'             => array( 'slug' => $taxonomy_permalinks['cp_recipe_category'] )
			)

		), $taxonomy_permalinks, $query_var );

		if ( !in_array( 'cp_recipe_category', $_cooked_settings['recipe_taxonomies'] ) ): unset( $taxonomies['cp_recipe_category'] ); endif;

		return $taxonomies;

	}

}
