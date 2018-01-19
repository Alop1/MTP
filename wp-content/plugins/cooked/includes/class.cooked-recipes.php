<?php
/**
 * Cooked Recipe-Specific Functions
 *
 * @package     Cooked
 * @subpackage  Recipe-Specific Functions
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cooked_Recipe_Meta Class
 *
 * This class handles the Cooked Recipe Meta Box creation.
 *
 * @since 1.0.0
 */
class Cooked_Recipes {

	public function __construct() {

		add_filter( 'the_content', array(&$this, 'recipe_template') );
		add_action( 'template_redirect', array(&$this, 'print_recipe_template'), 10 );
		add_action( 'cooked_check_recipe_query', array(&$this, 'check_recipe_query'), 10 );
		add_action( 'pre_get_posts', array(&$this, 'cooked_pre_get_posts'), 10, 1 );

		add_action( 'restrict_manage_posts', array(&$this, 'filter_recipes_by_taxonomy'), 10 );
		add_filter( 'parse_query', array(&$this, 'custom_taxonomy_in_query'), 10 );

	}

	public function check_recipe_query(){

		global $recipe_query;

		if ( !isset($recipe_query['cp_recipe_category']) ):
			$recipe_query['cp_recipe_category'] = ( isset($_GET['cp_recipe_category']) && $_GET['cp_recipe_category'] ? esc_attr($_GET['cp_recipe_category']) : ( isset($_cooked_settings['browse_default_cp_recipe_category']) && $_cooked_settings['browse_default_cp_recipe_category'] ? $_cooked_settings['browse_default_cp_recipe_category'] : false ) );
		endif;

	}

	function cooked_pre_get_posts( $q ){

	    if( $title = $q->get( '_cooked_title' ) ):
	        add_filter( 'get_meta_sql', function( $sql ) use ( $title ){

	            global $wpdb,$cooked_modified_where;

	            if ( $cooked_modified_where ) return $sql;
	            $cooked_modified_where = 1;

	            // Modified WHERE
	            $sql['where'] = sprintf(
	                " AND ( %s OR %s ) ",
	                apply_filters( 'cooked_query_where_filter', $wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title) ),
	                mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
	            );

	            return $sql;

	        });
	    endif;

	}

	public function print_recipe_template(){

		if ( is_singular('cp_recipe') && isset($_GET['print']) ):

			load_template( COOKED_DIR . 'templates/front/recipe-print.php',false);
			exit;

		endif;

	}

	public function filter_recipes_by_taxonomy() {
		global $typenow,$cooked_taxonomies_shown;
		$taxonomies = apply_filters( 'cooked_active_taxonomies', array( 'cp_recipe_category' ) );
		if ( $typenow == 'cp_recipe' ):
			foreach( $taxonomies as $taxonomy ):
				if ( is_array($cooked_taxonomies_shown) && !in_array( $taxonomy, $cooked_taxonomies_shown ) || !is_array($cooked_taxonomies_shown) ):
					$cooked_taxonomies_shown[] = $taxonomy;
					$selected      = isset($_GET[$taxonomy]) ? esc_html($_GET[$taxonomy]) : '';
					$info_taxonomy = get_taxonomy($taxonomy);
					wp_dropdown_categories(array(
						'show_option_all' => esc_html__( "All {$info_taxonomy->label}", "cooked" ),
						'taxonomy'        => $taxonomy,
						'name'            => $taxonomy,
						'orderby'         => 'name',
						'selected'        => $selected,
						'show_count'      => true,
						'hide_empty'      => true,
					));
				endif;
			endforeach;
		endif;
	}

	public function custom_taxonomy_in_query($query) {
		global $pagenow;
		$taxonomies = apply_filters( 'cooked_active_taxonomies', array( 'cp_recipe_category' ) );
		$q_vars    = &$query->query_vars;
		foreach( $taxonomies as $taxonomy ):
			if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == 'cp_recipe' && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ):
				$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
				$q_vars[$taxonomy] = $term->slug;
			endif;
		endforeach;
	}

	public static function get( $args = false, $single = false, $ids_only = false ) {

		$recipes = array();
		$counter = 0;

		if ( $args && !is_array($args) ):

			$recipe_id = $args;
			$args = array(
				'post_type' => 'cp_recipe',
				'post__in' => array( $recipe_id ),
				'post_status' => 'publish'
			);

		elseif ( !$args || !is_array($args) ):

			$args = array(
				'post_type' => 'cp_recipe',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby'=>'name',
				'order'=>'ASC'
			);

		elseif ( $args && isset($args['s']) && isset($args['meta_query']) ):

			$meta_query = $args['meta_query'];
			$recipe_ids = array();

			$pre_search_args = $args;
			$pre_search_args['posts_per_page'] = -1;

			unset($pre_search_args['meta_query']);

			$recipes_pre_search = new WP_Query($pre_search_args);
			if ( $recipes_pre_search->have_posts() ):
				$rposts = $recipes_pre_search->posts;
				$recipe_ids = wp_list_pluck($rposts, 'ID');
			endif;

			$pre_search_args['meta_query'] = $meta_query;
			unset($pre_search_args['s']);

			$recipes_pre_search = new WP_Query($pre_search_args);
			if ( $recipes_pre_search->have_posts() ):
				$rposts = $recipes_pre_search->posts;
				$recipe_ids = ( !empty($recipe_ids) ? array_merge( $recipe_ids, wp_list_pluck($rposts, 'ID') ) : wp_list_pluck($rposts, 'ID') );
			endif;

			$recipe_ids = array_unique( $recipe_ids );

			if ( !empty($recipe_ids) ):
				unset($args['s']);
				unset($args['meta_query']);
				$args['post__in'] = $recipe_ids;
			endif;

		endif;

		$recipes_results = new WP_Query($args);

		if ( $recipes_results->have_posts() ):
			while ( $recipes_results->have_posts() ): $recipes_results->the_post();
				if ( $ids_only ):
					$recipes[] = $recipes_results->post->ID;
				else:
					$recipes[$counter]['id'] = $recipes_results->post->ID;
					$recipes[$counter]['title'] = $recipes_results->post->post_title;
					$recipe_settings = self::get_settings($recipes_results->post->ID);
					foreach($recipe_settings as $key => $setting):
						$recipes[$counter][$key] = $setting;
					endforeach;
					$counter++;
				endif;
			endwhile;
		else:
			wp_reset_postdata();
			return;
		endif;

		if ( $ids_only ):
			return $recipes;
		endif;

		$recipes['raw'] = $recipes_results;

		if ( $single && isset( $recipes[0] ) ):
			$recipes = $recipes[0];
		endif;

		wp_reset_postdata();

    	return $recipes;

	}

	public static function list_view( $list_atts = false ){

		global $wp_query,$recipe_query,$atts,$_cooked_settings,$recipes,$recipe_query,$recipe_args,$current_recipe_page;

		// Get the attributes for this view
		$atts = $list_atts;
		$ls_method = 'list_style_grid';
		$ls_class = 'Cooked_Recipes';

		// Change the recipe layout
		if ( $atts['layout'] ):

			$recipe_list_style = apply_filters( 'cooked_recipe_list_style', array( 'grid' => 'Cooked_Recipes' ), $atts['layout'] );
			$list_style = esc_attr( key( $recipe_list_style ) );
			$ls_method = 'list_style_' . $list_style;
			$ls_class = current( $recipe_list_style );

			$_cooked_settings['recipe_list_style'] = $list_style;

		endif;

		$recipe_query = $wp_query->query;
		$tax_query = array();

		do_action( 'cooked_check_recipe_query' );

		if ( isset($_cooked_settings['recipe_taxonomies']) && !empty($_cooked_settings['recipe_taxonomies']) ):
			foreach( $_cooked_settings['recipe_taxonomies'] as $taxonomy ):
				if ( isset($recipe_query[$taxonomy]) && $recipe_query[$taxonomy] ):
					$field_type = ( is_numeric($recipe_query[$taxonomy]) ? 'id' : 'slug' );
					$tax_query['relation'] = 'AND';
					$tax_query[] = array(
						'taxonomy' 	=> $taxonomy,
						'field'		=> $field_type,
						'terms'		=> array_map( 'trim', explode(',', esc_attr( $recipe_query[$taxonomy] ) ) )
					);
				endif;
			endforeach;
			if ( empty($tax_query) ):
				foreach( $_cooked_settings['recipe_taxonomies'] as $taxonomy ):
					if ( isset( $_cooked_settings['browse_default_' . $taxonomy] ) && $_cooked_settings['browse_default_' . $taxonomy] ):
						$tax_query['relation'] = 'AND';
						$tax_query[] = array(
							'taxonomy' 	=> $taxonomy,
							'field'		=> 'id',
							'terms'		=> array( esc_attr( $_cooked_settings['browse_default_' . $taxonomy] ) )
						);
					endif;
				endforeach;
			endif;
		endif;

		if ( empty($tax_query) ):

			if ( $atts['category'] ):
				$tax_query['relation'] = 'AND';
				$tax_query[] = array(
					'taxonomy' 	=> 'cp_recipe_category',
					'field'		=> 'slug',
					'terms'		=> array_map( 'trim', explode(',', esc_attr( $atts['category'] ) ) )
				);
			endif;

			$tax_query = apply_filters( 'cooked_tax_query', $tax_query, $atts );

		endif;

		$sorting_type = ( isset($_GET['cooked_browse_sort_by']) && $_GET['cooked_browse_sort_by'] ? esc_attr($_GET['cooked_browse_sort_by']) : ( isset($_cooked_settings['browse_default_sort']) && $_cooked_settings['browse_default_sort'] ? $_cooked_settings['browse_default_sort'] : 'date_desc' ) );
		$sorting_types = explode( '_', $sorting_type );

		$text_search = ( isset($_GET['cooked_search_s']) && $_GET['cooked_search_s'] ? esc_attr($_GET['cooked_search_s']) : '' );
		$recipes_per_page = ( $atts['show'] ? $atts['show'] : ( isset($_cooked_settings['recipes_per_page']) && $_cooked_settings['recipes_per_page'] ? $_cooked_settings['recipes_per_page'] : get_option( 'posts_per_page' ) ) );
		$current_recipe_page = Cooked_Recipes::current_page();

		$orderby = ( $atts['orderby'] ? esc_attr( $atts['orderby'] ) : $sorting_types[0] );
		$meta_sort =  false;

		$recipe_args = array(
			'paged' => $current_recipe_page,
		 	'post_type' => 'cp_recipe',
		 	'posts_per_page' => $recipes_per_page,
		 	'post_status' => 'publish',
		 	'orderby' => $orderby,
		 	'order' => ( $atts['order'] ? esc_attr( $atts['order'] ) : $sorting_types[1] )
		);

		if ( $text_search ):

			// Replace [+] [,] [;] with spaces
			$prep_text = str_replace(array('+',',',';'),' ',$text_search);

			// Replace duplicate spaces
			$prep_text = preg_replace('/\s+/', ' ', $prep_text);

			// Explode into an array of search terms
			$words = explode( ' ', $prep_text );

			if ( !empty($words) ):
				$meta_query['relation'] = 'AND';
				foreach( $words as $word ):
					$meta_query[] = array(
						'key' => '_recipe_settings',
		    			'value' => $word,
		    			'compare' => 'LIKE'
					);
				endforeach;
			else:
				$meta_query[] = array(
					'key' => '_recipe_settings',
	    			'value' => $text_search,
	    			'compare' => 'LIKE'
				);
			endif;
			$recipe_args['_cooked_title'] = $prep_text;
			$recipe_args['meta_query'] = $meta_query;

		endif;

		if ( !empty($tax_query) ):
			$recipe_args['tax_query'] = $tax_query;
		endif;

		if ( $atts['author'] && is_numeric( $atts['author'] ) ):
			$recipe_args['author'] = $atts['author'];
		elseif ( $atts['author'] ):
			$recipe_args['author_name'] = $atts['author'];
		endif;

		if ( isset( $atts['include'] ) && !empty($atts['include']) ):
			$recipe_args['post__in'] = $atts['include'];
		endif;

		$recipe_args = apply_filters( 'cooked_recipe_query_args', $recipe_args, $atts, $sorting_types );
		if ( !isset($atts['public_recipes']) || isset($atts['public_recipes']) && $atts['public_recipes'] ):
			$recipe_args = apply_filters( 'cooked_recipe_public_query_filters', $recipe_args );
		endif;

		wp_suspend_cache_addition(true);

		ob_start();
		$recipes = Cooked_Recipes::get( $recipe_args );
		load_template( COOKED_DIR . 'templates/front/recipe-list.php',false);
		wp_reset_postdata();
		Cooked_Settings::reset();

		wp_suspend_cache_addition(false);

		return ob_get_clean();

	}

	public static function list_style_grid(){
		load_template( COOKED_DIR . 'templates/front/recipe-single.php',false);
	}

	public static function current_page(){
		return ( get_query_var( 'paged' ) ? max( 1, get_query_var('paged') ) : ( get_query_var( 'page' ) ? max( 1, get_query_var('page') ) : 1 ) );
	}

	public static function pagination( $recipe_query, $recipe_args ){

		global $_cooked_settings,$current_recipe_page,$paged,$atts;

		$paged = self::current_page();
		$total_recipe_pages = $recipe_query->max_num_pages;
		$pagination = '';

		if ( $total_recipe_pages > 1):

			do_action( 'cooked_init_pagination', $recipe_args, $current_recipe_page, $total_recipe_pages, $atts );

			$pagination_style = apply_filters( 'cooked_pagination_style', array( 'numbered_pagination' => 'Cooked_Recipes' ) );
			$p_method = key( $pagination_style );
			$p_class = current( $pagination_style );

			$pagination = $p_class::$p_method( $current_recipe_page, $total_recipe_pages );

		endif;

		return $pagination;

	}

	public static function numbered_pagination( $current_recipe_page, $total_recipe_pages ){
		$recipe_pagination = apply_filters( 'cooked_pagination_args', array(
	        'base' => @add_query_arg('paged','%#%'),
	        'format' => '?paged=%#%',
	        'mid-size' => 1,
	        'current' => $current_recipe_page,
	        'total' => $total_recipe_pages,
	        'prev_next' => true,
	        'prev_text' => '<i class="cooked-icon cooked-icon-angle-left"></i>',
	        'next_text' => '<i class="cooked-icon cooked-icon-angle-right"></i>'
	    ));
	    return '<div class="cooked-pagination-numbered cooked-clearfix">' . paginate_links( $recipe_pagination ) . '</div>';
	}

	public static function default_content(){
		return apply_filters( 'cooked_default_content', '<p>[cooked-info left="author,taxonomies,difficulty" right="print,fullscreen"]</p><p>[cooked-excerpt]</p><p>[cooked-image]</p><p>[cooked-info left="servings" right="prep_time,cook_time,total_time"]</p><p>[cooked-ingredients]</p><p>[cooked-directions]</p><p>[cooked-gallery]</p>' );
	}

	public static function print_content(){
		return apply_filters( 'cooked_print_content', '<p>[cooked-info include="servings,prep_time,cook_time,total_time"]</p><p>[cooked-excerpt]</p><p>[cooked-image]</p><p>[cooked-ingredients]</p><p>[cooked-directions]</p>' );
	}

	public static function fsm_content(){
		return apply_filters( 'cooked_fsm_content', '<div class="cooked-fsm-ingredients cooked-fsm-content cooked-active"><div class="cooked-panel"><h2>' . esc_html__('Ingredients','cooked') . '</h2>[cooked-ingredients]</div></div><div class="cooked-fsm-directions cooked-fsm-content"><div class="cooked-panel"><h2>' . esc_html__('Directions','cooked') . '</h2>[cooked-directions]</div></div>' );
	}

	public static function difficulty_levels(){
		return apply_filters( 'cooked_difficulty_levels', array(
			1 => esc_html__('Beginner','cooked'),
			2 => esc_html__('Intermediate','cooked'),
			3 => esc_html__('Advanced','cooked') )
		);
	}

	public static function get_settings( $post_id, $bc = true ){

		if ( !$post_id )
			return;

		$recipe_settings = get_post_meta( $post_id, '_recipe_settings', true );

		if ( !$recipe_settings || empty($recipe_settings) ): $recipe_settings = array(); endif;
		$recipe_settings['title'] = get_the_title( $post_id );

		$recipe_post = get_post($post_id);
		$wp_excerpt = $recipe_post->post_excerpt;

		// Check for excerpt/content
		if ( isset($recipe_settings['excerpt']) && !$recipe_settings['excerpt'] && !$wp_excerpt || !isset($recipe_settings['excerpt']) ):
			wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => $recipe_settings['title'] ) );
		endif;

		// Check for nutrition data
		if ( !isset($recipe_settings['nutrition']) ):
			$recipe_settings['nutrition'] = array();
			$recipe_settings['nutrition']['servings'] = 1;
		endif;

		// Backwards Compatibility with Cooked 2.x
		if ( !isset($recipe_settings['cooked_version']) && $bc ):
			$c2_recipe_settings = Cooked_Recipes::get_c2_recipe_meta( $post_id );
			$recipe_settings = Cooked_Recipes::sync_c2_recipe_settings( $c2_recipe_settings,$post_id );
		endif;

		// You're welcome developers!
		$recipe_settings = apply_filters( 'cooked_single_recipe_settings', $recipe_settings, $post_id );

		return $recipe_settings;

	}

	public static function get_by_slug($slug = false){
		if ($slug):

			if (!function_exists('ctype_digit') || function_exists('ctype_digit') && !ctype_digit($slug)):
				$recipe_query = new WP_Query( array( 'name' => $slug, 'post_type' => 'cp_recipe' ) );
				if ($recipe_query->have_posts()):
					$recipe_query->the_post();
					return get_the_ID();
				else:
					return false;
				endif;
			else:
				return $slug;
			endif;

		else:

			return false;

		endif;
	}

	public static function gallery_types(){

		$gallery_types = apply_filters( 'cooked_gallery_types', array(
			'cooked' => array(
				'title' => esc_html__('Cooked Gallery','cooked'),
				'required_class' => ''
			),
			'envira' => array(
				'title' => esc_html__('Envira Gallery','cooked'),
				'required_class' => 'Envira_Gallery'
			),
			'soliloquy' => array(
				'title' => esc_html__('Soliloquy Slider','cooked'),
				'required_class' => 'Soliloquy'
			),
			'revslider' => array(
				'title' => esc_html__('Slider Revolution','cooked'),
				'required_class' => 'RevSlider'
			)
		));

		foreach ( $gallery_types as $slug => $gtype ):

			$results = array();

			if ( $gtype['required_class'] && class_exists($gtype['required_class']) ):

				if ( $slug == 'revslider' ):

					$slider = new RevSlider();
					$arrSliders = $slider->getArrSliders();
					if (!empty($arrSliders)):
						foreach($arrSliders as $slider):
							$results[ $slider->getAlias() ] = $slider->getTitle();
						endforeach;
					endif;

				else:

					$args = apply_filters( 'cooked_gallery_type_' . $slug . '_query', array(
						'post_type'   		=> $slug,
						'post_status' 		=> 'publish',
						'posts_per_page'	=> -1
					));
					$gallery_query = new WP_Query( $args );
					if ( $gallery_query->have_posts() ):
						while( $gallery_query->have_posts() ):
							$gallery_query->the_post();
							$results[$gallery_query->post->ID] = get_the_title();
						endwhile;
					endif;

				endif;

				if ( !empty($results) ):
					$gallery_types[$slug]['posts'] = $results;
				else:
					unset( $gallery_types[$slug] );
				endif;

			else:

				if ( $slug != 'cooked' ): unset( $gallery_types[$slug] ); endif;

			endif;

		endforeach;

		wp_reset_query();
		wp_reset_postdata();

		return $gallery_types;

	}

	public static function serving_size_switcher( $servings ){

		$default = $servings;
		$servings = esc_attr( get_query_var( 'servings', $servings ) );
		$printing = ( is_singular('cp_recipe') && isset($_GET['print']) );
		$counter = 1;

		$quarter = $default / 4;
		$half = $default / 2;
		$double = $default * 2;
		$triple = $default * 3;

		$servings_array = apply_filters( 'cooked_servings_switcher_options', array(
			'quarter' => array( 'name' => sprintf( esc_html( _n('Quarter (%s Serving)','Quarter (%s Servings)',$quarter,'cooked')),$quarter ), 'value' => $quarter ),
			'half' => array( 'name' => sprintf( esc_html( _n('Half (%s Serving)','Half (%s Servings)',$half,'cooked')),$half ), 'value' => $half ),
			'default' => array( 'name' => sprintf( esc_html( _n('Default (%s Serving)','Default (%s Servings)',$default,'cooked')),$default ), 'value' => $default ),
			'double' => array( 'name' => sprintf( esc_html__( 'Double (%s Servings)','cooked'),$double ), 'value' => $double ),
			'triple' => array( 'name' => sprintf( esc_html__( 'Triple (%s Servings)','cooked'),$triple ), 'value' => $triple ),
		), $quarter,$half,$default,$double,$triple );

		foreach( $servings_array as $key => $val ):
			if ( $val['value'] < 1 ): unset( $servings_array[$key] ); endif;
		endforeach;

		echo '<span class="cooked-servings"><span class="cooked-servings-icon"><i class="cooked-icon cooked-icon-recipe-icon"></i></span>';
		echo '<strong class="cooked-meta-title">' . esc_html__('Yields','cooked') . '</strong>';
			if ( !$printing ):
				echo '<a href="#">' . sprintf( esc_html( _n( '%d Serving', '%d Servings', $servings, 'cooked' ) ), $servings ) . '</a>';
				echo '<select name="servings" class="cooked-servings-changer">';
					foreach ( $servings_array as $stype ):
						echo '<option value="' . ( $default == $stype['value'] ? remove_query_arg( 'servings', false ) : add_query_arg( 'servings', $stype['value'] ) ) . '"' . ( $stype['value'] == $servings ? ' selected' : '' ) . '>' . $stype['name'] . '</option>';
					endforeach;
				echo '</select>';
			else:
				echo '<span>' . sprintf( esc_html( _n( '%d Serving', '%d Servings', $servings, 'cooked' ) ), $servings ) . '</span>';
			endif;
		echo '</span>';

	}

	public static function single_ingredient( $ing, $checkboxes = true, $plain_text = false ){

		global $recipe_settings;

		$Cooked_Measurements = new Cooked_Measurements();
		$measurements = $Cooked_Measurements->get();

		if ( isset($ing['section_heading_name']) && $ing['section_heading_name'] ):

			if ( $plain_text ):
				return $ing['section_heading_name'];
			else:
				echo '<div class="cooked-single-ingredient cooked-heading">' . $ing['section_heading_name'] . '</div>';
			endif;

		elseif ( isset($ing['name']) && $ing['name'] ):

			$default_serving_size = ( isset($recipe_settings['nutrition']['servings']) && $recipe_settings['nutrition']['servings'] ? $recipe_settings['nutrition']['servings'] : 1 );
			$multiplier = get_query_var( 'servings', $recipe_settings['nutrition']['servings'] );

			if ( !$multiplier || $multiplier == $default_serving_size ):
				$multiplier = 1;
			else:
				$multiplier = $multiplier / $default_serving_size;
			endif;

			if ($multiplier === 1):
				$amount = ( isset($ing['amount']) && $ing['amount'] ? esc_html( $ing['amount'] ) : false );
				$amount = $Cooked_Measurements->cleanup_amount($amount);
				$format = ( strpos($amount, '/') === false ? ( strpos($amount, '.') !== false || strpos($amount, ',') !== false ? 'decimal' : 'fraction' ) : 'fraction' );
				$float_amount = $Cooked_Measurements->calculate( $amount, 'decimal' );
				$amount = $Cooked_Measurements->format_amount($float_amount,$format);
			else:
				$amount = ( isset($ing['amount']) && $ing['amount'] ? esc_html( $ing['amount'] ) : false );
				$amount = $Cooked_Measurements->cleanup_amount($amount);
				$format = ( strpos($amount, '/') === false ? ( strpos($amount, '.') !== false || strpos($amount, ',') !== false ? 'decimal' : 'fraction' ) : 'fraction' );
				$float_amount = $Cooked_Measurements->calculate( $amount, 'decimal' );
				if ($float_amount):
					$float_amount = $float_amount * $multiplier;
					$amount = $Cooked_Measurements->format_amount($float_amount,$format);
				endif;
			endif;

			$measurement = ( isset($ing['measurement']) && $ing['measurement'] ? esc_html( $ing['measurement'] ) : false );
			$measurement = ( $measurement && $float_amount ? $Cooked_Measurements->singular_plural( $measurements[ $measurement ]['singular_abbr'], $measurements[ $measurement ]['plural_abbr'], $float_amount ) : false );

			$name = ( isset($ing['name']) && $ing['name'] ? apply_filters( 'cooked_ingredient_name', wp_kses_post( $ing['name'] ), $ing ) : false );

			if ( $plain_text ):
				return ( $amount ? $amount . ' ' : '' ) . ( $measurement ? $measurement . ' ' : '' ) . ( $name ? $name : '' );
			else:
				echo '<div itemprop="recipeIngredient" class="cooked-single-ingredient cooked-ingredient' . ( !$checkboxes ? ' cooked-ing-no-checkbox' : '' ) . '">';
					echo ( $checkboxes ? '<span class="cooked-ingredient-checkbox">&nbsp;</span>' : '' );
					echo ( $amount ? '<span class="cooked-ing-amount" data-decimal="' . $float_amount . '">' . $amount . '</span> <span class="cooked-ing-measurement">' . $measurement . '</span> ' : '' );
					echo ( $name ? '<span class="cooked-ing-name">' . $name . '</span>' : '' );
				echo '</div>';
			endif;

		endif;

	}

	public static function single_direction( $dir, $number = false, $plain_text = false, $step = false ){

		global $recipe_settings;

		if ( isset($dir['section_heading_name']) && $dir['section_heading_name'] ):

			if ( $plain_text ):
				return $dir['section_heading_name'];
			else:
				echo '<div class="cooked-single-direction cooked-heading">' . $dir['section_heading_name'] . '</div>';
			endif;

		elseif ( isset($dir['content']) && $dir['content'] ):

			$image = ( isset($dir['image']) && $dir['image'] ? wp_get_attachment_image( $dir['image'], 'full' ) : '' );
			$content = Cooked_Recipes::format_content( $dir['content'] );

			if ( $plain_text ):
				return $content;
			else:
				echo '<div class="cooked-single-direction cooked-direction' . ( $image ? ' cooked-direction-has-image' : '' ) . ( $number ? ' cooked-direction-has-number' . ( $number > 9 ? '-wide' : '' ) : '' ) . '"' . ( $step ? ' data-step="' . sprintf( esc_html__( 'Step %d', 'cooked' ), $step ) . '"' : '' ) . '>';
					echo ( $number ? '<span class="cooked-direction-number">' . $number . '</span>' : '' );
					echo '<div class="cooked-dir-content">' . do_shortcode( $content ) . ( $image ? wpautop( $image ) : '' ) . '</div>';
				echo '</div>';
			endif;

		endif;

	}

	public static function format_content( $content ){
		return wpautop( wp_kses_post( html_entity_decode( $content ) ) );
	}

	public static function difficulty_level( $level ){
		switch ( $level ):
			case 1:
				$level_text = esc_html__('Beginner','cooked');
				break;
			case 2:
				$level_text = esc_html__('Intermediate','cooked');
				break;
			case 3:
				$level_text = esc_html__('Advanced','cooked');
				break;
		endswitch;
		return '<span class="cooked-difficulty-level-' . esc_attr( $level ) . '">' . $level_text . '</span>';
	}

	public static function recipe_search_box( $options = false ){

		global $_cooked_settings,$recipe_args,$tax_col_count,$active_taxonomy;

		$tax_col_count = 0;
		$filters_set = array();
		$taxonomy_search_fields = '';

		if ( isset($recipe_args['tax_query']) ):
			foreach( $recipe_args['tax_query'] as $query ):
				if ( isset($query['taxonomy']) ):
					$filters_set[$query['taxonomy']] = implode( ',',$query['terms'] );
				endif;
			endforeach;
			if ( isset($filters_set) ):
				foreach( $filters_set as $taxonomy => $filter ):
					$this_tax = get_term_by( 'slug', $filter, $taxonomy );
					$this_tax = ( $this_tax ? $this_tax : get_term_by( 'id', $filter, $taxonomy ) );
					$filters_set[$taxonomy] = $this_tax->term_id;
					$active_taxonomy = ( !isset($active_taxonomy) ? $this_tax->name : $active_taxonomy );
				endforeach;
			endif;
		endif;

		$total_taxonomies = 0;

		ob_start();
		if ( !empty($_cooked_settings['recipe_taxonomies']) ):

			echo '<div class="cooked-field-wrap cooked-field-wrap-select' . ( isset($active_taxonomy) ? ' cooked-taxonomy-selected' : '' ) . '">';
				echo '<span class="cooked-browse-select">';
					echo '<span class="cooked-field-title">' . ( isset($active_taxonomy) ? $active_taxonomy : esc_html__('Browse','cooked') ) . '</span>';

					echo '<span class="cooked-browse-select-block cooked-clearfix">';

						do_action( 'cooked_before_search_filter_columns' );

						if ( isset($active_taxonomy) ):
							$recipes_page_id = ( $_cooked_settings['browse_page'] ? $_cooked_settings['browse_page'] : get_the_ID() );
							$view_all_recipes_url = get_permalink( $recipes_page_id );
						else:
							$view_all_recipes_url = false;
						endif;

						if ( in_array( 'cp_recipe_category',$_cooked_settings['recipe_taxonomies']) ):
							$categories_array = Cooked_Settings::terms_array( 'cp_recipe_category', false, esc_html__('No categories','cooked'), true );
							if ( !empty($categories_array) ):
								echo '<span class="cooked-tax-column">';
									echo '<span class="cooked-tax-column-title">' . esc_html__('Categories','cooked') . '</span>';
									echo ( $view_all_recipes_url ? '<a href="' . $view_all_recipes_url . '">' . esc_html__('All Categories','cooked') . '</a>' : '' );
									foreach( $categories_array as $key => $val ):
										if ( $key ):
											$term = get_term( $key );
											$term_link = ( !empty($term) ? get_term_link( $term ) : false );
											echo ( $term_link ? ( isset($active_taxonomy) && $active_taxonomy == $val ? '<strong><i class="cooked-icon cooked-icon-angle-right"></i>&nbsp;&nbsp;' : '' ) . '<a href="' . $term_link . '">' . $val . '</a>' . ( isset($active_taxonomy) && $active_taxonomy == $val ? '</strong>' : '' ) . '</a>' : '' );
											$total_taxonomies++;
										endif;
									endforeach;
									$tax_col_count++;
								echo '</span>';
							endif;
						endif;

						do_action( 'cooked_after_search_filter_columns' );

					echo '</span>';

				echo '</span>';

			echo '</div>';

		endif;

		if ( $total_taxonomies ):
			$taxonomy_search_fields = ob_get_clean();
		else:
			ob_flush();
			$taxonomy_search_fields = false;
		endif;

		if ( !isset( $recipe_args['tax_query'] ) || !get_option('permalink_structure') ):
			$page_id = ( $_cooked_settings['browse_page'] ? $_cooked_settings['browse_page'] : get_the_ID() );
			$form_redirect = get_permalink( $page_id );
		else:
			$form_redirect = '';
			$page_id = false;
		endif;

		echo '<section class="cooked-recipe-search cooked-clearfix' . ( isset( $options['compact'] ) && $options['compact'] ? ' cooked-search-compact' : '' ) . ( isset( $options['hide_sorting'] ) && $options['hide_sorting'] ? ' cooked-search-no-sorting' : '' ) . ( isset( $options['hide_browse'] ) && $options['hide_browse'] ? ' cooked-search-no-browse' : '' ) . '">';

			echo '<form action="' . $form_redirect . '" method="get">';

				echo '<div class="cooked-fields-wrap cooked-' . $tax_col_count . '-search-fields">';

					echo ( !$options['hide_browse'] && $taxonomy_search_fields ? $taxonomy_search_fields : '' );

					echo '<input class="cooked-browse-search" type="text" name="cooked_search_s" value="' . ( isset($_GET['cooked_search_s']) && $_GET['cooked_search_s'] ? esc_attr($_GET['cooked_search_s']) : '' ) . '" placeholder="' . esc_attr__('Find a recipe...','cooked') . '" />';

					echo '<a href="#" class="cooked-browse-search-button"><i class="cooked-icon cooked-icon-search"></i></a>';

				echo '</div>';

				echo '<input type="hidden" name="page_id" value="' . $page_id . '">';
				if ( isset($recipe_args['tax_query'][0]['taxonomy']) && is_front_page() || isset($recipe_args['tax_query'][0]['taxonomy']) && !get_option('permalink_structure') ):
					echo '<input type="hidden" name="' . $recipe_args['tax_query'][0]['taxonomy'] . '" value="' . $recipe_args['tax_query'][0]['terms'][0] . '">';
				endif;

				if ( is_array( $recipe_args['orderby'] ) ):
					$sorting_type = key($recipe_args['orderby']) . '_' . current( $recipe_args['orderby'] );
				else:
					$sorting_type = $recipe_args['orderby'] . '_' . $recipe_args['order'];
				endif;

				$sorting_types = apply_filters( 'cooked_browse_sorting_types', array(
					'date_desc' => array(
						'slug' => 'date_desc',
						'name' => esc_html__("Newest first","cooked")
					),
					'date_asc' => array(
						'slug' => 'date_asc',
						'name' => esc_html__("Oldest first","cooked")
					),
					'title_asc' => array(
						'slug' => 'title_asc',
						'name' => esc_html__("Alphabetical (A-Z)","cooked")
					),
					'title_desc' => array(
						'slug' => 'title_desc',
						'name' => esc_html__("Alphabetical (Z-A)","cooked")
					)
				), $sorting_type );

				if ( !$options['hide_sorting'] ):

					echo '<span class="cooked-sortby-wrap"><select class="cooked-sortby-select" name="cooked_browse_sort_by">';
						foreach( $sorting_types as $value => $type ):
							echo '<option value="' . $value . '"' . ( $sorting_type == $type['slug'] ? ' selected' : '' ) . '>' . $type['name'] . '</option>';
						endforeach;
					echo '</select></span>';

				endif;

			echo '</form>';

		echo '</section>';

	}

	public function recipe_template( $content ){

		global $wp_query, $post, $_cooked_content_unfiltered;

		if( is_singular('cp_recipe') && is_main_query() && $_cooked_content_unfiltered == false ):

			ob_start();
			load_template( COOKED_DIR . 'templates/front/recipe.php', false );
			$content = ob_get_clean();

		endif;

		return $content;

	}

	/**
	 * Cooked Classic 2.x Backwards Compatibility
	 *
	 * @since 1.0.0
	 */

	// Get and return the Cooked 2.x Classic recipe meta information
	public static function get_c2_recipe_meta( $post_id ){

		$recipe_meta = array(); $revised_array = array();
		$recipe_cs2_meta = get_post_meta($post_id);

		if ( isset($recipe_cs2_meta['_cp_recipe_ingredients']) ):

			foreach($recipe_cs2_meta as $key => $content):
				$revised_array[$key] = $content[0];
			endforeach;

			$recipe_cs2_meta = $revised_array;

			$recipe_meta['_cp_recipe_title'] = get_the_title( $post_id );

			$recipe_meta['_cp_recipe_ingredients'] = isset($recipe_cs2_meta['_cp_recipe_ingredients']) ? $recipe_cs2_meta['_cp_recipe_ingredients'] : false;
			$recipe_meta['_cp_recipe_detailed_ingredients'] = isset($recipe_cs2_meta['_cp_recipe_detailed_ingredients']) ? $recipe_cs2_meta['_cp_recipe_detailed_ingredients'] : false;

			$recipe_meta['_cp_recipe_directions'] = isset($recipe_cs2_meta['_cp_recipe_directions']) ? $recipe_cs2_meta['_cp_recipe_directions'] : false;
			$recipe_meta['_cp_recipe_detailed_directions'] = isset($recipe_cs2_meta['_cp_recipe_detailed_directions']) ? $recipe_cs2_meta['_cp_recipe_detailed_directions'] : false;

			$recipe_meta['_cp_recipe_external_video'] = isset($recipe_cs2_meta['_cp_recipe_external_video']) ? $recipe_cs2_meta['_cp_recipe_external_video'] : false;
			$recipe_meta['_cp_recipe_short_description'] = isset($recipe_cs2_meta['_cp_recipe_short_description']) ? $recipe_cs2_meta['_cp_recipe_short_description'] : false;
			$recipe_meta['_cp_recipe_excerpt'] = isset($recipe_cs2_meta['_cp_recipe_excerpt']) ? $recipe_cs2_meta['_cp_recipe_excerpt'] : false;
			$recipe_meta['_cp_recipe_difficulty_level'] = isset($recipe_cs2_meta['_cp_recipe_difficulty_level']) ? $recipe_cs2_meta['_cp_recipe_difficulty_level'] : false;
			$recipe_meta['_cp_recipe_prep_time'] = isset($recipe_cs2_meta['_cp_recipe_prep_time']) ? $recipe_cs2_meta['_cp_recipe_prep_time'] : false;
			$recipe_meta['_cp_recipe_cook_time'] = isset($recipe_cs2_meta['_cp_recipe_cook_time']) ? $recipe_cs2_meta['_cp_recipe_cook_time'] : false;
			$recipe_meta['_cp_recipe_additional_notes'] = isset($recipe_cs2_meta['_cp_recipe_additional_notes']) ? $recipe_cs2_meta['_cp_recipe_additional_notes'] : false;
			$recipe_meta['_cp_recipe_admin_rating'] = isset($recipe_cs2_meta['_cp_recipe_admin_rating']) ? $recipe_cs2_meta['_cp_recipe_admin_rating'] : false;
			$recipe_meta['_cp_recipe_yields'] = isset($recipe_cs2_meta['_cp_recipe_yields']) ? $recipe_cs2_meta['_cp_recipe_yields'] : false;

			$recipe_meta['_cp_recipe_nutrition_servingsize'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_servingsize']) ? $recipe_cs2_meta['_cp_recipe_nutrition_servingsize'] : false;
			$recipe_meta['_cp_recipe_nutrition_calories'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_calories']) ? $recipe_cs2_meta['_cp_recipe_nutrition_calories'] : false;
			$recipe_meta['_cp_recipe_nutrition_fat'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_fat']) ? $recipe_cs2_meta['_cp_recipe_nutrition_fat'] : false;
			$recipe_meta['_cp_recipe_nutrition_satfat'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_satfat']) ? $recipe_cs2_meta['_cp_recipe_nutrition_satfat'] : false;
			$recipe_meta['_cp_recipe_nutrition_transfat'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_transfat']) ? $recipe_cs2_meta['_cp_recipe_nutrition_transfat'] : false;
			$recipe_meta['_cp_recipe_nutrition_cholesterol'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_cholesterol']) ? $recipe_cs2_meta['_cp_recipe_nutrition_cholesterol'] : false;
			$recipe_meta['_cp_recipe_nutrition_sodium'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_sodium']) ? $recipe_cs2_meta['_cp_recipe_nutrition_sodium'] : false;
			$recipe_meta['_cp_recipe_nutrition_potassium'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_potassium']) ? $recipe_cs2_meta['_cp_recipe_nutrition_potassium'] : false;
			$recipe_meta['_cp_recipe_nutrition_carbs'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_carbs']) ? $recipe_cs2_meta['_cp_recipe_nutrition_carbs'] : false;
			$recipe_meta['_cp_recipe_nutrition_fiber'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_fiber']) ? $recipe_cs2_meta['_cp_recipe_nutrition_fiber'] : false;
			$recipe_meta['_cp_recipe_nutrition_sugar'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_sugar']) ? $recipe_cs2_meta['_cp_recipe_nutrition_sugar'] : false;
			$recipe_meta['_cp_recipe_nutrition_protein'] = isset($recipe_cs2_meta['_cp_recipe_nutrition_protein']) ? $recipe_cs2_meta['_cp_recipe_nutrition_protein'] : false;

			return $recipe_meta;

		endif;

		return array();

	}

	// Sync up the Cooked 2.x Classic recipe meta fields to the new Cooked 3.x meta fields
	public static function sync_c2_recipe_settings( $c2_recipe_settings, $recipe_id ){

		$recipe_settings = array(); $ingredients = array(); $directions = array();

		$recipe_settings['title'] = $c2_recipe_settings['_cp_recipe_title'];
		$recipe_settings['content'] = wpautop( $c2_recipe_settings['_cp_recipe_short_description'] . ($c2_recipe_settings['_cp_recipe_short_description'] ? '<br><br>' : '') . Cooked_Recipes::default_content() . ($c2_recipe_settings['_cp_recipe_additional_notes'] ? '<br><br>' : '') . $c2_recipe_settings['_cp_recipe_additional_notes'] );
		$recipe_settings['excerpt'] = $c2_recipe_settings['_cp_recipe_excerpt'];
		$recipe_settings['difficulty_level'] = $c2_recipe_settings['_cp_recipe_difficulty_level'];
		$recipe_settings['prep_time'] = $c2_recipe_settings['_cp_recipe_prep_time'];
		$recipe_settings['cook_time'] = $c2_recipe_settings['_cp_recipe_cook_time'];

		// Ingredients
		if ( !empty($c2_recipe_settings['_cp_recipe_ingredients']) && empty($c2_recipe_settings['_cp_recipe_detailed_ingredients']) ):
			$ingredients = explode("\n", $c2_recipe_settings['_cp_recipe_ingredients']);
		elseif ( !empty($c2_recipe_settings['_cp_recipe_detailed_ingredients']) ):
			$ingredients = unserialize( $c2_recipe_settings['_cp_recipe_detailed_ingredients'] );
		endif;

		if ( !empty($ingredients) ):
			foreach( $ingredients as $ing ):
				$rand_id = rand( 100000000000,999999999999 );
				if ( isset($ing['type']) && $ing['type'] == 'ingredient' ):
					$recipe_settings['ingredients'][$rand_id]['amount'] = $ing['amount'];
					$recipe_settings['ingredients'][$rand_id]['measurement'] = $ing['measurement'];
					$recipe_settings['ingredients'][$rand_id]['name'] = $ing['name'];
				elseif ( isset($ing['type']) && $ing['type'] == 'section' ):
					$recipe_settings['ingredients'][$rand_id]['section_heading_name'] = $ing['value'];
				else:
					if ( substr($ing, 0, 2) == '--' ):
						$recipe_settings['ingredients'][$rand_id]['section_heading_name'] = substr($ing, 2);
					else:
						$recipe_settings['ingredients'][$rand_id]['amount'] = false;
						$recipe_settings['ingredients'][$rand_id]['measurement'] = false;
						$recipe_settings['ingredients'][$rand_id]['name'] = $ing;
					endif;
				endif;
			endforeach;
		endif;

		// Directions
		if ( !empty($c2_recipe_settings['_cp_recipe_directions']) && empty($c2_recipe_settings['_cp_recipe_detailed_directions']) ):
			$directions = explode("\n", $c2_recipe_settings['_cp_recipe_directions']);
		elseif ( !empty($c2_recipe_settings['_cp_recipe_detailed_directions']) ):
			$directions = unserialize( $c2_recipe_settings['_cp_recipe_detailed_directions'] );
		endif;

		if ( !empty($directions) ):

			foreach( $directions as $dir ):
				$rand_id = rand( 100000000000,999999999999 );
				if ( isset($dir['type']) && $dir['type'] == 'direction' ):
					$recipe_settings['directions'][$rand_id]['image'] = $dir['image_id'];
					$recipe_settings['directions'][$rand_id]['content'] = $dir['value'];
				elseif ( isset($dir['type']) && $dir['type'] == 'section' ):
					$recipe_settings['directions'][$rand_id]['section_heading_name'] = $dir['value'];
				else:
					if ( substr($dir, 0, 2) == '--' ):
						$recipe_settings['directions'][$rand_id]['section_heading_name'] = substr($dir, 2);
					else:
						$recipe_settings['directions'][$rand_id]['image'] = false;
						$recipe_settings['directions'][$rand_id]['content'] = $dir;
					endif;
				endif;
			endforeach;

		endif;

		if ( isset($c2_recipe_settings['_cp_recipe_external_video']) && $c2_recipe_settings['_cp_recipe_external_video'] && wp_oembed_get( $c2_recipe_settings['_cp_recipe_external_video'] ) ):
			$recipe_settings['gallery']['video_url'] = $c2_recipe_settings['_cp_recipe_external_video'];
		else:
			$recipe_settings['gallery']['video_url'] = false;
			$recipe_settings['gallery']['type'] = 'cooked';
		endif;

		$recipe_settings['nutrition']['serving_size'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_servingsize']) && $c2_recipe_settings['_cp_recipe_nutrition_servingsize'] ? $c2_recipe_settings['_cp_recipe_nutrition_servingsize'] : false );
		$recipe_settings['nutrition']['servings'] = ( isset($c2_recipe_settings['_cp_recipe_yields']) && $c2_recipe_settings['_cp_recipe_yields'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_yields']) : false );
		$recipe_settings['nutrition']['calories'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_calories']) && $c2_recipe_settings['_cp_recipe_nutrition_calories'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_calories']) : false );
		$recipe_settings['nutrition']['fat'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_fat']) && $c2_recipe_settings['_cp_recipe_nutrition_fat'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_fat']) : false );
		$recipe_settings['nutrition']['sat_fat'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_satfat']) && $c2_recipe_settings['_cp_recipe_nutrition_satfat'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_satfat']) : false );
		$recipe_settings['nutrition']['trans_fat'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_transfat']) && $c2_recipe_settings['_cp_recipe_nutrition_transfat'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_transfat']) : false );
		$recipe_settings['nutrition']['cholesterol'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_cholesterol']) && $c2_recipe_settings['_cp_recipe_nutrition_cholesterol'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_cholesterol']) : false );
		$recipe_settings['nutrition']['sodium'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_sodium']) && $c2_recipe_settings['_cp_recipe_nutrition_sodium'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_sodium']) : false );
		$recipe_settings['nutrition']['potassium'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_potassium']) && $c2_recipe_settings['_cp_recipe_nutrition_potassium'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_potassium']) : false );
		$recipe_settings['nutrition']['carbs'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_carbs']) && $c2_recipe_settings['_cp_recipe_nutrition_carbs'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_carbs']) : false );
		$recipe_settings['nutrition']['fiber'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_fiber']) && $c2_recipe_settings['_cp_recipe_nutrition_fiber'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_fiber']) : false );
		$recipe_settings['nutrition']['sugars'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_sugar']) && $c2_recipe_settings['_cp_recipe_nutrition_sugar'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_sugar']) : false );
		$recipe_settings['nutrition']['protein'] = ( isset($c2_recipe_settings['_cp_recipe_nutrition_protein']) && $c2_recipe_settings['_cp_recipe_nutrition_protein'] ? preg_replace("/[^0-9]/","",$c2_recipe_settings['_cp_recipe_nutrition_protein']) : false );

		$_nutrition_facts = Cooked_Measurements::nutrition_facts();
		foreach( $_nutrition_facts as $nutrition_facts ):
			foreach( $nutrition_facts as $slug => $nf ):

				if ( !isset($recipe_settings[$slug]) ): $recipe_settings[$slug]	= false; endif;
				if ( isset($nf['subs']) ):
					foreach( $nf['subs'] as $sub_slug => $sub_nf ):
						if ( !isset($recipe_settings[$sub_slug]) ): $recipe_settings[$sub_slug]	= false; endif;
					endforeach;
				endif;
			endforeach;
		endforeach;

		return apply_filters( 'cooked_sync_c2_recipe_settings', $recipe_settings, $recipe_id );

	}

}

global $Cooked_Recipes;
$Cooked_Recipes = new Cooked_Recipes();
