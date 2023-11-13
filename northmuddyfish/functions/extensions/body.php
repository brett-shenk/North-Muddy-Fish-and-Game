<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Body Classes
**/
add_filter('body_class', function( $classes ){
	$classes[] = 'shenks-theme';

	if ( is_preview() )  {
		$classes[] = 'preview-mode';
		return $classes;
	}
	if( get_query_var('is_my_account_login') ){
		$classes[] = 'my-account-login';
	}
	if( get_query_var('is_my_account_register') ){
		$classes[] = 'my-account-register';
	}
	if( get_query_var('is_my_account') ){
		$classes[] = 'my-account-page';
	}
	
	if ( is_singular() ){

		# Remove classes that are added for page templates
		if ( is_page_template() ) {
			global $wp_query;
			$post_id        = $wp_query->get_queried_object_id();
			$template_slug  = get_page_template_slug( $post_id );
			$template_parts = explode( '/', $template_slug );

			if( isset( $template_parts ) && !empty($template_parts[1]) ){
				$remove_classes = [
					'page-template-'.$template_parts[0].str_replace( '.', '-', $template_parts[1] ),
					'page-template-page-templates',
				];
			} else {
				$remove_classes = [ 'page-template-page-templates' ];
			}
			$classes = array_diff($classes, $remove_classes);
		}

		# Remove useless class added for pages
		if( is_page() ){
			$remove_classes = [ 'page' ];
			$classes = array_diff($classes, $remove_classes);
		}
	}

	return $classes;
});


/**
 * Add template class to admin body based on current page's template.
 */
add_filter('admin_body_class', 'shenk_add_template_class');
function shenk_add_template_class($classes){
    $template = get_page_template_slug();
    if ($template) {
        $class = preg_replace('/\.php$/', '', $template);
        $classes .= ' ' . sanitize_html_class($class);
    }
    return $classes;
}
