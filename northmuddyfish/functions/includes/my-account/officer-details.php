<?php
/**
 * My Account - Officer Details
 * 
 * @var array   $args['data']  [ID, user_login, user_pass, user_email, display_name, roles]
 */

$mytwitter = $description = $display_contact = $position = '';
$error_officer_details = new WP_Error();
$user_id = $args['data']->ID;
$the_my_account_url = $args['my-account-url'];

$position_terms = get_terms( 'positions', array( 'hide_empty' => false ) );

// Make sure Simple Local Avatars is active...
if ( !defined('SLA_VERSION') ){
    wp_die( 'Something went wrong. Please contact the site administrator. ERROR: SLAW_86128745' );
}
// Make sure Advanced Custom Fields (ACF) is active
if ( ! function_exists( 'get_field' ) ) {
    return 'Error Advanced Custom Fields plugin is missing.';
}

require( THEME_ABS . '/functions/includes/settings/allowed-tags.php' );

// Has this form's submit button been clicked?
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_officer_details']) ){
    if ( $_POST['token3'] == $_SESSION['token3'] ){
        /**
         * Form Validation Helper
         */
        $chars_valid = array(
            'position' => array(
                '-', '_', '&', '#', '!', '@', 
                '^', '(', ')', '[', ']', '{', '}', 
                ';', ':', '`', '~', '=', ' ', '.', 
                '?', '$', '*', '+', "'", ','
            ),
            'description' => array(
                '-', '_', '&', '#', '!', '@', 
                '^', '(', ')', '[', ']', '{', '}', 
                ';', ':', '`', '~', '=', '<', '>', 
                '?', '|', '$', '%', '*', '+', "'", 
                '"', ' ', '.', ',', '/', '\\'
            )
        );
        
        /**
         * @var string $display_contact  What happens:
         *      1. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Check for being empty
         *      3. Make sure what is selected is already in the list of options
         */
        $display_array = ['email+phone', 'phone', 'email', 'none'];
        if( isset($_POST['display_contact_info']) ){
            $display_contact = sanitize_text_field( $_POST['display_contact_info'] );
        }
        if( empty($display_contact) ){
            $error_officer_details->add('display-pae-empty', "Please select how you would like your contact info to show.");
        } else if( ! in_array($display_contact, $display_array) ){
            $error_officer_details->add('display-pae-incorrect', "Please select a proper contact info display option.");
        }

        /**
         * @var string $position  What happens:
         *      1. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Allow alpha characters only
         *      3. If 2 passes, make sure what is selected is already in the list of options:  $position_terms
         */
        if( isset( $_POST['positions'] ) ){
            $position = sanitize_text_field( $_POST['positions'] );
        }
        if( !empty( $position ) ){
            if( ctype_digit($position) ){
                foreach( $position_terms as $single_position ){
                    if( $single_position->term_id == intval($position) ){
                        $position = $single_position->term_id;
                        break;
                    }
                }
            } else {
                $error_officer_details->add('position-charset', 'Invalid characters in your position.');
            }
        }

        /**
         * @var string $description  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Convert HTML entities to their corresponding characters
         *      1c. Filters text content and strips out disallowed HTML
         *      1d. Create a temporary value so we can transform the values we want to
         *              support to nothing to make sure nothing nefarious leaks through.
         *      2. Check maxlength is within required size
         *      3. Allow alpha characters only
         */
        if( isset( $_POST['description'] ) ){
            $description = $_POST['description'];
        }

        // 1a - 1c.
        $description = clean_hex( $description );
        $description = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
        $description = wp_kses( $description, $allowed_tags );

        // 1d.
        $desc_temp = trim( $description );
        $desc_temp = str_replace(array( "\r","\n" ), "", $desc_temp);
        $desc_temp = wp_kses( $desc_temp, ['p'] );
        
        if( !empty( $description ) ){
            if( strlen( $desc_temp ) > 999 ){
                $error_officer_details->add('description-maxlength', 'Description of yourself needs to be less than 999 characters.');
            } else if( !empty($desc_temp) && ! ctype_alnum( str_replace($chars_valid['description'], '', $desc_temp) ) ){
                $error_officer_details->add('description-charset', 'Invalid characters in the description about yourself.');
            }
        }

        /**
         * Check honeypot field after making sure nothing nefarious leaks through
         */
        if( isset( $_POST['mytwitter'] ) ){
            $mytwitter = clean_hex( $_POST['mytwitter'] );
            $mytwitter = sanitize_text_field( $mytwitter );
        }
        if( ! empty($mytwitter) ){
            $error_officer_details->add('honeypot', 'Unexpected error.');
        }
        
        /**
         * Show the error(s)
         */
        if (count($error_officer_details->get_error_codes())) {
            unset( $_SESSION['token3'] );
            ?>
            <div id="toast-container" data-type="error">
                <i class="icon-notification"></i>
                <p>Please correct the following error(s):</p>
                <ul>
                    <?php echo '<li>' . implode( '</li><li>', $error_officer_details->get_error_messages() ) . '</li>'; ?>
                </ul>
            </div>
            <?php
        
        /**
         * Update User Profile
         */
        } else {
            update_field( 'display_contact_info', $display_contact, 'user_'. $user_id );
            update_field( 'positions-title', $position, 'user_'. $user_id );

            // Prevent form resubmission
            unset( $mytwitter );
            unset( $display_contact );
            unset( $position );
            unset( $description );
            unset( $_SESSION['token3'] );

            // Success message
            header( 'Location:' . esc_url( $the_my_account_url . '?active_tab=officer-details&success=1' ) );
        }

    // remote form posting attempted
    } else {
        unset( $_SESSION['token3'] );
        ?>
        <div id="toast-container" data-type="error">
            <i class="icon-notification"></i>
            <p>
                Something went wrong. Are you trying to do something you're not suppose to? 
                <a href="<?php echo esc_url( $the_my_account_url . '?active_tab=officer-details' ); ?>" rel="noopener">Here is a link to refresh.</a>
            </p>
        </div>
        <?php
    }
}

if( isset( $_REQUEST['active_tab'], $_REQUEST['success'] ) && $_REQUEST['active_tab'] == 'officer-details' && $_REQUEST['success'] == 1 ){ 
    $theme = wp_get_theme(); ?>
    <div id="toast-container" data-type="confirm">
        <i class="icon-checkmark"></i>
        <p>Officer details have successfully been updated.</p>
    </div>
<?php }

// Create a session token
$token3 = md5( uniqid(rand(), true) );
$_SESSION['token3'] = $token3;

// Get profile information
$display_contact = ( $display_contact   = get_field( 'display_contact_info', 'user_'. $user_id ) )    ? $display_contact  : 'email+phone';
$description = ( $description          = get_field( 'user_description', 'user_' . $user_id ) )        ? $description      : '';

if( isset( $display_contact ) ){
    $display_contact = sanitize_text_field( $display_contact );
}
if( isset( $description ) ){
    $description = clean_hex( $description );
    $description = wp_kses( $description, $allowed_tags );
}



// Warning for anyone trying to use no JavaScript ?>
<noscript><div id="toast-container" data-type="error">
    <i class="icon-notification"></i>
    <p>Please correct the following error:</p>
    <p>Your browser needs to support JavaScript in order to use this page.</p>
</div></noscript>

<div class="block-columns">
    <div class="column user-list-container" data-column-count="1">

        <?php 
        $args = [
            'ID'            => $args['data']->ID,
            'display_name'  => $args['data']->display_name,
            'user_email'    => $args['data']->user_email,
            'roles'         => $args['data']->roles,
            'numb_row'      => 100,
        ];

        get_template_part( 'includes/blocks/user-list/block-user', null, $args );
        ?>

    </div>
    <div class="column">
        <form name="officer-details" action="<?php echo esc_url( $the_my_account_url . '?active_tab=officer-details' ); ?>" method="post" class="account-form-wrapper">
        
            <div class="input-wrap">
                <input type="hidden" name="token3" value="<?php echo $token3; ?>" />
            </div>

            <div class="input-wrap select">
                <label for="display_contact_info">Display Contact Info</label>
                <select name="display_contact_info" id="display_contact_info">
					<option value="email+phone"<?php echo ( $display_contact == 'email+phone' ) ? ' selected="selected"' : ''; ?>>Email & Phone</option>
					<option value="phone"<?php echo ( $display_contact == 'phone' ) ? ' selected="selected"' : ''; ?>>Phone</option>
					<option value="email"<?php echo ( $display_contact == 'email' ) ? ' selected="selected"' : ''; ?>>Email</option>
					<option value="none"<?php echo ( $display_contact == 'none' ) ? ' selected="selected"' : ''; ?>>None</option>
				</select>
                <i class="icon-chevron-left"></i>
            </div>

            <?php
            if( !empty($position_terms) ){ 
                ?>
                <div class="input-wrap select">
                    <label for="positions">Position(s)</label>
                    <?php shenk_custom_form_field('positions', $position_terms, $user_id, 'dropdown'); ?>
                    <i class="icon-chevron-left"></i>
                </div>
            <?php } else { ?>
                <div class="input-wrap select">
                    <label for="positions">Position(s)</label>
                    <div>No Positions to Pick From</div>
                    <i class="icon-chevron-left"></i>
                </div>
            <?php } ?>

            <div class="input-wrap description">
                <?php 
                if (class_exists('ACF')) {
                    acf_form(array(
                        'id'       	=> 'user-front-end-form',
                        'post_id'  	=> 'user_' . $user_id,
                        'form'		=> false,
                        'return'	=> esc_url( $the_my_account_url . '?active_tab=officer-details&success=1' ),
                        'honeypot' 	=> false,
                        'instruction_placement' => 'field',
                        'fields'	=> ['field_654fb9a4b25ec']
                    ));
                }
                ?>
            </div>

            <?php // Prevent users from tabbing to the field + disabling autocomplete ?>
            <input type="text" name="mytwitter" id="mytwitter" 
                value="<?php echo isset( $_POST['mytwitter'] ) ? sanitize_text_field($mytwitter) : null; ?>" 
                maxlength="50" 
                tabindex="-1" 
                autocomplete="off" 
                spellcheck="false" 
            />

            <?php // Button is disabled initially for a few seconds ?>
            <div class="wp-block-button">
                <button class="wp-block-button__link wp-element-button" id="submit_officer_details" type="submit" name="submit_officer_details" disabled>Please Wait..</button>
            </div>
        </form>
    </div>
</div>
