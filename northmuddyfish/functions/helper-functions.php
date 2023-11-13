<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package			A Smarter var_dump
 * 
 * @author			Brett Shenk
 * @version			1.1
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2022
 * 
 * @param mixed[]   $output     Get more info about the array, variable or object
 * @param bool      $hide       Optional. Weather its visible on the page or not
 */
if ( ! function_exists('v_dump')) {
    function v_dump( $output, $hide = false ){
        if($hide){
            echo '<!--';
        }

        echo '<pre>';
        var_dump( $output );
        echo '</pre>';

        if($hide){
            echo '-->';
        }
    }
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package			Measuring Script Execution Time
 * 
 * @author			Brett Shenk
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2023
 * @example			Below is only an example of how to use it
 */
if ( ! function_exists('script_execution_time')) {
    function script_execution_time(){
        return;         // Stop function if called

        // Starting clock time in seconds
        $start_time = microtime(true);
        
        // Do Some Stuff
        
        $end_time = microtime(true);					// End clock time in seconds
        $execution_time = ($end_time - $start_time);	// Calculate script execution time
        echo " Execution time of script = ".round($execution_time, 4)." sec";
    }
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package			Write Your Own Info to the Server Log
 * 
 * @author			Wil
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2022
 * @link            https://zeropointdevelopment.com/using-the-wordpress-debug-log-wpquickies/
 */
if ( ! function_exists('write_log')) {
    function write_log ( $log )  {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}

/**
 * @example:
 * 
 * if( is_user_logged_in() ) {
 *      $user_id = get_current_user_id();
 *      write_log( 'Processing user ID: ' . $user_id );
 * }
**/


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package			Better Way to Add Multiple Widget Areas
 * 
 * @author			Stefan Budimirovic
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2020
 * @link            https://developer.wordpress.org/reference/functions/register_sidebar/#comment-content-3580
 * 
 * @contributors	Brett Shenk
 * @version 	 	2.0
 */
function widget_registration( $name, $id, $description = '', $beforeWidget = '', $afterWidget = '', $class = '' ){
    if( empty( $beforeWidget ) ){
        $beforeWidget = '<div id="%1$s" class="widget %2$s">';
    }
    if( empty( $afterWidget ) ){
        $afterWidget = '</div>';
    }
	register_sidebar( array(
		'name'          => $name,                   // The name or title of the sidebar displayed in the Widgets interface
		'id'            => $id,                     // The unique identifier by which the sidebar will be called
		'description'   => $description,            // Description of the sidebar, displayed in the Widgets interface
		'before_widget' => $beforeWidget,           // HTML content to prepend to each widget's HTML output when assigned to this sidebar
		'after_widget'  => $afterWidget,            // HTML content to append to each widget's HTML output when assigned to this sidebar
        'class'         => $class,                  // Extra CSS class to assign to the sidebar in the Widgets interface
		// 'before_title'  => $beforeTitle,            // HTML content to prepend to the sidebar title when displayed
		// 'after_title'   => $afterTitle,             // HTML content to append to the sidebar title when displayed
        // 'before_sidebar' => $beforeSidebar,         // HTML content to prepend to the sidebar when displayed
        // 'after_sidebar' => $afterSidebar,           // HTML content to append to the sidebar when displayed
	));
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Asset Version Handle
 * 
 * @author			Brett Shenk
 * @version			1.1
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2023
 */
function asset_version(){
    if( function_exists( 'wp_get_environment_type' ) ){
        if( wp_get_environment_type() == 'local' || wp_get_environment_type() == 'development' ){
            return rand(00000, 99999);
        } else {
            $theme = wp_get_theme();
            return $theme->get( 'Version' );
        }
    } else {
        $theme = wp_get_theme();
        return $theme->get( 'Version' );
    }
}


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * @package			Date and Time with PHP and WordPress
 * 
 * @author			Jeff Starr
 * @version			1.0
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright	 	2022
 * @link            https://wp-mix.com/date-time-php-wordpress/
 * @see             https://www.php.net/manual/en/datetime.format.php
 */
function get_date(){
	$date_format = get_option('date_format');
	$time_format = get_option('time_format');
	$date = date("{$date_format} {$time_format}", current_time('timestamp'));
	return $date;
}

// full date/time according to the admin-selected format and timezone
// $date = date_i18n(get_option('date_format'), current_time('timestamp')) .' @ '. date_i18n(get_option('time_format'), current_time('timestamp'));
