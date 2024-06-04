<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * @package Form Validation Helper
 */
function clean_hex( $input ){
    $clean = preg_replace('/[^\r\n\t\x20-\x7E\xA0-\xFF]/', ' ', $input);
    return $clean;
}

if(in_array('document-library-pro/document-library-pro.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	add_filter('dlp_form_wp_editor_args', function( $editor ){
		$editor["tinymce"]["toolbar1"] = 'bold,italic,|,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,undo,redo';
		return $editor;
	});

	add_filter( 'document_library_pro_language_defaults', function( $defaults ){
		$defaults['zeroRecords'] = 'Sorry, no documents found.'; 
		return $defaults; 
	} );
}

/**
 * @package 		Format Content to be displayed
 * 
 * @author			Brett Shenk
 * @version			1.0
 * @license			GNU AGPLv3  https://choosealicense.com/licenses/agpl-3.0/
 * @copyright		2023
**/
function shenk_input_formatter( $string ){

	// Returned string
    $return = '';

	// Turn all returns into an array we can work with
    $array = preg_split("/\r\n|\n|\r/", $string);

	/**
	 * Dump anything empty
	 * @version 1.0
	 */
    foreach($array as $index => $value){
        if( empty($value) ){
            unset( $array[$index] );
        }
    }

	// Reset the array index from the previous function
    $array = array_values($array);

    // Loop over each line
    $i = 0;
    while( $i < count($array) ){
		$modify_string = $array[$i];

		/**
		 * Replace deprecated HTML tags with compliant ones
		 * @version 1.0
		 */
		$modify_string = sh_replace_tags( $modify_string );

		/**
		 * Make sure supported HTML tags are lowercase
		 * @version 1.0
		 */
		$modify_string = sh_lowercase_tags( $modify_string );

		/**
		 * Support <p> tags and returns
		 * @version 1.0
		 */
        if( ! str_contains($modify_string, '<p>') || ! str_contains($modify_string, '</p>') ){
            $return .= '<p>'. $modify_string .'</p>';
        } else {
            $return .= $modify_string;
        }
		
        $i++;
    }

    return $return;
}

/**
 * @package Replace deprecated HTML tags with compliant ones
 * 
 * @version 		1.0
 * @license			GNU AGPLv3  https://choosealicense.com/licenses/agpl-3.0/
 * @copyright		Brett Shenk 2023
 * 
 * @param  string 	$string The input string
 * @return string 	The filtered text
 */
function sh_replace_tags( $string ){
	$pattern = array(
		'`(<b)([^\w])`i', 
		'`(<i)([^\w])`i', 
		'`(<s)([^\w])`i', 
		'`(<strike)([^\w])`i'
	);
	$replacement = array(
		"<strong$2", 
		"<em$2", 
		"<del$2", 
		"<del$2"
	);

	$subject_raw = array(
		'</b>', '</i>', 
		'</B>', '</I>', 
		'</s>', '</strike>', 
		'</S>', '</Strike>',
		'</STRIKE>'
	);
	$subject_new = array(
		'</strong>', '</em>', 
		'</strong>', '</em>', 
		'</del>', '</del>', 
		'</del>', '</del>',
		'</del>'
	);

	$subject = str_replace($subject_raw, $subject_new, $string);
	
	return preg_replace($pattern, $replacement, $subject);
}

/**
 * @package Make sure supported HTML tags are lowercase
 * 
 * @version 		1.0
 * @license			GNU AGPLv3  https://choosealicense.com/licenses/agpl-3.0/
 * @copyright		Brett Shenk 2023
 * 
 * @param  string 	$string The input string
 * @return string 	The filtered text
 */
function sh_lowercase_tags( $string ){
	$pattern = array(
		'`(<p)([^\w])`i', 
		'`(<small)([^\w])`i', 
		'`(<span)([^\w])`i', 
		'`(<ins)([^\w])`i', 
		'`(<strong)([^\w])`i', 
		'`(<del)([^\w])`i', 
		'`(<em)([^\w])`i'
	);
	$replacement = array(
		"<p$2", 
		"<small$2", 
		"<span$2", 
		"<ins$2", 
		"<strong$2", 
		"<del$2", 
		"<em$2"
	);

	$subject_raw = array(
		'</p>', '</P>', 
		'</small>', '</Small>', '</SMALL>', 
		'</span>', '</Span>', '</SPAN>',
		'</ins>', '</Ins>', '</INS>', 
		'</strong>', '</Strong>', '</STRONG>', 
		'</del>', '</Del>', '</DEL>', 
		'</em>', '</Em>', '</EM>'
	);
	$subject_new = array(
		'</p>', '</p>', 
		'</small>', '</small>', '</small>', 
		'</span>', '</span>', '</span>',
		'</ins>', '</ins>', '</ins>', 
		'</strong>', '</strong>', '</strong>', 
		'</del>', '</del>', '</del>', 
		'</em>', '</em>', '</em>', 
	);

	$subject = str_replace($subject_raw, $subject_new, $string);
	
	return preg_replace($pattern, $replacement, $subject);
}


/**
 * @package 		Render Taxonomy as Form Element
 * 
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2020 - 2023
 * @link            https://codebriefly.com/how-to-create-taxonomy-for-users-in-wordpress/
 * @version 	 	2.0
 * @author			Brett Shenk
 *  @author			Pankaj Sood
 * 
 * @param string 	$name 		Name of the taxonomy
 * @param array 	$options	Terms available
 * @param integer 	$user_id	User ID to get the linked terms
 * @param string 	$type		Display style options:  [checkbox, dropdown]
**/
function shenk_custom_form_field( $name, $options, $user_id, $type = 'checkbox') {
	global $pagenow;
	
	$name = sanitize_title($name);
  	switch ($type) {
  	  	case 'checkbox':
  	  	  	foreach( $options as $term ): ?>
  	  	  		<label for="<?php echo $name .'-'. esc_attr( $term->slug ); ?>">
					<?php echo $term->name; ?>
				</label>
				<input 
					type="checkbox" 
					name="<?php echo $name .'[]'; ?>" 
					id="<?php echo $name .'-'. esc_attr( $term->slug ); ?>" 
					value="<?php echo $term->slug; ?>" 
				<?php if ( $pagenow !== 'user-new.php' ) checked( true, is_object_in_term( $user_id, $name, $term->slug ) ); ?>>
  	  	  		<?php
  	  	  	endforeach;
  	  	  	break;

  	  	case 'dropdown':
  	  	  	$selectTerms = [];
  	  	  	foreach( $options as $term ){
  	  	  		$selectTerms[$term->term_id] = $term->name;
  	  	  	}
			
			// get all terms linked with the user
			$usrTerms = get_the_terms( $user_id, $name );
			$usrTermsArr = [];
			if( !empty($usrTerms) ){
				foreach ( $usrTerms as $term ) {
					$usrTermsArr[] = (int) $term->term_id;
				}
			}
			
			// Dropdown
			echo "<select name='{$name}' id='{$name}'>";
			echo "<option value=''>-Select-</option>";
			foreach( $selectTerms as $options_value => $options_label ) {
				$selected = ( in_array($options_value, array_values($usrTermsArr)) ) ? " selected='selected'" : "";
				echo "<option value='{$options_value}' {$selected}>{$options_label}</option>";
			}
			echo "</select>";
			break;
  	}
}

/**
 * @package Cut String to Specified Length With an Ending
 * 
 * @license			MIT  https://choosealicense.com/licenses/mit/
 * @copyright		2008
 * @author			Philip Norton
 * @link            https://www.hashbangcode.com/article/cut-string-specified-length-php
 * @version 	 	1.0
 * 
 * @param string 	$text		Text to be potentially cut
 * @param string 	$maxchar	The max character length
 * @param string	$end		What shows on the end after being cut off
 */
function substrwords($text, $maxchar, $end = '...') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);      
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            } 
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    } 
    else {
        $output = $text;
    }
    return $output;
}
