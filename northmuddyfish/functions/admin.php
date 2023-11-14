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
