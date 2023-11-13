<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * The Login page
**/
add_action( 'login_enqueue_scripts', function(){
	if ( function_exists( 'get_field' ) ) {
		$logo = get_field('logo', 'options');
	}
	if ( $logo != '' ){ ?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url(<?php echo $logo['url']; ?>);
                height: <?php echo $logo['height']; ?>px;
			}
		</style>
	<?php }
});

# Login Logo URL
add_filter( 'login_headerurl', 'shenk_custom_login_url', 10, 1 );
function shenk_custom_login_url( $url ){
    return esc_url( home_url( '/' ) );
}

# Login Site Name
add_filter( 'login_headertitle', 'shenk_login_logo_title' );
function shenk_login_logo_title(){
    return get_bloginfo( 'name' );
}

# Add HTML to the bottom of WP login 
// add_action( 'login_footer', function(){
// }, 10 );
