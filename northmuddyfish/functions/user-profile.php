<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Changes to the User Profile
**/

add_filter('user_contactmethods', 'theme_add_new_contactmethods', 10, 1);
function theme_add_new_contactmethods( $contact_methods ){

    // Add Author Profile Fields
    $contact_methods['phone'] = 'Phone';
    
    // Remove social contact methods
	unset( $contact_methods['facebook'] );
	unset( $contact_methods['pinterest'] );
	unset( $contact_methods['wikipedia'] );
	unset( $contact_methods['instagram'] );
	unset( $contact_methods['soundcloud'] );
	unset( $contact_methods['tumblr'] );
	unset( $contact_methods['twitter'] );
	unset( $contact_methods['linkedin'] );
	unset( $contact_methods['youtube'] );
	unset( $contact_methods['myspace'] );
	return $contact_methods;
}

/**
 * @link https://developer.wordpress.org/reference/hooks/admin_print_scripts-hook_suffix/
**/
if( !function_exists( 'shenk_hide_profile_fields' )){
	function shenk_hide_profile_fields() { ?>
		<style>
		.user-url-wrap,						<?php // Website ?>
		/* .user-syntax-highlighting-wrap,	<?php // Syntax Highlighting ?> */
		.user-comment-shortcuts-wrap,		<?php // Keyboard Short ?>
		.user-additional_profile_urls-wrap,	<?php // Additional profile URLs ?>
		.user-description-wrap,				<?php // Biographical Info ?>
		.user-profile-picture				<?php // Profile Picture ?>
		{
			display: none;
		}
		</style>

		<?php if( class_exists( 'ACF' ) ){
			/**
			 * Move ACF fields in the admin
			 * 
			 * @internal Tip, use the TR for targeting. The table itself is usually generic.
			 * 
			 * @param object move	 	Element to be moved.
			 * @param object parent		Where the element is to be moved.
			 * @param string position	Position the new element. Default is before.
			 * 								[before, after, ins_before]
			 */
			?>
			<script type="module">
				let profile_fields = [
					{
						move: 		document.querySelector('tr[data-name="display_contact_info"]'),
						parent: 	document.querySelector('tr.user-phone-wrap'),
						position:	'after'
					},
					{
						move: 		document.querySelector('tr[data-name="positions-title"]'),
						parent: 	document.querySelector('tr.user-description-wrap'),
						position:	'before'
					},
					{
						move: 		document.querySelector('tr[data-name="user_description"]'),
						parent: 	document.querySelector('tr.user-profile-picture'),
						position:	'before'
					},
					{
						move: 		document.querySelector('#simple-local-avatar-section'),
						parent: 	document.querySelector('#application-passwords-section'),
						position:	'ins_before'
					}
				];

				profile_fields.forEach((element) => {
					if( ( element.move.nodeName !== null || element.move.nodeName !== undefined ) && ( element.parent.nodeName !== null || element.parent.nodeName !== undefined ) ){
						if( element.position !== 'before' && element.position !== 'after' && element.position !== 'ins_before' ){
							element.position = 'before';
						}

						if( element.position === 'after' ){
							element.parent.after(element.move);
						} else if( element.position === 'before' ){
							element.parent.before(element.move);
						} else if( element.position === 'ins_before' ){
							element.parent.parentNode.insertBefore( element.move, element.parent );
						}
					}
				});
			</script>
			<?php
		}
	}
	add_action('admin_init', function() {
		add_action( 'admin_print_scripts-profile.php', 'shenk_hide_profile_fields' );
		add_action( 'admin_print_scripts-user-edit.php', 'shenk_hide_profile_fields' );
	} );
}

/**
 * @package		Display the user's name in 2 formats
 * 
 * Display the user's name by their Display Name, chosen inside their profile, which is default.
 * Otherwise, we override this and get their first and last name if they exist.
 *
 * @param  mixed  $user_id 		The user ID or object. Default is current user
 * @param  bool	  $display_name Display Format
 * @return string          		The user's name
 */
function get_users_name( $user_id = null, $display_name = true ) {
	$user_info = $user_id ? new WP_User( $user_id ) : wp_get_current_user();

	if( $display_name ){
		return $user_info->display_name;

	} else {
		if( ! empty($user_info->first_name) ){
			if( ! empty($user_info->last_name) ){
				return $user_info->first_name . ' ' . $user_info->last_name;
			}
	
			return $user_info->first_name;
		}
	}
}

// Add phone number to "New User" WP admin
add_action( 'user_new_form', 'shenk_theme_contact_fields' );
function shenk_theme_contact_fields(){
	?>
	<table class="form-table">
		<tr>
			<th><label for="phone">Phone</label></th>
	 		<td>
				<input type="text" name="phone" id="phone" value="" class="regular-text">
			</td>
		</tr>
	</table>
	<?php
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * User Profile Taxonomy
 */

// Register Taxonomy
add_action( 'init', 'shenk_register_user_taxonomy', 0 );
function shenk_register_user_taxonomy() {
	$singular = 'Position';
	$plural = 'Positions';
	$cpt_to_apply = 'user';

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
		// 'description'			=> '',
		'public'				=> true,			// Whether it's intended for use publicly either via the admin interface or by front-end users
		'publicly_queryable'    => false,			// Queries can be performed on the front end for the post type as part of parse_request()
		'show_ui'				=> true,			// Whether to generate and allow a UI for managing this post type in the admin
		'show_admin_column'		=> true,			// Show a column for the taxonomy on its post type listing screens
		'show_in_rest'			=> true,			// Block editor
		'hierarchical'			=> true,			// True allows parent and child pages  (page-attributes must be enabled)
		// 'rewrite'				=> array(			// Customize the URL
		// 	'slug'			=> sanitize_title($singular),
		// 	'with_front'	=> true
		// ),
		'query_var'				=> false,			// Sets the query_var key for this post type
		'show_tagcloud'     	=> false,			// Whether to list the taxonomy in the Tag Cloud Widget controls
		// 'show_in_quick_edit'	=> true,			// Whether to show the taxonomy in the quick/bulk edit panel
	);
	register_taxonomy( sanitize_title($plural), array($cpt_to_apply), $args );
}

// Admin page for the positions taxonomy
add_action( 'admin_menu', 'shenk_add_positions_taxonomy_admin_page' );
function shenk_add_positions_taxonomy_admin_page(){
	$tax = get_taxonomy( 'positions' );
	add_users_page(
    	esc_attr( $tax->labels->menu_name ),
    	esc_attr( $tax->labels->menu_name ),
    	$tax->cap->manage_terms,
    	'edit-tags.php?taxonomy=' . $tax->name
	);
}

// Update parent file name to fix the selected menu issue
add_filter('parent_file', 'shenk_change_parent_file');
function shenk_change_parent_file( $parent_file ){
    global $submenu_file;
    if (
        isset($_GET['taxonomy']) && 
        $_GET['taxonomy'] == 'positions' &&
        $submenu_file == 'edit-tags.php?taxonomy=positions'
    ) 
    $parent_file = 'users.php';
    return $parent_file;
}

/** @param string $username The username of the user before registration is complete */
add_filter( 'sanitize_user', 'shenk_disable_positions_username' );
function shenk_disable_positions_username( $username ){
	if ( 'positions' === $username ){
    	$username = '';
	}
  	return $username;
}

// Unset the 'posts' column and adds a 'users' column on the manage positions admin page
add_filter( 'manage_edit-positions_columns', 'shenk_manage_positions_user_column' );
function shenk_manage_positions_user_column( $columns ){
	unset( $columns['posts'] );
	$columns['users'] = __( 'Users' );
	return $columns;
}

/**
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
add_filter( 'manage_positions_custom_column', 'shenk_manage_positions_column', 10, 3 );
function shenk_manage_positions_column( $display, $column, $term_id ){
	if ( 'users' === $column ) {
    	$term = get_term( $term_id, 'positions' );
    	echo $term->count;
	}
}
