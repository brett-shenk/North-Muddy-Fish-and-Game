<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package 		Duplicate pages and posts without a plugin
 * 
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2021 - 2023
 * @link            https://rudrastyh.com/wordpress/duplicate-post.html
 * @link			https://rudrastyh.com/wordpress/custom-bulk-actions.html
 * 
 * @version 	 	2.0
 * @contributors	Brett Shenk
 * 					Misha Rudrastyh
**/

// Admin only
if( is_admin() ){

	// Single Edit
	add_filter( 'post_row_actions', 'shenk_duplicate_post_link', 10, 2 );      // "post" and custom post types
	add_filter( 'page_row_actions', 'shenk_duplicate_post_link', 10, 2 );      // "page" post type

	add_action( 'admin_action_shenk_duplicate_post_as_draft', 'shenk_duplicate_post_as_draft' );
	add_action( 'admin_notices', 'shenk_duplication_admin_notice' );

	// Bulk Edit
	add_action( 'bulk_actions-edit-post', 'shenk_bulk_duplicate_title' );
	add_action( 'bulk_actions-edit-page', 'shenk_bulk_duplicate_title' );

	add_action( 'handle_bulk_actions-edit-post', 'shenk_bulk_action_handle', 10, 3 );
	add_action( 'handle_bulk_actions-edit-page', 'shenk_bulk_action_handle', 10, 3 );
}

/**
 * Add the duplicate link to action list for post_row_actions
 */
function shenk_duplicate_post_link( $actions, $post ) {

	// Should the user be allowed?
	if( ! current_user_can( 'edit_posts' ) ) {
		return $actions;
	}

	$url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'shenk_duplicate_post_as_draft',
				'post' => $post->ID,
			),
			'admin.php'
		),
		basename(__FILE__),
		'duplicate_nonce'
	);

	$actions[ 'duplicate' ] = '<a href="' . $url . '" title="Duplicate this post" rel="permalink">Duplicate</a>';

	return $actions;
}

/**
 * Single post duplication
**/
function shenk_duplicate_post_as_draft(){

	// Check if post ID has been provided and action
	if ( empty( $_GET[ 'post' ] ) ) {
		wp_die( 'No post to duplicate has been provided!' );
	}

	// Nonce verification
	if ( ! isset( $_GET[ 'duplicate_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'duplicate_nonce' ], basename( __FILE__ ) ) ) {
		return;
	}

	// Variable Setup
	$post_id = absint( $_GET[ 'post' ] );
	$post = get_post( $post_id );

	// The core function
	shenk_duplicate_post_core( $post );

	// Redirect to all posts with a message
	if ( $post ) {
		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => ( 'post' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
					'saved' => 'post_duplication_created' // custom slug
				),
				admin_url( 'edit.php' )
			)
		);
		exit;
	}
}

/**
 * Add to Bulk Actions
 */
function shenk_bulk_duplicate_title( $bulk_array ){
	$bulk_array[ 'shenk_duplicate' ] = 'Duplicate';
	return $bulk_array;
}

/**
 * Process the bulk action
 */
function shenk_bulk_action_handle( $redirect, $action, $object_ids ){

	// Remove query args first
	$redirect = remove_query_arg( array( 'shenk_duplicate' ), $redirect );

	// Do something for the "Duplicate" bulk action
	if ( 'shenk_duplicate' === $action ) {
		foreach ( $object_ids as $post_id ){

			// Variable setup
			$post = get_post( $post_id );

			// The core function
			shenk_duplicate_post_core( $post );
		}

		// Redirect after finished with the selected parameters
		$redirect = add_query_arg(
			'shenk_duplicate', 		// Parameter for URL
			count( $object_ids ), 	// Number of posts selected
			$redirect
		);
	}

	return $redirect;
}

/**
 * Core function to duplicate a page / post
 */
function shenk_duplicate_post_core( $post ){
	if ( $post ) {
		
		$post_id = $post->ID;

		// New post data array
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $post->post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		// Insert the post by wp_insert_post() function
		$new_post_id = wp_insert_post( $args );

		// Get all current post terms and set them to the new draft post(s)
		$taxonomies = get_object_taxonomies( get_post_type( $post ) ); // Returns array of taxonomy names for post type, ex array("category", "post_tag");
		if( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}
		}

		// Duplicate all post meta
		$post_meta = get_post_meta( $post_id );
		if( $post_meta ) {
			foreach ( $post_meta as $meta_key => $meta_values ) {

				if( '_wp_old_slug' == $meta_key ) {
					// Do nothing for this meta key
					continue;
				}

				foreach ( $meta_values as $meta_value ) {
					add_post_meta( $new_post_id, $meta_key, $meta_value );
				}
			}
		}

	} else {
		wp_die( 'Post creation failed, could not find original post.' );
	}
}

/**
 * Admin notice Single
**/
function shenk_duplication_admin_notice() {

	// Get the current screen
	$screen = get_current_screen();

	if ( 'edit' !== $screen->base ) {
		return;
	}

    // Checks if settings updated
    if ( isset( $_GET[ 'saved' ] ) && 'post_duplication_created' == $_GET[ 'saved' ] ) {
		echo '<div class="notice notice-success is-dismissible"><p>Post has been duplicated.</p></div>';
    }
}

/**
 * Admin notice Bulk Edit
**/
add_action( 'admin_notices', 'shenk_bulk_duplicate_notices' );
function shenk_bulk_duplicate_notices() {
	if( ! empty( $_REQUEST[ 'shenk_duplicate' ] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>Posts have been duplicated.</p></div>';
	}
}
