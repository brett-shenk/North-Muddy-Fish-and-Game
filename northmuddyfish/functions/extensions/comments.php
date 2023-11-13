<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Disables Comments
 * 
 * @author 		Brett Shenk
 * @package 	Disable Comments
 * @version 	1.1.0
 * @internal 	Removes functionality related to Comments. Such as dashboard widgets, page meta boxes, cleaning
 * 		  	 	up the left and top admin bars. To enable comments again, just delete this file.
**/
add_action( 'admin_init', function() {

	# Remove Comments Dashboard Widgets
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

	# Removes Comments From Posts
	remove_meta_box('commentstatusdiv', 'post', 'normal');
	remove_meta_box('commentsdiv', 'post', 'normal');

	# Removes Comments From Pages
	remove_meta_box('commentstatusdiv', 'page', 'normal');
	remove_meta_box('commentsdiv', 'page', 'normal');

	# The Events Calendar Plugin Support
	if( function_exists('tribe_classes') ){
		remove_meta_box('commentstatusdiv', 'tribe_events', 'normal');
		remove_meta_box('commentsdiv', 'tribe_events', 'normal');
	}
});

# Remove Comments From left Admin Bar
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
});

# Removes Comments from Top Admin Bar
add_action( 'admin_menu', function(){
	remove_menu_page( 'edit-comments.php' ); 
}, 998 );

# Clean up Columns
add_action('admin_init', function () {

	// Posts
	add_filter('manage_posts_columns', function ( $columns ) {
		unset( $columns['comments'] );
		return $columns;
	});

	// Pages
	add_filter('manage_pages_columns', function ( $columns ) {
		unset( $columns['comments'] );
		return $columns;
	});
});

# Set Comment Count to Always be Zero
add_filter(	'wp_count_comments', function($count) {
	return (object) array(
		'approved'       => 0,
		'spam'           => 0,
		'trash'          => 0,
		'post-trashed'   => 0,
		'total_comments' => 0,
		'all'            => 0,
		'moderated'      => 0,
	);
});

# Removes the Default Fields in the Comment Form
add_filter( 'comment_form_default_fields', function ($fields) {
	unset( $fields['author'] );
	unset( $fields['email'] );
	unset( $fields['url'] );
	return $fields;
});
