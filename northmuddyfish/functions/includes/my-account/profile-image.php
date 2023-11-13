<?php
/**
 * My Account - Profile Image Page
 * 
 * @var array   $args['data']  [ID, user_login, user_pass, user_email, display_name, roles]
 */

$myoutube = '';
$error_profile = new WP_Error();
$user_id = $args['data']->ID;

// Has this form's submit button been clicked?
if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_profile_img']) ){
    if ( $_POST['token2'] == $_SESSION['token2'] ){

        // File Variables
        $file_name =     isset( $_FILES['avatar']['name'] )     ? $_FILES['avatar']['name']     : '';
        $file_tmp_name = isset( $_FILES['avatar']['tmp_name'] ) ? $_FILES['avatar']['tmp_name'] : '';
        $file_error =    isset( $_FILES['avatar']['error'] )    ? $_FILES['avatar']['error']    : '';
        $file_size =     isset( $_FILES['avatar']['size'] )     ? $_FILES['avatar']['size']     : '';

        /**
         * @var string $email  What happens:
         *      1a. Remove hexadecimal characters
         *      1b. Remove tab, new line, carriage return, the nul-byte, and vertical tab.
         *      1c. Strip HTML and PHP tags
         */
        if( isset( $_FILES['avatar']['name'] ) ){
            $file_name = clean_hex( $file_name );
            $file_name = trim( $file_name, '\n\r\t\v\x00' );
            $file_name = strip_tags( $file_name );
        }

        /**
         * If this request falls under any of them, treat it as invalid
         *    Undefined | Multiple Files | $_FILES Corruption Attack
         */
        if ( !isset($file_error) || is_array($file_error) ){
            $error_profile->add('multi-file', 'Invalid parameters.');
        }

        /**
         * Check..
         *      1. File error message response
         *          https://www.php.net/manual/en/features.file-upload.errors.php
         *      2. Which includes being empty or partial uploads
         */
        switch ($file_error) {
            case UPLOAD_ERR_OK:
                // no error, proceed
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_profile->add('upload-limit', 'Uploaded file exceeds the file size limit.');
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_profile->add('avatar-partial', 'Uploaded file was only partially uploaded, try again.');
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_profile->add('no-file', 'No file was uploaded, please select a file.');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
            default:
                $error_profile->add('unknown-error', 'Unexpected error. Try again, use a different file, or contact the site admin. Error: SVRFUL_76832');
                break;
        }

        /**
         * Check filesize again to be safe
         *                 2MB
         */
        $upload_max_size = 2 * 1024 * 1024;
        if ($file_size > $upload_max_size) {
            $error_profile->add('image-size', 'Uploaded file exceeds the file size limit.');
        }

        /**
         * Check that the..
         *      1. Temporary file path and file name exist
         *      2. Real file type exists
         *      3. Real file type is in the list of approved formats
         *      4. File size isn't too small, it's for a profile after all
         *          - Can return incorrect if it's not an image
         *      5. File name doesn't contain any invalid characters
         *          - Which is a few more than what windows doesn't allow
         *      6. File name length
         * 
         * 1)
         */
        if( ! empty($file_tmp_name) && ! empty($file_name) ){
            $wp_filetype = wp_check_filetype_and_ext( $file_tmp_name, $file_name );
            $file_type = ! empty( $wp_filetype['type'] ) ? $wp_filetype['type'] : '';

            // 2)
            if( ! empty($file_type) ){
                $allowed_formats = array(
                    'image/jpeg', 
                    'image/jpg', 
                    'image/png', 
                    'image/gif', 
                    'image/webp'
                );

                // 3)
                if( ! in_array( $file_type, $allowed_formats ) ){
                    $error_profile->add('image-type', 'Invalid file format uploaded, please select an image.');
                } else {

                    // 4)
                    if ( @getimagesize( $file_tmp_name ) ){
                        $file_details = getimagesize( $file_tmp_name );
                        $width = $file_details[0];
                        $height = $file_details[1];

                        if( $width < 150 ){
                            $error_profile->add('image-width', 'Uploaded image width needs to be at least 150px wide.');
                        }
                        if( $height < 150 ){
                            $error_profile->add('image-height', 'Uploaded image height needs to be at least 150px wide.');
                        }
                    } else {
                        $error_profile->add('image-size', 'File uploaded is missing or invalid.');
                    }
                }
            } else {
                $error_profile->add('image-type', 'Invalid file format uploaded, please select an image.');
            }

            // 5)
            $chars_valid = array('-', '_', '.', '[', ']', '{', '}', '(', ')', '!', '@', '#', '$', '^', '&', '=', '+', ',', '`', '~', ' ', ';');
            if( ! ctype_alnum(str_replace($chars_valid, '', $file_name)) ){
                $error_profile->add("image-name", "Invalid characters in the file name. Check the list of invalid characters below.");
            }

            // 6)
            if( strlen($file_name) > 100){
                $error_profile->add('image-maxlength', "The file name is too long. It needs to be 100 or less characters.");
            }
        }

        /**
         * Check honeypot field after making sure nothing nefarious leaks through
         */
        if( isset( $_POST['myoutube'] ) ){
            $myoutube = clean_hex( $_POST['myoutube'] );
            $myoutube = sanitize_text_field( $myoutube );
        }
        if( ! empty($myoutube) ){
            $error_profile->add('honeypot', 'Unexpected error.');
        }

        /**
         * Show the error(s)
         */
        if (count($error_profile->get_error_codes())) { 
            unset( $_SESSION['token2'] );
            ?>
            <div id="toast-container" data-type="error">
                <i class="icon-notification"></i>
                <p>Please correct the following error(s):</p>
                <ul>
                    <?php echo '<li>' . implode( '</li><li>', $error_profile->get_error_messages() ) . '</li>'; ?>
                </ul>
            </div>
            <?php

        /**
         * Otherwise upload the user image
         */
        } else {

            // WordPress environment
            require( dirname(__FILE__).'/../../../../../../wp-load.php' );

            // Allows us to use wp_handle_upload() function
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            $upload = wp_handle_upload( 
                $_FILES['avatar'], 
                array( 'test_form' => false ) 
            );

            // Double check it's set
            if( ! empty( $upload['error'] ) ) {
                wp_die( 'Error missing image.' );
            }

            // Make sure Simple Local Avatars is active...
            if ( !defined('SLA_VERSION') ){
                wp_die( 'Something went wrong. Please contact the site administrator. ERROR: SLAW_86128745' );
            }

            // It is time to add our uploaded image into WordPress media library
            $attachment_id = wp_insert_attachment(
                array(
                    'guid'           => $upload['url'],
                    'post_mime_type' => $upload['type'],
                    'post_title'     => basename( $upload['file'] ),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                ),
                $upload['file']
            );

            if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
                wp_die( 'Upload error.' );
            }

            // Update medatata, regenerate image sizes
            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            wp_update_attachment_metadata(
                $attachment_id,
                wp_generate_attachment_metadata( $attachment_id, $upload['file'] )
            );

            // Update profile image
            $my_new_avatar = new Simple_Local_Avatars();
            $my_new_avatar->assign_new_user_avatar( $attachment_id, $user_id );

            // Prevent form resubmission
            unset( $upload );
            unset( $attachment_id );
            unset( $my_new_avatar );
            unset( $myoutube );
            unset( $file_name );
            unset( $file_tmp_name );
            unset( $file_error );
            unset( $file_size );
            unset( $_SESSION['token2'] );

            // Make sure the new image is properly loaded everywhere
            header( 'Location:' . home_url('/my-account/?active_tab=profile-image&success=1') );
        }

    // remote form posting attempted
    } else {
        unset( $_SESSION['token2'] );
        ?>
        <div id="toast-container" data-type="error">
            <i class="icon-notification"></i>
            <p>
                Something went wrong. Are you trying to do something you're not suppose to? 
                <a href="<?php echo esc_url( home_url('/my-account/?active_tab=profile-image') ); ?>" rel="noopener">Here is a link to refresh.</a>
            </p>
        </div>
        <?php
    }
}

if( isset( $_REQUEST['active_tab'], $_REQUEST['success'] ) && $_REQUEST['active_tab'] == 'profile-image' && $_REQUEST['success'] == 1 ){
    $theme = wp_get_theme(); ?>
    <div id="toast-container" data-type="confirm">
        <i class="icon-checkmark"></i>
        <p>Profile image successfully updated.</p>
    </div>
<?php }

// Create a session token
$token2 = md5( uniqid(rand(), true) );
$_SESSION['token2'] = $token2;



// Warning for anyone trying to use no JavaScript ?>
<noscript><div id="toast-container" data-type="error">
    <i class="icon-notification"></i>
    <p>Please correct the following error:</p>
    <p>Your browser needs to support JavaScript in order to use this page.</p>
</div></noscript>

<div class="block-columns">
    <div class="column">
        <?php
        $user_profile_id = get_user_meta($user_id, 'simple_local_avatar', true);

        if( isset($user_profile_id) && !empty($user_profile_id["media_id"]) ){
            echo wp_get_attachment_image( $user_profile_id["media_id"], array('400', '400'), false, array( "class" => "profile-image" , "") );
        } else {
            echo wp_get_attachment_image( get_option('simple_local_avatar_default'), array('400', '400'), false, array( "class" => "profile-image", "alt" => get_users_name() ."'s Profile Image" ) );
        }
        ?>
    </div>
    <div class="column">
        <form id="form-profile-image" name="form-profile-image" action="<?php echo esc_url( home_url('/my-account/?active_tab=profile-image') ); ?>" method="post" class="account-form-wrapper" enctype="multipart/form-data">

            <input type="hidden" name="token2" value="<?php echo $token2; ?>" />

            <div class="input-wrap avatar dropzone disabled">
                <p>Drag and drop your profile image here or click to use the file browser.</p>
                <label for="avatar" class="sr-only">Select an Image</label>
                <span class="file_name"></span>
                <input type="file" name="avatar" id="avatar" value="" accept="image/*" disabled />
            </div>

            <?php // Prevent users from tabbing to the field + disabling autocomplete ?>
            <input type="text" name="myoutube" id="myoutube" 
                value="<?php echo isset( $_POST['myoutube'] ) ? sanitize_text_field($myoutube) : null; ?>" 
                maxlength="50" 
                tabindex="-1" 
                autocomplete="off" 
                spellcheck="false" 
            />

            <div class="profile-image-restrictions">
                <strong>Invalid Characters for File Name:</strong> &nbsp; &lt; &gt; ' " : \ | / ? * %<br />
                <strong>Accepted File Types:</strong> &nbsp; jpg, jpeg, png, gif, webp<br />
                <strong>Recommended Size:</strong> &nbsp; 400 wide by 400 high<br />
                <strong>Max File Size:</strong> &nbsp; 2MB
            </div>

            <?php // Button is disabled initially for a few seconds ?>
            <div class="wp-block-button">
                <button class="wp-block-button__link wp-element-button" id="submit_profile_img" type="submit" name="submit_profile_img" disabled>Please Wait..</button>
            </div>
        </form>

    </div>
</div>
