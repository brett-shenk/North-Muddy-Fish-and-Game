<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Additions to the Admin
**/

# Remove Empty p tags for Shortcodes
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content) {
	$array = array (
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']'
	);

	$content = strtr($content, $array);
	return $content;
}

# Allow Shortcodes in Text Widgets
add_filter('widget_text', 'do_shortcode');

# Update the Admin Footer
add_filter('admin_footer_text', 'shenk_admin_footer', 999);
function shenk_admin_footer() {
	$output = '';
	$output .= 'Powered by: <a href="https://wordpress.org/" target="_blank">WordPress</a>. ';
	$output .= 'This site has been custom built just for you by ';
	$output .= '<a href="https://www.linkedin.com/in/brett-shenk-59480794/" target="_blank">Brett Shenk</a>. ';
	echo $output;
}

# Admin Greeting
add_filter('admin_bar_menu', 'shenk_replace_wp_howdy', 25 );
function shenk_replace_wp_howdy( $wp_admin_bar ){
    $my_account = $wp_admin_bar->get_node('my-account');

	$date = date_i18n("G:i", current_time('timestamp'));
	if( $date < '11:59' ){
		$text = "Morning, ";
	} else {
		$text = "Afternoon, ";
	}

    $greeting = str_replace( 'Howdy,', $text, $my_account->title );
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $greeting,
    ));
}

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Custom Pages
**/
add_action('init', 'theme_rewrite_rules');
function theme_rewrite_rules(){
	/**
	 * add_rewrite_rule( 
	 * 			url [REGEX],
	 * 			location, 
	 * 			priority [ex: top is before other rewrite rules]
	 * );
	 */
   add_rewrite_rule('^login?', 'index.php?is_my_account_login=1&post_type=page', 'top');
   add_rewrite_rule('^register?', 'index.php?is_my_account_register=1&post_type=page', 'top');
   add_rewrite_rule('^my-account?', 'index.php?is_my_account=1&post_type=page', 'top');
}

// Register our custom query var(s)
add_action('query_vars','shenk_set_query_var');
function shenk_set_query_var($vars) {
    array_push($vars, 'is_my_account_login');
	array_push($vars, 'is_my_account_register');
	array_push($vars, 'is_my_account');
    return $vars;
}

// Associate a template with the query_var(s)
add_filter('template_include', 'shenk_include_template', 1000, 1);
function shenk_include_template($template){
    if( get_query_var('is_my_account_login') ){
    	$new_template = get_template_directory() . '/functions/includes/login.php';

		if( file_exists($new_template) ){
			$template = $new_template;
		}
    }
	if( get_query_var('is_my_account_register') ){
    	$new_template = get_template_directory() . '/functions/includes/register.php';

		if( file_exists($new_template) ){
			$template = $new_template;
		}
    }
	if( get_query_var('is_my_account') ){
    	$new_template = get_template_directory() . '/functions/includes/my-account.php';

		if( file_exists($new_template) ){
			$template = $new_template;
		}
    }
	return $template;
}

add_filter('frontend/title', 'some_callback', 1);
function some_callback($data){
    return 'My new title';
}
