<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package 		The Script Optimizer
 * 
 * @author			Brett Shenk
 * @version			1.2
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2022
 * 
 * @example 		Documentation on how to use is below.
**/
add_filter('script_loader_tag', 'the_script_optimizer', 10, 3);
function the_script_optimizer($tag, $handle, $src) {
    
    $async = apply_filters('the_script_async', null);
    if($async != null){
        foreach( $async as $asynced ){
            if($handle === $asynced) {
                if (false === stripos($tag, 'async')) {
                    $tag = str_replace(' src', ' async="async" src', $tag);
                }
            }
        }
    }

    $defer = apply_filters('the_script_defer', null);
    if($defer != null){
        foreach( $defer as $deferring ){
            if($handle === $deferring) {
                if (false === stripos($tag, 'defer')) {
                    $tag = str_replace('<script ', '<script defer ', $tag);
                }
            }
        }
    }
    return $tag;
}

/**
 * Adds async to all scripts listed
 * 
 * @see 			the_script_optimizer
 * @return html  	The script handle(s) to be included
 *               	Default is nothing
 * 
 * @internal 		To be loaded after primary resources are loaded. Doesn't follow document order and 
 *                  runs when downloaded. Best used for 3rd party scripts or anything standalone.
**/
// add_filter('the_script_async', function(){
//     if( ! is_admin() ){
//         return ['wc-password-strength-meter'];
//     }
// });

/**
 * Adds defer to all scripts listed
 * 
 * @see 			the_script_optimizer
 * @return html  	The script handle(s) to be included
 *               	Default is nothing
 * 
 * @internal 		To be loaded after primary resources are loaded first. Follows the document order.
**/
// add_filter('the_script_defer', function(){
//     if( ! is_admin() ){
//         return ['wc-password-strength-meter'];
//     }
// });


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package 		List all Enqueued Assets (css + js)
 * 
 * @author			Brett Shenk
 * @version			1.3
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2020
 * 
 * @return array    Asset handle + url
 * @internal        Limitation of WordPress, any asset not enqueued probably is excluded here.
 *                  If it's excluded here it will not work with the filters:
 *                      [the_script_async, the_script_defer]
**/
global $included_scripts;
global $included_styles;

function list_all_scripts(){
    global $wp_scripts;
    global $included_scripts;
    $included_scripts = array();
    foreach ( $wp_scripts->queue as $handle ) {
        $included_scripts[$handle] = $wp_scripts->registered[$handle]->src;
    }
}
function list_all_styles(){
    global $wp_styles;
    global $included_styles;
    $included_styles = array();
    foreach ( $wp_styles->queue as $handle ) {
        $included_styles[$handle] = $wp_styles->registered[$handle]->src;
    }
}
function list_all_included_assets(){
    global $included_scripts;
    echo '<pre>';
    var_dump( $included_scripts );
    echo '</pre>';
    global $included_styles;
    echo '<pre>';
    var_dump( $included_styles );
    echo '</pre>';
}

// add_action( 'wp_print_scripts', 'list_all_scripts', 999 );
// add_action( 'wp_print_styles', 'list_all_styles', 999 );
// add_action( 'wp_head', 'list_all_included_assets');
