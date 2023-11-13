<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Support for various plugins
**/

# Contact Form 7 - CF7
if( defined( 'WPCF7_VERSION' ) ){
	# Removes  <p>  tags from being output
	add_filter('wpcf7_autop_or_not', '__return_false');

	# The form class
	add_filter('wpcf7_form_class_attr', function($html_class) {
		return $html_class;
	});

	# Disable loading of JavaScript and CSS
	add_filter( 'wpcf7_load_js', '__return_false' );
	add_filter( 'wpcf7_load_css', '__return_false' );

	# Enable loading of JavaScript and CSS
	// if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
	// 	wpcf7_enqueue_scripts();
	// }
	// if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
	// 	wpcf7_enqueue_styles();
	// }
}

add_filter( 'document_library_pro_enable_single_content_customization', '__return_false' );

# The Events Calendar Plugin Support
// if( function_exists('tribe_classes') ){}

# Advanced Custom Fields - ACF
if( class_exists( 'ACF' ) ){

	# Disable post type and taxonomy creation
	add_filter( 'acf/settings/enable_post_types', '__return_false' );

	# Hide ACF Settings Menu
	// add_filter( 'acf/settings/show_admin', '__return_false' );
}

# Easy Media Replace
if( defined( 'EMR_VERSION' ) ){
	
	# Disable background removal feature
	add_filter( 'emr/feature/background', '__return_false' );
}


/**
 * Add custom admin notice about a required plugin
 */
add_action('admin_init', 'shenk_theme_warn_required_plugins_init');
function shenk_theme_warn_required_plugins_init(){

	// Bail if user doesn't have permissions
	if( ! current_user_can('manage_options') ){
		return false;
	}

	// Simple Local Avatars
    if ( !defined('SLA_VERSION') ){
        add_action('admin_notices', function(){ echo '
			<div class="notice notice-error is-dismissible">
				<p>Simple Local Avatars plugin is not active. Please 
				<a href="'. home_url( "/wp-admin/plugins.php" ) .'">re-activate it</a>
				, your site requires it.</p>
			</div>';
		});
    }

	// Advanced Custom Fields
    if ( !class_exists( 'ACF' ) ){
        add_action('admin_notices', function(){ echo '
			<div class="notice notice-error is-dismissible">
				<p>The Advanced Custom Fields plugin is not active. Please 
				<a href="'. home_url( "/wp-admin/plugins.php" ) .'">re-activate it</a>
				, your site requires it.</p>
			</div>';
		});
    }

}
