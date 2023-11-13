<?php
/**
 * Template Name: User Login
**/

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
            <span><?php echo get_the_title(); ?></span>
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
    $username = $password = $mywebsite = '';

    session_start();

    // On form submission
    if( $_POST ){
        if ( $_POST['token'] == $_SESSION['token'] ){
        
            global $wpdb;
            $error = new WP_Error();

            /**
             * Form Validation Helper
             */
            $chars_valid = array(
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
                )
            );

            /**
             * Check username is valid
             * 
             * @var string $username  What happens:
             *      1. First we roughly determine if it's an email or the username
             *          - It's handled this way due to this field using email/username and email's are quite broad
             *      2a. If it's an email... 
             *          - Remove hexadecimal characters
             *          - Remove tab, new line, carriage return, the nul-byte, and vertical tab.
             *          - Strip HTML and PHP tags
             *          - Encodes the <, >, &, " and ' characters
             *      2b. If it's a username...
             *          - Remove hexadecimal characters
             *          - Removes tags, percent-encoded characters, HTML entities
             *      3. Check for being empty
             *      4. Verify what's provided is valid
             *      5. Check maxlength is within required size
             */
            if( preg_match('/^\\S+@\\S+\\.\\S+$/', $_POST['username']) ){
                $is_email = true;
            } else {
                $is_email = false;
            }
            if( $is_email ){    // Email
                $username = clean_hex( $_POST['username'] );
                $username = trim( $username, '\n\r\t\v\x00' );
                $username = strip_tags( $username );
                $username = esc_attr( $username );
            } else {            // Username
                $username = clean_hex( $_POST['username'] );
                $username = sanitize_user( $username );
            }
            if( empty( $username ) ){
            } else if( $is_email === true ){
                if( ! is_valid_email_address($username) ){
                    $error->add('username-email', 'Please enter a valid email address.');
                }
            } else if( $is_email === false ){
                if( ! ctype_alnum(str_replace($chars_valid['un'], '', $username)) ){
                    $error->add('username-charset', 'Invalid characters in your username.');
                }
            } else if( strlen( $username ) > 160 ){
                $error->add('username-maxlength', 'Please enter a valid username or email address.');
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
             */
            if( isset( $_POST['password'] ) ){
                $password = clean_hex( $_POST['password'] );
                $password = trim( $password, '\n\r\t\v\x00' );
                $password = strip_tags( $password );
                $password = esc_attr( $password );
            }
            // $password;
            if( empty($password) || empty($username) ){
                $error->add('password-empty', 'Please enter a username and password.');
            } else if( 0 === preg_match("/.{6,}/", $password) ){
                $error->add('password-invalid', 'Please enter a valid password.');
            } else if( ! ctype_alnum( str_replace( $chars_valid['pw'], '', $password ) ) ){
                $error->add('password-charset', 'Invalid characters in your password.');
            } else if( strlen( $password ) > 60 ){
                $error->add('password-maxlength', 'Please enter a valid password.');
            }

            /**
             * Remember me
             * 
             * @var string|bool $remember  What happens:
             *      1. Check for the expected output and return the sanitized value
             */
            if( isset($_POST['rememberme']) ){
                $remember = $_POST['rememberme'];
            } else {
                $remember = '';
            }
            if( $remember === 'forever' ){
                $remember = "true";
            } else {
                $remember = "false";
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
            if(count( $error->get_error_codes() )){ 
                unset( $password );
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
             * Collect the input and try to login
             */
            } else {
                $login_data = array();
                $login_data['user_login'] = $username;
                $login_data['user_password'] = $password;
                $login_data['remember'] = $remember;

                // Attempt to sign in
                $user_verify = wp_signon( $login_data, false );

                if ( is_wp_error($user_verify) ){ 
                    // Prevent form resubmission
                    unset( $user_verify );
                    unset( $password );
                    unset( $_SESSION['token'] );

                    // Error message ?>
                    <div id="toast-container" data-type="error">
                        <i class="icon-notification"></i>
                        <p>Invalid login details.</p>
                    </div>
                    <?php

                } else {
                    // Prevent form resubmission
                    unset( $user_verify );
                    unset( $username );
                    unset( $password );
                    unset( $_SESSION['token'] );

                    // Success message
                    echo "<script type='text/javascript'>location.reload();</script>";
                    exit();
                }
            }

        // remote form posting attempted
        } else {
            unset( $password );
            unset( $_SESSION['token'] );
            ?>
            <div id="toast-container" data-type="error">
                <i class="icon-notification"></i>
                <p>
                    Something went wrong. Are you trying to do something you're not suppose to? 
                    <a href="<?php echo esc_url( home_url('/login/') ); ?>" rel="noopener">Here is a link to refresh.</a>
                </p>
            </div>
            <?php
        }
    }

    if( isset( $_REQUEST['success'] ) && $_REQUEST['success'] == 1){ 
        $theme = wp_get_theme(); ?>
        <div id="toast-container" data-type="confirm">
            <i class="icon-checkmark"></i>
            <p>You have successfully registered to <?php echo $theme->get( 'Name' ); ?>.</p>
        </div>
    <?php }
    if( isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed'){ ?>
        <div id="toast-container" data-type="confirm">
            <i class="icon-checkmark"></i>
            <p>Your password has been changed. You can sign in now.</p>
        </div>
    <?php }
    
    // Create a session token
    $token = md5( uniqid(rand(), true) );
    $_SESSION['token'] = $token;

    // Get the current page URL
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


    
    // Warning for anyone trying to use no JavaScript ?>
    <noscript><div id="toast-container" data-type="error">
        <p>Please correct the following error:</p>
        <p>Your browser needs to support JavaScript in order to use this page.</p>
    </div></noscript>

    <form name="login" action="<?php echo esc_url( $url ); ?>" method="post" class="account-form-wrapper background">
        <h2>Login</h2>

        <input type="hidden" name="token" value="<?php echo $token; ?>" />

        <div class="input-wrap username">
            <label for="username">
                Username / Email<span class="txt-red">*</span>
            </label>
            <input type="text" name="username" id="username" 
                value="<?php echo isset( $_POST['username'] ) ? $username : null; ?>" 
                maxlength="160" 
                required 
                autocomplete="username" 
                autocapitalize="off" 
                autocorrect="off" 
                data-valueMissing="Please enter a username or email" 
                data-tooLong="Username or email is too long" 
                data-typeMismatch="Invalid characters in your username or email" 
                data-badInput="Enter a different username or email" 
            />
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
                data-tooLong="Password is too long" 
                data-tooShort="Your password should be at least 6 characters" 
                data-typeMismatch="Invalid characters in your password" 
                data-badInput="Enter a different password" 
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
        
        <div class="input-wrap forgetmenot">
            <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
            <label for="rememberme">Remember Me</label>
        </div>

        <?php
        if( defined('SOMFRP_PATH') ){
            $reset_pw = get_option( 'somfrp_gen_settings' );
            $reset_value = ( isset( $reset_pw['somfrp_reset_page'] ) && $reset_pw['somfrp_reset_page'] ) ? esc_html($reset_pw['somfrp_reset_page']): '' ;
            ?>
        
            <a href="<?php echo esc_url( get_permalink($reset_value) ); ?>" class="lost-password" rel="noopener">Lost your password?</a>
        <?php } ?>
    </form>
<?php
}

shenk_init();       // NOTE: This must be last on the page
