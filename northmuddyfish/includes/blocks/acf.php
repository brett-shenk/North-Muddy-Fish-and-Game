<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package Theme.json
 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/
 * @link https://developer.wordpress.org/themes/advanced-topics/theme-json/
 * 
 * @package Block.json
 * @link https://www.advancedcustomfields.com/resources/acf-blocks-with-block-json/
 * @link https://www.advancedcustomfields.com/resources/whats-new-with-acf-blocks-in-acf-6/
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * @link https://developer.wordpress.org/block-editor/reference-guides/core-blocks/
 * 
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Help with block.json
 * 
 * name:		 The name of the block, which is a unique string. Must have a namespace prefix. Ex: namespace/block-name
 * title:		 Display title for your block that is visible in various areas of the editor.
 * description:	 Description of the block to the displayed in the admin
 * keywords:	 An image block could also want to be discovered by photo. You can do so by providing an array of unlimited terms
 * 				 [Synonyms, Antonyms] https://www.powerthesaurus.org/
 * 
 * icon:		 SVG  OR  https://developer.wordpress.org/resource/dashicons/  OR  https://www.svgrepo.com/
 * supports:	 https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/#anchor
 * version:		 Current version of your code / module / plugin / section
 * 
 * category:	 Default WordPress handles:
 * 				 [text, media, design, widgets, theme, embed]
 * 
 * editorStyle:  Details:   CSS file for the editor
 *               Hook:      block-editor-[block-name]
 *               File01:    editor-style.css
 * 				 File02:	editor-style.scss, editor-style.min.css
 * 
 * style:		 Details:   CSS file for the editor and front end
 *               Hook:      block-[block-name]
 * 				 File01:    style.css
 * 				 File02:	style.scss, style.min.css
 * 
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package		Blocks
 * 
 * @author      CultivateWP
 * @author		Brett Shenk
 * @version		2.1
 * @license     GPL-2.0+  https://choosealicense.com/licenses/gpl-2.0/
 * @copyright	2022
 * @link        https://www.billerickson.net/building-acf-blocks-with-block-json/
 * 
** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Load Blocks
add_action( 'init', __NAMESPACE__ . '\load_blocks', 5 );
function load_blocks() {
	$theme  = wp_get_theme();
	$blocks = get_blocks();
	
	foreach( $blocks as $block ) {

		if ( file_exists( get_template_directory() .'/includes/blocks/'. $block .'/block.json' ) ) {

			register_block_type( get_template_directory() .'/includes/blocks/'. $block .'/block.json' );
			
			if( is_dir( get_template_directory() .'/includes/blocks/'. $block .'/dist' ) ){
				if( file_exists( get_template_directory() .'/includes/blocks/'. $block .'/dist/editor-style.min.css' ) ){
					wp_register_style(
						'block-editor-'. $block, 
						get_template_directory_uri() .'/includes/blocks/'. $block .'/dist/editor-style.min.css', 
						null, 
						$theme->get( 'Version' ) 
					);
				}
				if( file_exists( get_template_directory() .'/includes/blocks/'. $block .'/dist/style.min.css' ) ){
					wp_register_style(
						'block-'. $block, 
						get_template_directory_uri() .'/includes/blocks/'. $block .'/dist/style.min.css', 
						null, 
						$theme->get( 'Version' ) 
					);
				}
			} else {
				if( file_exists( get_template_directory() .'/includes/blocks/'. $block .'/editor-style.css' ) ){
					wp_register_style(
						'block-editor-'. $block, 
						get_template_directory_uri() .'/includes/blocks/'. $block .'/editor-style.css', 
						null, 
						$theme->get( 'Version' )
					);
				}
				if( file_exists( get_template_directory() .'/includes/blocks/'. $block .'/style.css' ) ){
					wp_register_style(
						'block-'. $block, 
						get_template_directory_uri() .'/includes/blocks/'. $block .'/style.css', 
						null, 
						$theme->get( 'Version' )
					);
				}
			}
		}
	}
}

// Load ACF field groups for blocks
if (class_exists('ACF')) {
	add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\load_acf_field_group' );
	function load_acf_field_group( $paths ) {
		$blocks = get_blocks();
		foreach( $blocks as $block ) {
			$paths[] = get_template_directory() . '/includes/blocks/' . $block;
		}
		return $paths;
	}
}

// Get Blocks
function get_blocks() {
	$theme   = wp_get_theme();
	$blocks  = get_option( 'shenk_blocks' );
	$version = get_option( 'shenk_blocks_version' );
	if ( empty( $blocks ) || version_compare( $theme->get( 'Version' ), $version ) || ( function_exists( 'wp_get_environment_type' ) && 'production' !== wp_get_environment_type() ) ) {
		$blocks = scandir( get_template_directory() . '/includes/blocks/' );
		$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );

		update_option( 'shenk_blocks', $blocks );
		update_option( 'shenk_blocks_version', $theme->get( 'Version' ) );
	}
	return $blocks;
}


/**
 * Block categories
**/
add_filter( 'block_categories', 'shenk_block_categories' );
function shenk_block_categories( $categories ) {
    $category_slugs = wp_list_pluck( $categories, 'slug' );
    return in_array( 'custom_theme', $category_slugs, true ) ? $categories : array_merge(
        $categories,
        array(
            array(
                'slug'  => 'custom_theme',
                'title' => __( 'Custom', 'custom_theme' ),
                'icon'  => null,
            ),
        )
    );
}


/**
 * Option Pages
**/
if ( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Global Settings',
		'menu_title'	=> 'Global Settings',
		'menu_slug' 	=> 'global-settings',
		'capability'	=> 'update_plugins',
		'position'		=> 59,
		'icon_url'		=> 'dashicons-admin-site',
		'redirect'		=> true,
		// 'update_button' => __('Update', 'acf'),
		// 'updated_message' => __("Options Updated", 'acf'),
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'General Information',
		'menu_title'	=> 'General Information',
		'parent_slug'	=> 'global-settings',
	));
}


/**
 * Registers a new block style
 * @internal Not to be confused with variations, which is different.
 * 
 * Help Links
 * @see https://developer.wordpress.org/block-editor/reference-guides/core-blocks/
 * @see https://developer.wordpress.org/reference/functions/register_block_style/
**/
register_block_style(
	'core/image',
	array(
		'name' => 'align-center',
        'label' => 'Align Center',
        'inline_style' => '.is-style-align-center, .is-style-align-center > div, .is-style-align-center img { text-align: center; margin: 0 auto!important; }',
	)
);
register_block_style(
	'core/cover',
	array(
		'name' => 'background',
        'label' => 'Background',
        'inline_style' => '.wp-block-cover.is-style-background .wp-block-cover__inner-container{ margin-left: auto!important; margin-right: auto!important; width: 100%!important; max-width: var(--wp--style--global--content-size); padding-left: 1em; padding-right: 1em; display: flex; flex-direction: column; align-items: center; }
		.wp-block-cover.is-style-background .wp-block-cover__inner-container > *:not(.block-list-appender){ background-color: rgba(255, 255, 255, .7)!important; border-radius: 0!important; margin: 0!important; color: #000000; width: 100%; max-width: 500px; padding: 0 18px; }
		.wp-block-cover.is-style-background.is-position-center-right .wp-block-cover__inner-container, .wp-block-cover.is-style-background.is-position-top-right .wp-block-cover__inner-container,.wp-block-cover.is-style-background.is-position-bottom-right .wp-block-cover__inner-container{ align-items: flex-end; }
		.wp-block-cover.is-style-background.is-position-center-left .wp-block-cover__inner-container, .wp-block-cover.is-style-background.is-position-top-left .wp-block-cover__inner-container, .wp-block-cover.is-style-background.is-position-bottom-left .wp-block-cover__inner-container{ align-items: flex-start; }',
	)
);
