<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly

# Remove stupid button that hides the secondary admin bar by default
add_filter( 'tiny_mce_before_init', 'gpm_mce_before_init' );
function gpm_mce_before_init( $settings ) {
	$settings[ 'wordpress_adv_hidden' ] = FALSE;
	return $settings;
}

/**
 * Customize Top Row of WYSIWYG
 *
 * Uncomment items to remove them from the editor
**/
add_filter( 'mce_buttons', 'remove_tinymce_button' );
function remove_tinymce_button( $buttons ){
	$remove = array(
		//'bold',
		//'italic',
		//'strikethrough',
		//'bullist',
		//'numlist',
		'blockquote',
		//'hr',
		//'alignleft',
		//'aligncenter',
		//'alignright',
		//'link',
		//'unlink',
		'wp_more',
		//'spellchecker',
		'fullscreen',
		'wp_adv'
	);

	return array_diff( $buttons, $remove );
}

/**
 * Customize Bottom Row of WYSIWYG
 * 
 * Uncomment items to remove them from the editor
**/
add_filter( 'mce_buttons_2','remove_tinymce2_buttons' );
function remove_tinymce2_buttons( $buttons ){
	$remove = array(
		//'formatselect',
		//'underline',
		//'alignjustify',
		//'forecolor',
		//'pastetext',
		//'removeformat',
		'charmap',
		//'outdent',
		//'indent',
		//'undo',
		//'redo',
		'wp_help'
	);

	return array_diff( $buttons, $remove );
}

/**
 * Customize the Buttons On the Text View
**/
add_filter( 'quicktags_settings', 'default_text_view', 10, 2 );
function default_text_view( $qtInit, $editor_id = 'content' ) {
	$qtInit['buttons'] = 'strong,em,link,block,img,ul,ol,li,code';
	return $qtInit;
}

/**
 * ACF Edits for the TinyMCE Toolbar
 */
if (class_exists('ACF')) {
	add_filter( 'acf/fields/wysiwyg/toolbars', 'shenk_theme_toolbars' );
	function shenk_theme_toolbars( $toolbars ){

		// Uncomment to view format of $toolbars
		// echo '<pre>';
		// 	print_r($toolbars);
		// echo '</pre>';
		// die;

		// Add a new toolbar called "Very Simple"
		// - this toolbar has only 1 row of buttons
		$toolbars['Front End'] = array();
		$toolbars['Front End'][1] = array('bold', 'italic', '|', 'underline', 'strikethrough', '|', 'undo', 'redo' );

		return $toolbars;
	}
}
