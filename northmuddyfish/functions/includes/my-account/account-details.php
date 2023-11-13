<?php
/**
 * My Account - Account Details Page
 * 
 * @var array   $args['data'] -> [ID, user_login, user_pass, user_email, display_name, roles]
 */

$first_name = $last_name = $nickname = $display_name = $email = $phone = $password_current = $password_new = $password_confirm = $myfacebook = '';
$error_acc_details = new WP_Error();
$user_id = $args['data']->ID;

// Display Name
$public_display                     = array();
$public_display['display_nickname'] = $args['data']->nickname;
$public_display['display_username'] = $args['data']->user_login;

if ( ! empty( $args['data']->first_name ) ) {
	$public_display['display_firstname'] = $args['data']->first_name;
}
if ( ! empty( $args['data']->last_name ) ) {
	$public_display['display_lastname'] = $args['data']->last_name;
}
if ( ! empty( $args['data']->first_name ) && ! empty( $args['data']->last_name ) ) {
	$public_display['display_firstlast'] = $args['data']->first_name . ' ' . $args['data']->last_name;
	$public_display['display_lastfirst'] = $args['data']->last_name . ' ' . $args['data']->first_name;
}
if ( ! in_array( $args['data']->display_name, $public_display, true ) ) { // Only add this if it isn't duplicated elsewhere.
	$public_display = array( 'display_displayname' => $args['data']->display_name ) + $public_display;
}

$public_display = array_map( 'trim', $public_display );
$public_display = array_unique( $public_display );

// Has this form's submit button been clicked?
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_account_details']) ){
    if ( $_POST['token1'] == $_SESSION['token1'] ){
        /**
         * Form Validation Helper
         */
        $chars_valid = array(
            'main' => array('-', '_', '&', '#', ';', '~', '`', '.'),
            'pw' => array(
                '-', '_', '&', '#', '!', '@', 
                '^', '(', ')', '[', ']', '{', '}', 
                ';', ':', '`', '~', '=', '<', '>', 
                '?', '|', '$', '%', '*', '+', "'", 
                '"', ' ', '.', ',', '/', '\\',
            ),
            'tel' => array('-', '_', '.', '+', '(', ')', ' ')
        );

        /**
         * @var string $first_name  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Check for being empty
         *      3. Allow alpha characters only
         *      4. Check maxlength is within required size
         */
        if( isset( $_POST['first_name'] ) ){
            $first_name = clean_hex( $_POST['first_name'] );
            $first_name = sanitize_text_field( $first_name );
        }
        if( empty( $first_name ) ){
            $error_acc_details->add('fname-invalid', 'Please enter your first name.');
        } else if( ! ctype_alnum(str_replace($chars_valid['main'], '', $first_name)) ){
            $error_acc_details->add('fname-charset', 'Invalid characters in your first name.');
        } else if( strlen( $first_name ) > 40 ){
            $error_acc_details->add('fname-maxlength', 'First name needs to be less than 40 characters.');
        }

        /**
         * @var string $last_name  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Check for being empty
         *      3. Allow alpha characters only
         *      4. Check maxlength is within required size
         */
        if( isset( $_POST['last_name'] ) ){
            $last_name = clean_hex( $_POST['last_name'] );
            $last_name = sanitize_text_field( $last_name );
        }
        if( empty( $last_name ) ){
            $error_acc_details->add('lname-invalid', 'Please enter your last name.');
        } else if( ! ctype_alnum(str_replace($chars_valid['main'], '', $last_name)) ){
            $error_acc_details->add('lname-charset', 'Invalid characters in your last name.');
        } else if( strlen( $last_name ) > 40 ){
            $error_acc_details->add('lname-maxlength', 'Last name needs to be less than 40 characters.');
        }

        /**
         * @var string $nickname  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Allow alpha characters only
         *      3. Check minlength is within required size
         *      4. Check maxlength is within required size
         */
        if( isset( $_POST['nickname'] ) ){
            $nickname = clean_hex( $_POST['nickname'] );
            $nickname = sanitize_text_field( $nickname );
        }
        if( empty($nickname) ){
            $error_acc_details->add('nickname-invalid', 'Please enter a nickname.');
        } else if( ! ctype_alnum(str_replace($chars_valid['main'], '', $nickname)) ){
            $error_acc_details->add('nickname-charset', 'Invalid characters in your nickname.');
        } else if( 0 === preg_match("/.{3,}/", $nickname) ){
            $error_acc_details->add('nickname-minlength', 'Nickname must be at least 3 characters.');
        } else if( strlen( $nickname ) > 160 ){
            $error_acc_details->add('nickname-maxlength', "Nickname needs to be less than 160 characters.");
        }

        /**
         * @var string $display_name  What happens:
         *      1. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Check for being empty
         *      3. Make sure what is selected is already in the list of options for this profile
         */
        if( isset( $_POST['display_name'] ) ){
            $display_name = sanitize_text_field( $_POST['display_name'] );
        }
        if( empty($display_name) ){
            $error_acc_details->add('display-name-empty', "Please select a display name.");
        } else if( ! in_array($display_name, $public_display) ){
            $error_acc_details->add('display-name-incorrect', "Please select a proper display name.");
        }

        /**
         * @var string $email  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
         *      1c. Strip HTML and PHP tags
         *      1d. Encodes the <, >, &, " and ' characters
         *      2. Check for being empty
         *      3. Check minlength is within required size
         *      4. Checking it's a valid email
         *      5. Check email entered doesn't match the account email before..
         *          - Check email doesn't already exist
         *      6. Check maxlength is within required size
         */
        if( isset( $_POST['email'] ) ){
            $email = clean_hex( $_POST['email'] );
            $email = trim( $email, '\n\r\t\v\x00' );
            $email = strip_tags( $email );
            $email = esc_attr( $email );
        }
        if( empty($email) ){
            $error_acc_details->add('email-empty', 'Please enter an email address.');
        } else if( 0 === preg_match("/.{6,}/", $email) ){
            $error_acc_details->add('email-invalid-length', 'Email address needs be at least 6 characters.');
        } else if( ! is_valid_email_address($email) ){
            $error_acc_details->add('email-valid', 'Please enter a valid email address.');
        } else if( $email !== $args['data']->user_email ){ 
            if( email_exists( $email ) ) {
                $error_acc_details->add('email-exists', 'Please enter a different email address.');
            }
        } else if( strlen( $email ) > 160 ){
            $error_acc_details->add('email-maxlength', 'Email address needs to be less than 160 characters.');
        }

        /**
         * @var string $phone  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags,
         *          Removes line breaks, tabs, and extra whitespace, Strips percent-encoded characters
         *      2. Check phone number is not empty
         *      3. Check length is at least 7 characters long
         *      4. Allow numbers and a few special characters
         *      5. Check maxlength is within required size
         */
        if( isset( $_POST['phone'] ) ){
            $phone = clean_hex( $_POST['phone'] );
            $phone = sanitize_text_field( $phone );
        }
        if( ! empty($phone) ){
            if( 0 === preg_match("/.{7,}/", $phone) ){
                $error_acc_details->add('phone-minlength', 'Phone number needs to be at least 7 characters.');
            } else if( strlen( $phone ) > 30 ){
                $error_acc_details->add('phone-maxlength', "Phone number needs to be less than 30 characters.");
            } else if( ! ctype_digit(str_replace($chars_valid['tel'], '', $phone)) ){
                $error_acc_details->add('phone-charset', 'Invalid characters in your phone number.');
            }
        }

        /**
         * Did the user fill in any of the password fields? They should be empty unless they are to be updated
         */
        if( ! empty($_POST['new-password']) || ! empty($_POST['password_confirmation']) || ! empty($_POST['current-password']) ){
            $possible_new_password = true;
        } else {
            $possible_new_password = false;
        }

        /**
         * @var string $password_current  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
         *      1c. Strip HTML and PHP tags
         *      1d. Encodes the <, >, &, " and ' characters
         *      2. Check password fields are not empty
         *      3. Check length is at least 6 characters long
         *      4. Allow alpha characters only
         *      5. Check maxlength is within required size
         *      6. Check the password entered matches the current account password
         */
        if( $possible_new_password ){
            if( isset( $_POST['current-password'] ) ){
                $password_current = clean_hex( $_POST['current-password'] );
                $password_current = trim( $password_current, '\n\r\t\v\x00' );
                $password_current = strip_tags( $password_current );
                $password_current = esc_attr( $password_current );
            }
            if( empty($password_current) ){
                $error_acc_details->add('c-password-empty', 'Please enter your current password if you want it changed.');
            } else if( 0 === preg_match("/.{6,}/", $password_current) ){
                $error_acc_details->add('c-password-invalid', 'Current password must be at least 6 characters.');
            } else if( ! ctype_alnum( str_replace( $chars_valid['pw'], '', $password_current ) ) ){
                $error_acc_details->add('c-password-charset', 'Invalid characters in the current password.');
            } else if( strlen( $password_current ) > 50 ){
                $error_acc_details->add('c-password-maxlength', 'Current password needs to be less than 50 characters.');
            } else{
                if( wp_check_password( $password_current, $args['data']->user_pass, $user_id ) ){
                } else {
                    $error_acc_details->add('c-password-match', "What was entered for the current password doesn't match our records, please try again.");
                }
            }
        }

        /**
         * Check password is valid
         * 
         * @var string $password_new  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
         *      1c. Strip HTML and PHP tags
         *      1d. Encodes the <, >, &, " and ' characters
         *      2. Check password fields are not empty
         *      3. Check length is at least 6 characters long
         *      4. Allow alpha characters only
         *      5. Check maxlength is within required size
         * 
         * @var string $password_confirm  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
         *      1c. Strip HTML and PHP tags
         *      1d. Encodes the <, >, &, " and ' characters
         *      2. Check password confirmation matches
         */
        if( $possible_new_password ){
            if( isset( $_POST['new-password'] ) ){
                $password_new = clean_hex( $_POST['new-password'] );
                $password_new = trim( $password_new, '\n\r\t\v\x00' );
                $password_new = strip_tags( $password_new );
                $password_new = esc_attr( $password_new );
            }
            if( empty($password_new) ){
                $error_acc_details->add('password-empty', 'Please enter your new password if you want it changed.');
            } else if( 0 === preg_match("/.{6,}/", $password_new) ){
                $error_acc_details->add('password-invalid', 'New password must be at least 6 characters.');
            } else if( ! ctype_alnum( str_replace( $chars_valid['pw'], '', $password_new ) ) ){
                $error_acc_details->add('password-charset', 'Invalid characters in the new password.');
            } else if( strlen( $password_new ) > 50 ){
                $error_acc_details->add('password-maxlength', 'New password needs to be less than 50 characters.');
            }

            if( $_POST['password_confirmation'] ){
                $password_confirm = clean_hex( $_POST['password_confirmation'] );
                $password_confirm = trim( $password_confirm, '\n\r\t\v\x00' );
                $password_confirm = strip_tags( $password_confirm );
                $password_confirm = esc_attr( $password_confirm );
            }
            if( 0 !== strcmp($password_new, $password_confirm) ){
                $error_acc_details->add('password-confirm', "New password and password confirmation do not match.");
            }
        }

        /**
         * Check honeypot field after making sure nothing nefarious leaks through
         */
        if( isset( $_POST['myfacebook'] ) ){
            $myfacebook = clean_hex( $_POST['myfacebook'] );
            $myfacebook = sanitize_text_field( $myfacebook );
        }
        if( ! empty($myfacebook) ){
            $error_acc_details->add('honeypot', 'Unexpected error.');
        }
        
        /**
         * Show the error(s)
         */
        if (count($error_acc_details->get_error_codes())) { 
            unset( $password_current );
            unset( $password_new );
            unset( $password_confirm );
            unset( $_SESSION['token1'] );
            ?>
            <div id="toast-container" data-type="error">
                <i class="icon-notification"></i>
                <p>Please correct the following error(s):</p>
                <ul>
                    <?php echo '<li>' . implode( '</li><li>', $error_acc_details->get_error_messages() ) . '</li>'; ?>
                </ul>
            </div>
            <?php

        /**
         * Update User Profile
         */
        } else {

            update_user_meta( $user_id, 'first_name', $first_name );
            update_user_meta( $user_id, 'last_name', $last_name );
            update_user_meta( $user_id, 'nickname', $nickname );
            update_user_meta( $user_id, 'phone', $phone );

            wp_update_user( array('ID' => $user_id, 'display_name'  => $display_name) );
            wp_update_user( array('ID' => $user_id, 'user_email'    => $email) );

            if( ! empty($password_new) ){
                wp_update_user( array('ID' => $user_id, 'user_pass' => $password_new) );
            }

            // Prevent form resubmission
            unset( $meta );
            unset( $first_name );
            unset( $last_name );
            unset( $nickname );
            unset( $display_name );
            unset( $email );
            unset( $phone );
            unset( $possible_new_password );
            unset( $password_current );
            unset( $password_new );
            unset( $password_confirm );
            unset( $myfacebook );
            unset( $_SESSION['token1'] );

            // Success message
            header( 'Location:' . home_url('/my-account/?active_tab=account-details&success=1') );
        }

    // remote form posting attempted
    } else {
        unset( $password_current );
        unset( $password_new );
        unset( $password_confirm );
        unset( $_SESSION['token1'] );
        ?>
        <div id="toast-container" data-type="error">
            <i class="icon-notification"></i>
            <p>
                Something went wrong. Are you trying to do something you're not suppose to? 
                <a href="<?php echo esc_url( home_url('/my-account/?active_tab=account-details') ); ?>" rel="noopener">Here is a link to refresh.</a>
            </p>
        </div>
        <?php
    }
}

if( isset( $_REQUEST['active_tab'], $_REQUEST['success'] ) && $_REQUEST['active_tab'] == 'account-details' && $_REQUEST['success'] == 1 ){ 
    $theme = wp_get_theme(); ?>
    <div id="toast-container" data-type="confirm">
        <i class="icon-checkmark"></i>
        <p>My account details have successfully been updated.</p>
    </div>
<?php }

// Create a session token
$token1 = md5( uniqid(rand(), true) );
$_SESSION['token1'] = $token1;



// Warning for anyone trying to use no JavaScript ?>
<noscript><div id="toast-container" data-type="error">
    <i class="icon-notification"></i>
    <p>Please correct the following error:</p>
    <p>Your browser needs to support JavaScript in order to use this page.</p>
</div></noscript>

<form name="account-details" action="<?php echo esc_url( home_url('/my-account/?active_tab=account-details') ); ?>" method="post" class="account-form-wrapper">
    <br />
    
    <input type="hidden" name="token1" value="<?php echo $token1; ?>" />
    
    <div class="two-col">
        <div class="input-wrap first_name">
            <label for="first_name">
                First Name<span class="txt-red">*</span>
            </label>
            <input type="text" name="first_name" id="first_name" 
                value="<?php echo isset( $_POST['first_name'] ) ? $first_name : sanitize_text_field( $args['data']->first_name ); ?>" 
                maxlength="40" 
                required 
                autocomplete="given-name" 
                data-valueMissing="Please enter your first name" 
                data-tooLong="First name needs to be under 40 characters" 
                data-typeMismatch="Invalid characters in your first name" 
                data-badInput="Enter a valid first name" 
            />
        </div>
        <div class="input-wrap last_name">
            <label for="last_name">
                Last Name<span class="txt-red">*</span>
            </label>
            <input type="text" name="last_name" id="last_name" 
                value="<?php echo isset( $_POST['last_name'] ) ? $last_name : sanitize_text_field( $args['data']->last_name ); ?>" 
                maxlength="40" 
                required 
                autocomplete="family-name" 
                data-valueMissing="Please enter your last name" 
                data-tooLong="Last name needs to be under 40 characters" 
                data-typeMismatch="Invalid characters in your last name" 
                data-badInput="Enter a valid last name" 
            />
        </div>
    </div>

    <div class="input-wrap nickname">
        <label for="nickname">
            Nickname<span class="txt-red">*</span>
        </label>
        <input type="text" name="nickname" id="nickname" 
            value="<?php echo isset( $_POST['nickname'] ) ? $nickname : sanitize_text_field( $args['data']->nickname ); ?>" 
            minlength="3" 
            maxlength="160" 
            required 
            autocomplete="nickname" 
            data-valueMissing="Please enter a nickname" 
            data-tooLong="Nickname needs to be under 160 characters" 
            data-tooShort="Nickname needs to be at least 3 characters" 
            data-typeMismatch="Enter a valid nickname" 
            data-badInput="Enter a valid nickname" 
        />
    </div>

    <div class="input-wrap select">
        <label for="display_name">Display Name</label>
        <select name="display_name" id="display_name">
            <?php foreach ( $public_display as $id => $item ) : ?>
				<option <?php selected( $args['data']->display_name, $item ); ?>><?php echo $item; ?></option>
			<?php endforeach; ?>
		</select>
        <i class="icon-chevron-left"></i>
    </div>
    <div class="em">This will be how your name will be displayed throughout the site</div>
    
    <div class="input-wrap email">
        <label for="email">
            Email<span class="txt-red">*</span>
        </label>
        <input type="email" name="email" id="email" 
            value="<?php echo isset( $_POST['email'] ) ? $email : sanitize_text_field( $args['data']->user_email ); ?>" 
            minlength="6" 
            maxlength="160" 
            required 
            autocomplete="email" 
            autocapitalize="off" 
            autocorrect="off" 
            data-valueMissing="Please enter your email address" 
            data-tooLong="Email address needs to be under 160 characters" 
            data-tooShort="Email address needs be at least 6 characters" 
            data-typeMismatch="Enter a valid email address" 
            data-badInput="Enter a valid email address" 
            emailMismatch="Enter a valid email address" 
        />
    </div>

    <div class="input-wrap phone">
        <label for="phone">
            Phone Number
        </label>
        <input type="tel" name="phone" id="phone" 
            value="<?php echo ! empty( $_POST['phone'] ) ? $phone : sanitize_text_field( get_user_meta($user_id, 'phone', true) ); ?>" 
            maxlength="30" 
            autocomplete="tel" 
            data-tooLong="Phone number needs to be less than 30 characters" 
            data-tooShort="Phone number needs to be at least 7 characters" 
            data-typeMismatch="Enter a valid phone number" 
            data-badInput="Enter a valid phone number" 
            telMismatch="Enter a valid phone number" 
        />
    </div>

    <fieldset>
        <legend>Password Change</legend>
        <div class="input-wrap current-password">
            <label for="current-password">Current Password</label>
            <input type="password" name="current-password" id="current-password" 
                value="" 
                minlength="6" 
                maxlength="50" 
                class="password" 
                autocomplete="current-password" 
                autocapitalize="off"
                spellcheck="false" 
                data-tooLong="Password needs to be less than 50 characters" 
                data-tooShort="Your password should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your current password" 
                data-badInput="Enter a different current password" 
            />
        </div>
        <div class="em">Leave blank to leave unchanged</div>
        <div class="input-wrap password pw-infobox">
            <label for="new-password">New Password</label>
            <input type="password" name="new-password" id="new-password" 
                value="" 
                minlength="6" 
                maxlength="50" 
                class="password" 
                autocomplete="new-password" 
                autocapitalize="off"
                spellcheck="false" 
                data-tooLong="New password needs to be less than 50 characters" 
                data-tooShort="Your new password should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your new password" 
                data-badInput="Enter a different new password" 
            />
        </div>
        <div class="em">Leave blank to leave unchanged</div>
        <div class="input-wrap password-confirm">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                value="" 
                minlength="6" 
                maxlength="50" 
                class="password" 
                autocomplete="new-password" 
                autocapitalize="off"
                spellcheck="false" 
                data-tooLong="Password confirmation needs to be less than 50 characters" 
                data-tooShort="Your password confirmation should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your password confirmation" 
                data-badInput="Password confirmation is incorrect" 
            />
        </div>
        <div class="em">Leave blank to leave unchanged</div>
    </fieldset>

    <?php // Prevent users from tabbing to the field + disabling autocomplete ?>
    <input type="text" name="myfacebook" id="myfacebook" 
        value="<?php echo isset( $_POST['myfacebook'] ) ? sanitize_text_field($myfacebook) : null; ?>" 
        maxlength="50" 
        tabindex="-1" 
        autocomplete="off" 
        spellcheck="false" 
    />

    <?php // Button is disabled initially for a few seconds ?>
    <div class="wp-block-button">
        <button class="wp-block-button__link wp-element-button" id="submit_account_details" type="submit" name="submit_account_details" disabled>Please Wait..</button>
    </div>
</form>
