<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package 		Automatic alt tags on images
 * 
 * @internal        Automatically adds alt text to images in Gutenberg block editor when you add the alt text in the Media Library
 * 
 * @author			Bill Erickson
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2020
 * @link            https://www.billerickson.net/code/wordpress-image-automatic-alt-text/
**/
add_filter( 'render_block', function( $content, $block ) {
	if( 'core/image' !== $block['blockName'] )
		return $content;

	$alt = get_post_meta( $block['attrs']['id'], '_wp_attachment_image_alt', true );
	if( empty( $alt ) )
		return $content;

	// Empty alt
	if( false !== strpos( $content, 'alt=""' ) ) {
		$content = str_replace( 'alt=""', 'alt="' . $alt . '"', $content );

	// No alt
	} else if( false === strpos( $content, 'alt="' ) ) {
		$content = str_replace( 'src="', 'alt="' . $alt . '" src="', $content );
	}

	return $content;
}, 10, 2 );


/**
 * Image Class
**/
// add_filter('get_image_tag_class','add_image_class');
function add_image_class($class){
    return $class;
}


/**
 * @package			Wordpress Allow File Types
 * 
 * @author			Brett Shenk
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2023
 * @link            https://wpengine.com/support/mime-types-wordpress/
 * 
 * @example			You can verify it's working with the following function to list all allowed:
 * 					get_allowed_mime_types()
**/
add_filter('upload_mimes', 'change_site_mime_types', 1, 1);
function change_site_mime_types( $mimes ){

    // Add...
	if( allow_user(2) ){				// Only allow certain users for security
		$mimes['svg'] = 'image/svg+xml';
	}
	$mimes['mpg'] = 'audio/mpeg';

    // Remove...
    unset( $mimes['mp4'] );			// video/mp4
	unset( $mimes['m4v'] );			// video/x-m4v
	unset( $mimes['wmv'] );			// video/x-ms-wmv
	unset( $mimes['avi'] );			// video/x-msvideo, video/avi, video/msvideo, application/x-troff-msvideo
	unset( $mimes['ogv'] );			// video/ogg
	unset( $mimes['wm'] );			// video/x-ms-wm
	unset( $mimes['wmx'] );			// video/x-ms-wmx
	unset( $mimes['divx'] );		// video/divx
	unset( $mimes['flv'] );			// video/x-flv
	unset( $mimes['webm'] );		// video/webm
	unset( $mimes['mov|qt'] );		// video/quicktime
	unset( $mimes['3gp|3gpp'] );	// video/3gpp
	unset( $mimes['3g2|3gp2'] );	// video/3gpp2
	unset( $mimes['mkv'] );			// video/x-matroska
	unset( $mimes['asf|asx'] );		// video/x-ms-asf
	unset($mimes['mpeg|mpg|mpe']);	// video/mpeg
	unset( $mimes['mp4|m4v'] );		// video/mp4

    return $mimes;
}
