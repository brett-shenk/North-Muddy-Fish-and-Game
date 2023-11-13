<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Theme Setup
**/

# Add Theme Support
add_action('after_setup_theme','theme_setup');
function theme_setup(){

    # Tag Title
    add_theme_support( 'title-tag' );

    # Menu Support
    add_theme_support('menus');

    # Post Formats
    // $post_formats = array('aside', 'image', 'gallery', 'video', 'audio', 'link', 'quote', 'status');
    // add_theme_support( 'post-formats', $post_formats);

    # HTML5 Support [script/style removes the type attribute]
    add_theme_support( 'html5', array( 'search-form', 'caption', 'script', 'style' ) );

    # Remove Widget Block Editor
    // remove_theme_support( 'widgets-block-editor' );

    # Editor Styles
    add_theme_support( 'editor-styles' );

    # Block Styles
    add_theme_support( 'wp-block-styles' );

    # Built in block patterns
    // remove_theme_support( 'core-block-patterns' );
}

# Register Menus
add_action( 'init', function(){
    register_nav_menu('primary-navigation', 'Primary Menu' );
    register_nav_menu('my_account', 'My Account Menu' );
    register_nav_menu('copyright_bar', 'Copyright Menu' );
} );

# Add Sidebars
add_action( 'widgets_init', 'shenk_register_sidebars' );
function shenk_register_sidebars(){
    widget_registration('Footer Column 1', 'footer_column_one', 'Left Footer Column Widget Area');
    widget_registration('Footer Column 2', 'footer_column_two', 'Center Footer Column Widget Area');
    widget_registration('Footer Column 3', 'footer_column_three', 'Right Footer Column Widget Area');
    widget_registration('Club News Category Sidebar', 'post_archive_sidebar', 'Club News Category Sidebar');
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Default Post Setup
**/

# Remove Edit Link
add_filter( 'edit_post_link', '__return_false' );

# Set width of iframes
if(!isset($content_width)){ $content_width = 600; }

# The excerpt
add_post_type_support('page', 'excerpt');

# The excerpt length
function my_excerpt_length($length){ return 60; }
add_filter('excerpt_length', 'my_excerpt_length');

# Change default excerpt ending [...]
add_filter('excerpt_more', 'new_excerpt_more');
function new_excerpt_more( $more ) {
	return '...';
}

# Post Thumbnail
add_theme_support('post-thumbnails');
set_post_thumbnail_size(600, 9999);

# Remove post thumbnail from...
add_action('init','remove_thumbnail_support');
function remove_thumbnail_support(){
    remove_post_type_support('page','thumbnail');
}

# Clean up the admin left sidebar
/* add_action( 'admin_menu', function(){    
	remove_menu_page( 'edit.php' );                                          // default post
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');      // category post
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');      // taxonomy post
}, 998 ); */


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package			Simple User Access
 * 
 * 
 * @author			Brett Shenk
 * @version			1.0
 * @copyright	 	2023
 * 
 * @param init      [0, 1, 2, 3] What role(s) should be allowed?
 * @return boolean  [true, false] Returns true if the user is allowed access, otherwise it returns false
 * @note            Updating theses roles will update it site wide
**/
function allow_user( $level ){
    $allow = false;

    if( is_user_logged_in() ) {
        $user = wp_get_current_user();

        switch( $level ){
            case 0:
                $roles = array('administrator');
                break;
            case 1:
                $roles = array('administrator', 'an_administrator');
                break;
            case 2:
                $roles = array('administrator', 'an_administrator', 'editor');
                break;
            case 3:
                $roles = array('administrator', 'an_administrator', 'editor', 'author');
                break;
        }

        foreach($roles as $role){
            if( in_array($role, $user->roles) ){
                $allow = true;
                break;
            }
        }
    }

    return $allow;
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Theme Activation
**/

// This action is only triggered when a theme is activated / switched
// add_action('after_switch_theme', 'shenk_after_theme_switch_options');
function shenk_after_theme_switch_options() {

    $option_name = 'shenk_activate_theme' ;

    // Check if our option has been set. If it is, this isn't the first time being activated
    if ( get_option( $option_name ) !== false ) {
        update_option( $option_name, 'true' );

    // The option hasn't been created yet so it must be the first time being activated
    } else {

        // Add the option
        add_option( $option_name, 'true', null, 'no' );

        // Remove the default page
        $defaultPage = get_page_by_title( 'Sample Page' );
        wp_delete_post( $defaultPage->ID, $bypass_trash = true );

        // Remove the default post
        $defaultPost = get_posts( array( 'title' => 'Hello World!' ) );
        wp_delete_post( $defaultPost[0]->ID, $bypass_trash = true );

        // Set the permalink structure to our default
        // This can be anything that's placed in the  `Custom Structure`  output
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        
        // Flush the rewrite rules to make sure we have no problems with the permalink update
        flush_rewrite_rules();

        // Tell the user that something happened
        add_action('admin_notices', 'shenk_activate_admin_notice');
        function shenk_activate_admin_notice() { ?>
            <div class="notice notice-success is-dismissible">
                <p>First time setup has been ran, enjoy!</p>
            </div>
        <?php }
    }
}
