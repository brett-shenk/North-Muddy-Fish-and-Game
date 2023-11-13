<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Clean up the Header and CSS / JS
**/

# This cleans up  wp_head()  function
add_action( 'wp_enqueue_scripts', function(){

	# Remove WordPress Icons if not logged in
	if (!is_user_logged_in()) {
        wp_dequeue_style( 'dashicons' );
    }
	
	# Remove EditURI
	remove_action ('wp_head', 'rsd_link');

	# Remove Windows Live Writer
	remove_action( 'wp_head', 'wlwmanifest_link');

	# Remove the general feeds
	remove_action( 'wp_head', 'feed_links', 2 );

	# Remove the extra feeds, such as category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	# Remove the displayed XHTML generator
	remove_action( 'wp_head', 'wp_generator' );

	# Remove rel next/prev links
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
	
	# Remove shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head');

	# Removes WordPress DNS Prefetch
	add_filter( 'emoji_svg_url', '__return_false' );

	# Remove dns-prefetch
    // remove_action( 'wp_head', 'wp_resource_hints', 2 );
}, 100 );

# Remove JQuery migrate
add_action('wp_default_scripts', 'shenk_jquery_migrate');
function shenk_jquery_migrate($scripts){
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
		// Check whether the script has any dependencies
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}

# Remove All WP Emojicons
function stupid_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
function stupid_emojis() {
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	add_filter( 'tiny_mce_plugins', 'stupid_emojis_tinymce' );
}
add_action( 'init', 'stupid_emojis' );

/** 
 * Disable XML-RPC
 * @see https://www.wpbeginner.com/plugins/how-to-disable-xml-rpc-in-wordpress/
**/
add_filter('xmlrpc_enabled', '__return_false');

# Disable Rankmath search query
if( function_exists('rank_math_the_breadcrumbs') ){
	add_filter( 'rank_math/json_ld/disable_search', '__return_true' );
}


/**
 * Other Tweaks
**/

# Remove invalid HTML from iframes & remove Do Not Track
add_filter( 'embed_oembed_html', 'gp_remove_frameborder', 10, 2 );
function gp_remove_frameborder( $html, $url ) {
    if ( strpos( $url, 'youtube.com' ) !== false ) {
        $html = str_replace( 'frameborder="0"', '', $html );
		$html = str_replace( 'dnt=1', '', $html );
    }
	if ( strpos( $url, 'vimeo.com' ) !== false ) {
		$html = str_replace( 'allowfullscreen', '', $html );
		$html = str_replace( 'dnt=1', '', $html );
	}

    return $html;
}

# Remove duotone SVG's from the header
add_action('after_setup_theme', 'remove_svg_filter_styles', 10);
function remove_svg_filter_styles() {
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );
}


/**
 * Clean up the Admin
**/

# Site Health API
add_filter( 'site_status_tests', 'prefix_remove_php_test' );
function prefix_remove_php_test( $tests ) {
	unset( $tests['direct']['theme_version'] );								// Remove error of only one theme
	return $tests;
}

# Remove dashboard widgets
add_action( 'admin_init', function() {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );					// Welcome panel
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );			// WordPress News
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );		// Quick Draft
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');			// Activity

	if( function_exists('tribe_classes') ){
		remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal');	// The Event Calendar
	}
});

# Widgets
// add_action( 'widgets_init', 'remove_widgets' );
function remove_widgets() {
    unregister_widget('WP_Widget_Categories');      		// Categories Widget
    unregister_widget('WP_Widget_Recent_Posts');    		// Recent Posts Widget
    unregister_widget('WP_Widget_Recent_Comments'); 		// Recent Comments Widget
    unregister_widget('WP_Widget_Tag_Cloud' );      		// Tag Cloud
    unregister_widget('WP_Widget_RSS');             		// RSS Widget
    unregister_widget('WP_Widget_Archives');        		// Archives Widget
    unregister_widget('WP_Widget_Calendar');        		// Calendar Widget
    unregister_widget('WP_Widget_Media_Audio');     		// Audio Player Media Widget
    unregister_widget('WP_Widget_Media_Gallery');   		// Gallery Media Widget
    unregister_widget('WP_Widget_Media_Video' );    		// Video Widget
    unregister_widget('WP_Widget_Meta');            		// Meta Widget
    unregister_widget('WP_Widget_Custom_HTML');     		// Custom HTML
    unregister_widget('WP_Widget_Media_Image');     		// Image
    unregister_widget('WP_Nav_Menu_Widget');        		// Navigation Menu
    unregister_widget('WP_Widget_Pages');           		// Pages
    unregister_widget('WP_Widget_Search');          		// Search
    unregister_widget('WP_Widget_Text');            		// Text
}


/**
 * Clean Up The Menus
**/

# Outputs all menu items to help organize the admin
// add_action( 'admin_menu', 'all_admin_links', 999 );
function all_admin_links(){
	global $submenu;
	echo "<pre>";
	var_dump( $submenu );
	echo "</pre>";
	echo "<style>#wpwrap { display: none!important; }</style>";
}

add_action( 'admin_menu', function(){

	# Pages
	remove_menu_page( 'edit.php?post_type=page' );
	add_menu_page('page-title', 'Pages', 'manage_options', 'edit.php?post_type=page', null, 'dashicons-admin-page', 4.1);

	# Appearance
	remove_submenu_page( 'themes.php', 'customize.php?return=' . urlencode( $_SERVER['REQUEST_URI'] ) );	// Fallback method only
	remove_submenu_page( 'themes.php', 'theme-editor.php' );												// Theme Editor
	remove_submenu_page( 'plugins.php', 'plugin-editor.php' );												// Plugin editor
	// remove_submenu_page( 'themes.php', 'widgets.php' );													// Widgets

	# Tools
	remove_submenu_page( 'tools.php', 'tools.php' );
	if( current_user_can('administrator') || current_user_can('an_administrator') ){
		add_submenu_page('index.php', 'About WordPress', 'About WordPress', 'manage_options', 'about.php', null, 5);
	}
	if( ! current_user_can('administrator') ){
		remove_submenu_page( 'index.php', 'update-core.php' );												// Updates tab
		remove_menu_page( 'edit.php?post_type=acf-field-group' );											// ACF Settings
	}

	# Meta box Removals
	// remove_meta_box( 'authordiv', 'page', 'normal' );			// author
}, 999 );

# Completely remove Editor and customize
add_action( 'admin_menu', function () {
	global $submenu;
	if ( isset( $submenu[ 'themes.php' ] ) ) {
		foreach ( $submenu[ 'themes.php' ] as $index => $menu_item ) {
			foreach ($menu_item as $value) {
				if (strpos($value,'customize') !== false) {
					unset( $submenu[ 'themes.php' ][ $index ] );
				}
			}
		}
	}
});
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('customize');			// Customize
	$wp_admin_bar->remove_menu('wp-logo');				// WP Logo
	$wp_admin_bar->remove_menu( 'new-user' );         	// New User
	$wp_admin_bar->remove_menu( 'new-media' );         	// New Media
});


/**
 * Media Library
**/

# Remove actions from Media Upload. Does not disable it completely
add_filter( 'media_view_strings', function ($strings){
	$strings['createGalleryTitle']       = null; 		    // Remove "Create gallery" button
	$strings['createPlaylistTitle']      = null; 		    // Remove "Create Audio Playlist" button
	$strings['createVideoPlaylistTitle'] = null; 		    // Remove "Create Video Playlist" button

	return $strings;
});

# Hide input fields when uploading or editing media
add_action('admin_print_scripts', function(){ ?>
    <style>
	.attachment-details .setting[data-setting="description"],		<?php // Media description ?>
	.attachment-details .setting[data-setting="artist"],    		<?php // Audio artist ?>
	.attachment-details .setting[data-setting="album"],         	<?php // Audio album ?>
    .media-types-required-info{
		display: none;
	}
	.attachments-browser .compat-field-enable-media-replace .label{ <?php // Media Replacer ?>
		margin: 0;
	}
	.attachments-browser .compat-attachment-fields tr td:not([class]):first-of-type{ 	<?php // Rank Math  ?>
		min-width: 30%;
		margin-right: 4%;
		float: left;
		text-align: right;
		padding: 1.3em 0;
	}
	</style>
<?php 
});
