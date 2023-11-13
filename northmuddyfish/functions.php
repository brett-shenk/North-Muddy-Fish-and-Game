<?php

// Theme URL
define( 'THEME_DIR',  get_stylesheet_directory_uri() );
// Ex: echo THEME_DIR;    https://[your-domain].com/wp-content/themes/northmuddyfish

// Absolute Theme URL
define( 'THEME_ABS',  dirname( __FILE__ ) );
// Ex: echo THEME_ABS;    /dom38860/wp-content/themes/northmuddyfish

# Setup Site HTML and Hooks
include_once( THEME_ABS . '/includes/layout/template.php' );
include_once( THEME_ABS . '/includes/layout/header.php' );
include_once( THEME_ABS . '/includes/layout/footer.php' );

# ACF Blocks
include_once( THEME_ABS . '/includes/blocks/acf.php' );

# Required Included Functions
include_once( THEME_ABS . '/functions/setup.php' );                 // Load order Matters
include_once( THEME_ABS . '/functions/helper-functions.php' );      // Load order Matters

# Extensions
include_once( THEME_ABS . '/functions/extensions/assets-functions.php' );
include_once( THEME_ABS . '/functions/extensions/body.php' );
include_once( THEME_ABS . '/functions/extensions/comments.php' );
include_once( THEME_ABS . '/functions/extensions/duplicate-posts.php' );
include_once( THEME_ABS . '/functions/extensions/email-validator.php' );
include_once( THEME_ABS . '/functions/extensions/generate-theme-img.php' );
include_once( THEME_ABS . '/functions/extensions/image-html.php' );
include_once( THEME_ABS . '/functions/extensions/pingback.php' );
include_once( THEME_ABS . '/functions/extensions/tinymce.php' );
include_once( THEME_ABS . '/functions/extensions/user-roles.php' );
include_once( THEME_ABS . '/functions/extensions/walker-texas-ranger.php' );

# Include Function Files
include_once( THEME_ABS . '/functions/admin.php' );
include_once( THEME_ABS . '/functions/clean-up-wordpress.php' );
include_once( THEME_ABS . '/functions/cpts.php' );
include_once( THEME_ABS . '/functions/custom-functions.php' );
include_once( THEME_ABS . '/functions/enqueue-scripts.php' );
include_once( THEME_ABS . '/functions/header-hooks.php' );
include_once( THEME_ABS . '/functions/image-size.php' );
include_once( THEME_ABS . '/functions/login-page.php' );
include_once( THEME_ABS . '/functions/plugins.php' );
include_once( THEME_ABS . '/functions/user-profile.php' );
?>
