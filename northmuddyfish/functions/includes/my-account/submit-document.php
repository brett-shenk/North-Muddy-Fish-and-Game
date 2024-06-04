<?php
/**
 * My Account - Submit a Document
 * 
 * @var array   $args['data']  [ID, user_login, user_pass, user_email, display_name, roles]
 */

if(in_array('document-library-pro/document-library-pro.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	echo do_shortcode('[dlp_submission_form]');
}
