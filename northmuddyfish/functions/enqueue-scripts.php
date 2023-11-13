<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package     Theme Styles & Scripts
**/
add_action( 'wp_enqueue_scripts', 'shenk_styles', 99);
add_action( 'wp_enqueue_scripts', 'shenk_register_scripts', 99);
function shenk_styles(){

    # Register styles for later use
    wp_register_style('primary-theme-css', THEME_DIR . '/assets/dist/css/primary-theme.min.css', false, asset_version(), 'all');
    wp_register_style('template-background-img', THEME_DIR . '/assets/dist/css/template-background-img.min.css', false, asset_version(), 'all');
    wp_register_style('my-account', THEME_DIR . '/assets/dist/css/my-account.min.css', false, asset_version(), 'all');
    wp_register_style('theme-print', THEME_DIR . '/assets/dist/css/theme-print.min.css', false, asset_version(), 'print');
    wp_register_style('slick-slider-css', THEME_DIR . '/assets/dist/css/slick-slider.min.css', false, asset_version(), 'all');
    wp_register_style('error-page', THEME_DIR . '/assets/dist/css/error-page.min.css', false, asset_version(), 'all');
    wp_register_style('event-calendar', THEME_DIR . '/assets/dist/css/tribe-calendar.min.css', false, asset_version(), 'all');
    wp_register_style('posts-page', THEME_DIR . '/assets/dist/css/posts.min.css', false, asset_version(), 'all');

    # Enqueue styles
    wp_enqueue_style('primary-theme-css');
    wp_enqueue_style('theme-print');
    
    if( is_page_template('page-templates/background-image.php') ){
        wp_enqueue_style('template-background-img');
    }
    if( is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_search() || is_date() ){
        wp_enqueue_style('posts-page');
    }

    $reset_pw = get_option( 'somfrp_gen_settings' );
	$reset_value = ( isset( $reset_pw['somfrp_reset_page'] ) && $reset_pw['somfrp_reset_page'] ) ? esc_html($reset_pw['somfrp_reset_page']): '' ;
    if( isset($reset_value) && is_page($reset_value) ){
        wp_enqueue_style('my-account');
        wp_enqueue_script('my-account-js');
    }
}
function shenk_register_scripts(){
    
    # Register scripts for later use
    wp_register_script('primary-theme-js', THEME_DIR . '/assets/dist/js/primary-theme.min.js', array('jquery', 'hoverIntent'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );
    wp_register_script('theme-global', THEME_DIR . '/assets/dist/js/theme-global.min.js', array('jquery'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );
    wp_register_script('my-account-js', THEME_DIR . '/assets/dist/js/my-account.min.js', array('jquery', 'theme-global', 'valid-form'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );
    wp_register_script('valid-form', THEME_DIR . '/assets/dist/js/valid-form.min.js', array('jquery'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );
    wp_register_script('slick-slider-js', THEME_DIR . '/assets/dist/js/slick-slider.min.js', array('jquery'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );
    wp_register_script('img-view-spotlight', THEME_DIR . '/includes/blocks/tab-module/spotlight/spotlight.bundle.js', array('jquery'), asset_version(), array(
        'strategy' => 'defer', 
        'in_footer' => 'true'
    ) );

    # Enqueue scripts
    wp_enqueue_script('primary-theme-js');
}

add_action('shenk-after-wp-head', 'theme_override_css', 5);
function theme_override_css(){ ?>
    <style>
        :root{
            --tec-color-background-subscribe-list-item-hover: #cbcbcb;
            --tec-color-icon-disabled: #797979;
            --tec-color-border-secondary-month-grid: #000000;
            --tec-color-text-day-of-week-month: #ffffff;
            --tec-color-background-messages: rgba(0, 0, 0, 7%);
        }
    </style>
    <?php
}

/**
 * @package         Adds async to all scripts listed
 * @see             the_script_optimizer
**/
// add_filter('the_script_async', function(){
//     if( ! is_admin() ){
//         return ['wc-password-strength-meter'];
//     }
// });

/**
 * @package         Adds defer to all scripts listed
 * @see             the_script_optimizer
**/
add_filter('the_script_defer', function(){
    if( ! is_admin() ){
        return [
            'hoverIntent', 'hoverintent-js', 'shenk-frequently-asked-qs-script', 'shenk-tab-module-script'
        ];
    }
});

/**
 * @package 		List all Enqueued Assets (css + js)
 * @return array    Asset handle + url
**/
// add_action( 'wp_print_scripts', 'list_all_scripts', 999 );
// add_action( 'wp_print_styles', 'list_all_styles', 999 );
// add_action( 'wp_head', 'list_all_included_assets');


/**
 * More Resources
**/

# Admin
add_action( 'admin_enqueue_scripts', 'shenk_enqueue_admin_scripts' );
function shenk_enqueue_admin_scripts( $hook_suffix ){
    wp_enqueue_style('theme-admin', THEME_DIR . '/assets/dist/css/theme-admin.min.css', false, 1, 'all');
}

# Login
add_action( 'login_enqueue_scripts', 'shenk_enqueue_login_scripts' );
function shenk_enqueue_login_scripts(){
    wp_enqueue_style('theme-login', THEME_DIR . '/assets/dist/css/theme-login.min.css');
}

# Gutenberg + TinyMCE
add_action( 'admin_init', 'gutenberg_editor_style' );
function gutenberg_editor_style() {
    add_editor_style( THEME_DIR . '/assets/dist/css/editor.min.css' );
}

add_filter( 'tiny_mce_before_init', 'override_mp6_tinymce_styles' );
function override_mp6_tinymce_styles( $mce_init ) {

	$content_css = get_stylesheet_directory_uri() . '/assets/dist/css/editor.min.css';
	if ( isset( $mce_init[ 'content_css' ] ) ){
        $content_css .= ',' . $mce_init[ 'content_css' ];
    }
	
	$mce_init[ 'content_css' ] = $content_css;
	
	return $mce_init;
}

/**
 * Gutenberg scripts
 * @link https://www.billerickson.net/block-styles-in-gutenberg/
 * 
 * @param string handle        Name of the script
 * @param string src           Full URL of the script
 * @param array  dependencies  ['lodash', 'wp-autop', 'wp-blob', 'wp-block-serialization-default-parser', 'wp-compose', 'wp-data', 'wp-a11y',
 *                             'wp-deprecated', 'wp-dom', 'wp-element', 'wp-hooks', 'wp-html-entities', 'wp-i18n', 'wp-is-shallow-equal', 
 *                             'wp-polyfill', 'wp-shortcode', 'wp-dom-ready', 'wp-rich-text', 'wp-api-fetch', 'wp-components', 'wp-core-data',
 *                             'wp-editor', 'wp-notices', 'wp-plugins', 'wp-primitives', 'wp-date', 'wp-escape-html', 'wp-keyboard-shortcuts',
 *                             'wp-keycodes', 'wp-preferences', 'wp-private-apis', 'wp-style-engine', 'wp-token-list', 'wp-url', 'wp-warning',
 *                             'wp-wordcount', 'wp-block-editor', 'moment', 'react', 'react-dom', 'wp-priority-queue', 'wp-redux-routine',
 *                             'wp-block-library', 'wp-reusable-blocks', 'wp-widgets', 'wp-media-utils', 'wp-viewport', 'wp-server-side-render']
 * 
 * @param init  version         Specifying script version number, which is added to the URL as a query string for cache busting purposes
 */
add_action( 'enqueue_block_editor_assets', 'gutenberg_editor_scripts' );
function gutenberg_editor_scripts(){
    if( $_SERVER['REQUEST_URI'] !== '/wp-admin/widgets.php' ){
        wp_enqueue_script(
            'theme-editor', 
            THEME_DIR. '/assets/dist/js/theme-editor.min.js', 
            array( 'wp-blocks', 'wp-dom', 'wp-api-fetch', 'acf-input', 'react', 'wp-edit-post' ), 
            filemtime( THEME_ABS. '/assets/dist/js/theme-editor.min.js' ),
            array(
                'in_footer' => 'true'
            )
        );
    }

    $post_id = get_the_ID();
    $page_template = get_post_meta($post_id, '_wp_page_template', true);
    if ($page_template == 'page-templates/background-image.php') {
        wp_enqueue_style('template-background-img', THEME_DIR . '/assets/dist/css/template-background-img.min.css');
    }
}
