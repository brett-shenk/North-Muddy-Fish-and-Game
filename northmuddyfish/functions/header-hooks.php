<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Header Hooks
 * 
 * @property hook shenk-before-wp-head
 * @property hook wp_head
 * @property hook shenk-after-wp-head
 * @property hook wp_body_open
**/

/**
 * Favicon
 * @link        http://realfavicongenerator.net/
 * 
 * Progressive Web App Support
 * 
 * @todo        Download and Install the plugin Progressive WordPress (PWA)
 * @link        https://wordpress.org/plugins/progressive-wp/
 * 
 * @internal    After configuring the plugin and adding the site's favicon through the customizer... Unfortunately,
 *              this is how it adds icons so no maskable icon can be added unless the default one is that you upload.
 *              It wants a 512 image uploaded. To prevent duplicate data being loaded delete the following files:
 *                  [ site.webmanifest, msapplication-*, apple-touch-icon, theme-color ]
**/
add_filter( 'wp_head', 'favicon_filter' );      // Front end of the site
add_action( 'admin_head', 'favicon_filter' );   // WordPress Admin
add_action( 'login_head', 'favicon_filter' );   // Wordpress Login
function favicon_filter(){ ?>
    <link rel="icon" href="<?php echo THEME_DIR; ?>/assets/dist/images/favicon/favicon.ico" type="image/x-icon" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo THEME_DIR; ?>/assets/dist/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo THEME_DIR; ?>/assets/dist/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?php echo THEME_DIR; ?>/assets/dist/images/favicon/safari-pinned-tab.svg" color="#1f2943">
    <meta name="theme-color" content="#ffffff">
<?php }

/**
 * Pingback
**/
add_action( 'shenk-before-wp-head', 'shenk_head_pingback', 1 );
function shenk_head_pingback(){ 
    if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php }
}

/**
 * Preloader
**/
add_action( 'shenk-before-wp-head', 'shenk_site_preloader_css', 1 );
function shenk_site_preloader_css(){ ?>
    <style>
        <?php include THEME_ABS .'/assets/dist/css/preloader.min.css'; ?>
    </style>
<?php }

add_action( 'before_site_header', 'shenk_site_preloader_html', 1 );
function shenk_site_preloader_html(){ ?>
    <div class="preloader" data-type="page">
        <span class="loader"></span>
    </div>
<?php }

add_action( 'wp_body_open', 'shenk_theme_script_init', 1 );
function shenk_theme_script_init(){ ?>
    <script type="module">
        (function($){
            $('html').removeClass('no-js');         // Make sure JS is enabled cz why not
            $('.preloader').fadeOut(200);
            $('body').css('overflow', 'auto');
        })(jQuery);
    </script>
    <?php
}


/**
 * Site Fonts
**/
add_action( 'shenk-before-wp-head', 'shenk_site_fonts', 2 );
function shenk_site_fonts(){ ?>
    <?php /* <link rel='preload' as='style' href='<?php echo THEME_DIR ."/assets/dist/css/fonts.min.css?ver=". asset_version(); ?>' media='all' />
    <link rel='stylesheet' href='<?php echo THEME_DIR ."/assets/dist/css/fonts.min.css?ver=". asset_version(); ?>' media='print' onload='this.media="all"' /> */ ?>
    <link rel='stylesheet' href='<?php echo THEME_DIR ."/assets/dist/css/fonts.min.css?ver=". asset_version(); ?>' media='all' />
<?php }

/**
 * Show Admin Bar for the roles that need it
 * 
 * Restricted User Roles: subscriber, contributor
**/
if( is_user_logged_in() ){
    if( allow_user(3) ){
        add_filter( 'show_admin_bar', 'shenk_show_admin_bar', 99, 1 );
    } else {
        add_filter( 'show_admin_bar', 'shenk_hide_admin_bar', 99, 1 );
        add_action( 'wp_head', 'hide_admin_bar' );
    }
}
# Enable the Admin Bar
function shenk_show_admin_bar( $show_admin_bar ) {
    $show_admin_bar = true;
	return $show_admin_bar;
}
function shenk_hide_admin_bar( $show_admin_bar ) {
    $show_admin_bar = false;
	return $show_admin_bar;
}
# Hide the admin bar from the initial page load
function hide_admin_bar(){ ?>
    <style>
		html { height: calc(100% - 32px) !important; }
		#wpadminbar #wp-admin-bar-new-content>a { pointer-events: none; }
	    @media screen and ( max-width: 782px ) {
			html { height: calc(100% - 46px) !important; }
	    }
    </style>
<?php }

/**
 * Prevent certain user roles from getting to the Admin
 * 
 * Restricted User Roles: subscriber, contributor
**/
add_action( 'init', 'block_users_init' );
function block_users_init() {
	if( is_admin() && is_user_logged_in() ){
		if( allow_user(3) == false && ! ( defined('DOING_AJAX') && DOING_AJAX ) ){
			wp_redirect( home_url() );
			exit;
		}
	}
}
