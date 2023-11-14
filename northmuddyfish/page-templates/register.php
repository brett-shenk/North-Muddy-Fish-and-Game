<?php
/**
 * Template Name: User Registration
 */

// Scripts for the page
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_script('my-account-js');
    wp_enqueue_style('my-account');
});

// Remove default page content
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ ?>
    <header class="entry-header alignfull" role="region" aria-label="Page Banner">
	    <h1 class="entry-title">
		    <span>New Users</span>
            <div id="page-title"></div>
		</h1>
	</header>
<?php }, 1);

// Wrapper
add_action('entry', 'wrapper_open', 5);
add_action('entry', 'wrapper_close', 20);

// The content
add_action('entry', 'the_login_page', 8);
function the_login_page(){
    global $wpdb, $user_ID;
    $first_name = $last_name = $email = $username = $password = $password_confirm = $mywebsite = '';

    if( class_exists( 'ACF' ) && ! empty( get_field('user_login_page', 'options') ) ){
        $site_login_url = get_field('user_login_page', 'options');
        $site_login_url = esc_url( get_permalink( $site_login_url->ID ) );
    } else {
        $site_login_url = esc_url( home_url('wp-login.php') );
    }

    if( class_exists( 'ACF' ) && ! empty( get_field('user_register_page', 'options') ) ){
        $site_register_url = get_field('user_register_page', 'options');
        $site_register_url = esc_url( get_permalink( $site_register_url->ID ) );
    } else {
        $site_register_url = esc_url( home_url('wp-login.php?action=register') );
    }

    session_start();

    // Check whether the user is already logged in and redirect them
    if( $user_ID ){
        header( 'Location:' . esc_url( home_url('/') ) );

    // Otherwise let's proceed with a new instance
    } else {
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            if ( $_POST['token'] == $_SESSION['token'] ){

                $error = new WP_Error();

                /**
                 * Form Validation Helper
                 */
                $chars_valid = array(
                    'main' => array('-', '_', '&', '#', ';', '~', '`', '.'),
                    'un' => array(
                        '-', '_', '&', '#', '!', '@', 
                        ';', ':', '`', '~', '=',
                        '?', '$', '*', '+',
                        ' ', '.', ',',
                    ),
                    'pw' => array(
                        '-', '_', '&', '#', '!', '@', 
                        '^', '(', ')', '[', ']', '{', '}', 
                        ';', ':', '`', '~', '=', '<', '>', 
                        '?', '|', '$', '%', '*', '+', "'", 
                        '"', ' ', '.', ',', '/', '\\',
                    ),
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
                    $error->add('fname-invalid', 'Please enter your first name.');
                } else if( ! ctype_alnum(str_replace($chars_valid['main'], '', $first_name)) ){
                    $error->add('fname-charset', 'Invalid characters in your first name.');
                } else if( strlen( $first_name ) > 40 ){
                    $error->add('fname-maxlength', 'First name needs to be less than 40 characters.');
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
                    $error->add('lname-invalid', 'Please enter your last name.');
                } else if( ! ctype_alnum(str_replace($chars_valid['main'], '', $last_name)) ){
                    $error->add('lname-charset', 'Invalid characters in your last name.');
                } else if( strlen( $last_name ) > 40 ){
                    $error->add('lname-maxlength', 'Last name needs to be less than 40 characters.');
                }

                /**
                 * Check email address is present and valid
                 * 
                 * @var string $email  What happens:
                 *      1a. Remove hexadecimal characters
                 *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
                 *      1c. Strip HTML and PHP tags
                 *      1d. Encodes the <, >, &, " and ' characters
                 *      2. Check for being empty
                 *      3. Check minlength is within required size
                 *      3. Check it's a valid email
                 *      4. Check email doesn't already exist
                 *      5. Check maxlength is within required size
                 */
                if( isset( $_POST['email'] ) ){
                    $email = trim( $email );
                    $email = strip_tags( $email );
                    $email = esc_attr( $email );
                }
                if( empty($email) ){
                    $error->add('email-empty', 'Please enter an email address.');
                } else if( 0 === preg_match("/.{6,}/", $email) ){
                    $error->add('email-invalid-length', 'Email address needs be at least 6 characters.');
                } else if( ! is_valid_email_address($email) ){
                    $error->add('email-valid', 'Please enter a valid email address.');
                } else if( email_exists( $email ) ) {
                    $error->add('email-exists', 'Please enter a different email address.');
                } else if( strlen( $email ) > 160 ){
                    $error->add('email-maxlength', 'Email address needs to be less than 160 characters.');
                }

                /**
                 * Check username is present and not already in use
                 * 
                 * @var string $username  What happens:
                 *      1a. Remove hexadecimal characters
                 *      1b. Removes tags, percent-encoded characters, HTML entities
                 *      2. Check for being empty
                 *      3. Check username doesn't already exist
                 *      4. Allow alpha characters only
                 *      5. Check maxlength is within required size
                 */
                if( isset( $_POST['username'] ) ){
                    $username = clean_hex( $_POST['username'] );
                    $username = sanitize_user( $username );
                }
                if( empty( $username ) ){
                    $error->add('username-invalid', 'Please enter a username.');
                } else if( username_exists( $username ) ) {
                    $error->add('username-exists', 'Please enter a different username.');
                } else if( ! ctype_alnum(str_replace($chars_valid['un'], '', $username)) ){
                    $error->add('username-charset', 'Invalid characters in the username.');
                } else if( strlen( $username ) > 50 ){
                    $error->add('username-maxlength', 'Username needs to be less than 50 characters.');
                }

                /**
                 * Check password is valid
                 * 
                 * @var string $password  What happens:
                 *      1a. Remove hexadecimal characters
                 *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
                 *      1c. Strip HTML and PHP tags
                 *      1d. Encodes the <, >, &, " and ' characters
                 *      2. Check for being empty
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
                if( isset( $_POST['password'] ) ){
                    $password = clean_hex( $_POST['password'] );

                    // Inform the user about a requirement
                    if( str_starts_with($password, ' ') || str_ends_with($password, ' ') ){
                        $error->add('password-spaces', 'Passwords can\'t start or end with a space.');
                    }

                    $password = trim( $password );
                    $password = strip_tags( $password );
                    $password = esc_attr( $password );
                }
                if( empty($password) ){
                    $error->add('password-empty', 'Please enter a password.');
                } else if( 0 === preg_match("/.{6,}/", $password) ){
                    $error->add('password-invalid', 'Password must be at least six characters.');
                } else if( ! ctype_alnum( str_replace( $chars_valid['pw'], '', $password ) ) ){
                    $error->add('password-charset', 'Invalid characters in the password.');
                } else if( strlen( $password ) > 50 ){
                    $error->add('password-maxlength', 'Password needs to be less than 50 characters.');
                }

                if( $_POST['password_confirmation'] ){
                    $password_confirm = clean_hex( $_POST['password_confirmation'] );
                    $password_confirm = trim( $password_confirm );
                    $password_confirm = strip_tags( $password_confirm );
                    $password_confirm = esc_attr( $password_confirm );
                }
                if( 0 !== strcmp($password, $password_confirm) ){
                    $error->add('password-confirm', "Password and password confirmation don't not match.");
                }

                /**
                 * Check honeypot field after making sure nothing nefarious leaks through
                 */
                if( isset( $_POST['mywebsite'] ) ){
                    $mywebsite = clean_hex( $_POST['mywebsite'] );
                    $mywebsite = sanitize_text_field( $mywebsite );
                }
                if( ! empty($mywebsite) ){
                    $error->add('honeypot', 'Unexpected error.');
                }

                /**
                 * Show the error(s)
                 */
                if (count($error->get_error_codes())) {
                    unset( $password );
                    unset( $password_confirm );
                    unset( $_SESSION['token'] );
                    ?>
                    <div id="toast-container" data-type="error">
                        <i class="icon-notification"></i>
                        <p>Please correct the following error(s):</p>
                        <ul>
                            <?php echo '<li>' . implode( '</li><li>', $error->get_error_messages() ) . '</li>'; ?>
                        </ul>
                    </div>
                    <?php

                /**
                 * create the user
                 */
                } else {
                    wp_insert_user( array(
                        'user_pass'     => $password,
                        'user_login'    => $username,
                        'user_email'    => $email,
                        'display_name'  => $first_name .' '. $last_name,
                        'nickname'      => $first_name,
                        'first_name'    => $first_name,
                        'last_name'     => $last_name,
                        'role'          => get_option( 'default_role' ),
                        'meta_input'    => array(
                            array(
                                'key'   => 'display_contact_info',
                                'value' => 'email+phone'
                            ),
                        )
                    ) );

                    // Prevent form resubmission
                    unset( $first_name );
                    unset( $last_name );
                    unset( $email );
                    unset( $username );
                    unset( $password );
                    unset( $password_confirm );
                    unset( $mywebsite );
                    unset( $_SESSION['token'] );

                    // Success Message
                    header( 'Location:' . $site_login_url . '?success=1&u=' . $username );
                }

            // remote form posting attempted
            } else {
                unset( $password );
                unset( $password_confirm );
                unset( $_SESSION['token'] );
                ?>
                <div id="toast-container" data-type="error">
                    <i class="icon-notification"></i>
                    <p>
                        Something went wrong. Are you trying to do something you're not suppose to? 
                        <a href="<?php echo $site_register_url; ?>" rel="noopener">Here is a link to refresh.</a>
                    </p>
                </div>
                <?php
            }
        }
    }

    // Create a session token
    $token = md5( uniqid(rand(), true) );
    $_SESSION['token'] = $token;
    
    
    
    // Warning for anyone trying to use no JavaScript ?>
    <noscript><div id="toast-container" data-type="error">
        <i class="icon-notification"></i>
        <p>Please correct the following error:</p>
        <p>Your browser needs to support JavaScript in order to use this page.</p>
    </div></noscript>

    <form name="register" action="<?php echo $site_register_url; ?>" method="post" class="account-form-wrapper background">
        <h2>Register</h2>

        <input type="hidden" name="token" value="<?php echo $token; ?>" />

        <div class="two-col">
            <div class="input-wrap first_name">
                <label for="first_name">
                    First Name<span class="txt-red">*</span>
                </label>
                <input type="text" name="first_name" id="first_name" 
                    value="<?php echo isset( $_POST['first_name'] ) ? $first_name : null; ?>" 
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
                    value="<?php echo isset( $_POST['last_name'] ) ? $last_name : null; ?>" 
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

        <div class="input-wrap email">
            <label for="email">
                Email<span class="txt-red">*</span>
            </label>
            <input type="email" name="email" id="email" 
                value="<?php echo isset( $_POST['email'] ) ? $email : null; ?>" 
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

        <div class="input-wrap username infobox">
            <label for="username">
                Username<span class="txt-red">*</span>
            </label>
            <input type="text" name="username" id="username" 
                value="<?php echo isset( $_POST['username'] ) ? $username : null; ?>" 
                maxlength="50" 
                required 
                autocomplete="username" 
                autocapitalize="off" 
                spellcheck="false" 
                data-valueMissing="Please enter a username" 
                data-tooLong="Username needs to be less than 50 characters." 
                data-typeMismatch="Invalid characters in your username" 
                data-badInput="Enter a different username" 
            />
            <div class="btn btn-primary tooltip"><i class="icon-notification"></i>
                <div class="top">
                    <h3>Usernames can not contain:</h3>
                    <ul>
                        <li>Single or double quotes</li>
                        <li>Slashes of any kind</li>
                        <li>Percentages</li>
                        <li>Brackets or parentheses of any kind</li>
                    </ul>
                    <i></i>
                </div>
            </div>
        </div>

        <div class="input-wrap password">
            <label for="password">
                Password<span class="txt-red">*</span>
            </label>
            <input type="password" name="password" id="password" 
                value="" 
                minlength="6" 
                maxlength="50" 
                required 
                class="password" 
                autocomplete="current-password" 
                autocapitalize="off" 
                spellcheck="false" 
                data-valueMissing="Please enter a password" 
                data-tooLong="Password needs to be less than 50 characters" 
                data-tooShort="Your password should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your password" 
                data-badInput="Enter a different password" 
            />
        </div>

        <div class="input-wrap password-confirm">
            <label for="password_confirmation">
                Confirm Password<span class="txt-red">*</span>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                value="" 
                minlength="6" 
                maxlength="50" 
                required 
                class="password" 
                autocomplete="new-password" 
                autocapitalize="off"
                spellcheck="false" 
                data-valueMissing="Please enter a password and then confirm it" 
                data-tooLong="Password confirmation needs to be less than 50 characters" 
                data-tooShort="Your password confirmation should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your password confirmation" 
                data-badInput="Password confirmation is incorrect" 
            />
        </div>

        <?php // Prevent users from tabbing to the field + disabling autocomplete ?>
        <input type="text" name="mywebsite" id="mywebsite" 
            value="<?php echo isset( $_POST['mywebsite'] ) ? sanitize_text_field($mywebsite) : null; ?>" 
            maxlength="50" 
            tabindex="-1" 
            autocomplete="off" 
            spellcheck="false" 
        />

        <?php // Button is disabled initially for a few seconds ?>
        <div class="wp-block-button">
            <button class="wp-block-button__link wp-element-button" id="submit" type="submit" name="submit" disabled>Please Wait..</button>
        </div>
    </form>
    <?php
}

shenk_init();       // NOTE: This must be last on the page
