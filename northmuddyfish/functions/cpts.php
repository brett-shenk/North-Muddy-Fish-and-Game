<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Custom Post Types and Taxonomies
 * 
 * More Info At:
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
**/

# Custom Post Types
add_action( 'init', 'shenk_register_cpts' );

# Custom Taxonomies
add_action( 'init', 'shenk_register_taxonomy' );

function shenk_register_cpts() {

	# Start Post Type
	$singular = 'Slide';
	$plural = 'Slides';

	$args = array(
		'label'					=> $plural,			// Name of the post type
		'labels'				=> 	array(
			'name'					=> $plural,
			'singular_name'			=> $singular,
			'menu_name'				=> $plural,
			'name_admin_bar'		=> $singular,
			'all_items'				=> 'All '.$plural,
			'add_new'				=> 'Add New',
			'add_new_item'			=> 'Add New '.$singular,
			'edit_item'				=> 'Edit '.$singular,
			'new_item'				=> 'New '.$singular,
			'view_item'				=> 'View '.$singular,
			'search_items'			=> 'Search '.$plural,
			'not_found'				=> 'No '.$plural.' found',
			'not_found_in_trash'	=> 'No '.$plural.' found in Trash',
			'parent_item_colon'		=> 'Parent '.$singular.':',
			'update_item'			=> 'Update '.$singular,
			'items_list'			=> $plural.' list',
			'items_list_navigation'	=> $plural.' list navigation',
			'filter_items_list'		=> 'Filter '.$plural.' list',
			'parent' 				=> 'Parent '.$singular,
		),
		'description'			=> '',
		'public'				=> false,			// False disables yoast + slug
		'exclude_from_search'	=> true,			// Included in search results and custom queries
		'publicly_queryable'    => false,			// Queries can be performed on the front end for the post type as part of parse_request()
		'show_ui'				=> true,			// Whether to generate and allow a UI for managing this post type in the admin
		'show_in_nav_menus'     => false,			// Appearance > Menus  (Adds a select list of all pages)
		'show_in_menu'			=> true,			// Left admin bar menu
		'show_in_admin_bar'     => true,			// Horizontal admin bar > New
		'show_in_rest'			=> true,			// Block editor
		'menu_position'         => 4,				// Use decimal numbers to not override anything
		'menu_icon'				=> 'none',			// https://developer.wordpress.org/resource/dashicons/
		'hierarchical'			=> true,			// True allows parent and child pages  (page-attributes must be enabled)
		'supports'				=> array(
			'title',
			'editor',								// Content, enables the block editor
			// 'author',							// Author of the post
			// 'thumbnail',							// Featured image (theme must also support post-thumbnails)
			// 'excerpt',							// Short description of the post
			// 'trackbacks',
			// 'custom-fields',
			// 'comments',							// Also will see comment count balloon on edit screen
			// 'revisions',							// Will store revisions
			// 'page-attributes',					// Menu order, hierarchical must be true to show Parent option
			// 'post-formats'						// Add post formats, see Post Formats
		),
		'has_archive'			=> false,			// A post type archive page
		'taxonomies'			=> array(),
		'rewrite'				=> array(			// Customize the URL
			'slug'			=> sanitize_title($singular),
			'with_front'	=> true,
			'pages'         => true
		),
		// 'capability_type'		=> array(),		// String to use to build the read, edit, and delete capabilities
		'query_var'				=> false,			// Sets the query_var key for this post type
		'can_export'            => true,			// Whether to allow this post type to be exported
		'delete_with_user'		=> null,			// Whether to delete posts of this type when deleting a user
		// 'template' 				=> array(),
		'template_lock'			=> false			// Whether the block template should be locked  [all, insert]
	);
	// register_post_type( sanitize_title($singular), $args );
	# End Post Type
}
function shenk_register_taxonomy() {

	# Start Taxonomy
	$singular = 'Type';
	$plural = 'Types';
	$cpt_to_apply = 'case-study';

	$args = array(
		'label'					=> $plural,			// Name of the post type
		'labels'				=> array(
			'name'							=> $plural,
			'singular_name'					=> $singular,
			'menu_name'						=> $plural,
			'all_items'						=> 'All '.$plural,
			'edit_item'						=> 'Edit '.$singular,
			'view_item'						=> 'View '.$singular,
			'update_item'					=> 'Update '.$singular.' Name',
			'add_new_item'					=> 'Add New '.$singular,
			'new_item_name'					=> 'New '.$singular.' Name',
			'parent_item'					=> 'Parent '.$singular,
			'parent_item_colon'				=> 'Parent '.$singular.':',
			'search_items'					=> 'Search '.$plural,
			'popular_items'					=> 'Popular '.$plural,
			'separate_items_with_commas'	=> 'Separate '.$plural.' with commas',
			'add_or_remove_items'			=> 'Add or remove '.$plural,
			'choose_from_most_used'			=> 'Choose from the most used '.$plural,
			'not_found'						=> 'No '.$plural.' found',
		),
		'description'			=> '',
		'public'				=> true,			// Whether it's intended for use publicly either via the admin interface or by front-end users
		'publicly_queryable'    => false,			// Queries can be performed on the front end for the post type as part of parse_request()
		'show_ui'				=> true,			// Whether to generate and allow a UI for managing this post type in the admin
		'show_in_nav_menus'     => false,			// Appearance > Menus  (Adds a select list of all pages)
		'show_in_menu'			=> true,			// Left admin bar menu
		'show_admin_column'		=> true,			// Show a column for the taxonomy on its post type listing screens
		'show_in_rest'			=> true,			// Block editor
		'hierarchical'			=> true,			// True allows parent and child pages  (page-attributes must be enabled)
		'rewrite'				=> array(			// Customize the URL
			'slug'			=> sanitize_title($singular),
			'with_front'	=> true
		),
		'query_var'				=> false,			// Sets the query_var key for this post type
		'show_tagcloud'     	=> false,			// Whether to list the taxonomy in the Tag Cloud Widget controls
		'show_in_quick_edit'	=> true,			// Whether to show the taxonomy in the quick/bulk edit panel
		// 'capabilities'		=> array(),			// String to use to build the read, edit, and delete capabilities
		// 'default_term'		=> array()			// Default term to be used for the taxonomy
	);
	// register_taxonomy( sanitize_title($singular), array($cpt_to_apply), $args );
	# End Taxonomy
}

# Disable Fullscreen Editor by default
add_filter('wp_editor_expand', 'deregister_editor_expand', 10, 2);
function deregister_editor_expand($val, $post_type) {
	$disabled_post_types = array('page', 'post');
	return !in_array($post_type, $disabled_post_types);
}
