<?php
/**
 * My Account Login Page
 */

if( ! is_user_logged_in() ){
    header( 'Location:' . esc_url( home_url('/login/') ) );
}

if (class_exists('ACF')) {
    add_action('before_get_header', function(){
        acf_form_head();
    });
}

// Scripts for the page
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_script('my-account-js');
    wp_enqueue_style('my-account');

    wp_enqueue_style('dropzone-css');
    wp_enqueue_script('dropzone-js');

    wp_enqueue_style('block-user-list');
});

// Remove default page content
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ ?>
    <header class="entry-header alignfull" role="region" aria-label="Page Banner">
	    <h1 class="entry-title">
		    <span>My Account Details</span>
            <div id="page-title"></div>
		</h1>
	</header>
<?php }, 1);



// The content container
add_action('entry', 'the_login_page', 8);
function the_login_page(){

    session_start();
    
    include( THEME_ABS . '/functions/includes/settings/my-account-creation.php' );
    ?>
    <div class="my-account-page-wrapper">
        <div class="my-account-sidebar-container">
            <?php do_action('shenk_my_account_sidebar', $args); ?>
        </div>
        <div class="my-account-container">
            <?php do_action('shenk_my_account_content', $args); ?>
        </div>
    </div>
<?php }

// Sidebar Navigation
add_action('shenk_my_account_sidebar', function( $user ){ 
    echo '<ul>';

    foreach( $user['tabs'] as $tab ){
        $slug = $tab['slug'];
        $tab_title = $tab['tab_title'];
        $page_title = $tab['page_title'];
        $icon = $tab['icon'];

        $class = '';
        if( ! empty( $tab['class'] ) ){
            $class .= ' class="';
            $class .= $tab['class'];
            $class .= '"';
        }

        $access = false;
        if( ! empty($tab['access']) ){
            if( in_array($user['data']->roles[0], ['administrator', 'an_administrator', 'editor', 'author', 'contributor']) ){
                $access = true;
            }
        } else {
            $access = true;
        }

        if( $access ){ ?>
            <li>
                <button type="button" name="<?php echo $slug ?>" id=<?php echo '"' . $slug . '"' . $class; ?>>
                    <span><?php echo $tab_title; ?></span>
                    <i class="<?php echo $icon ?>"></i>
                    <div class="hidden page-title"><?php echo $page_title; ?></div>
                </button>
            </li>
            <?php
        }
    }
    ?>

    <li>
        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
            <span>Logout</span>
            <i class="icon-logout"></i>
        </a>
    </li>

    </ul>
<?php
}, 1, 1);

// The content
add_action('shenk_my_account_content', function( $user ){ 
    foreach( $user['tabs'] as $tab ){
        $slug = $tab['slug'];
        $tab_title = $tab['tab_title'];
        $page_title = $tab['page_title'];
        $icon = $tab['icon'];

        $class = ' class="';
        $class .= 'tab';
        $class .= ' '. $slug;
        if( ! empty( $tab['class'] ) ){
            $class .= ' '. $tab['class'];
        }
        $class .= '"';

        $access = false;
        if( ! empty($tab['access']) ){
            if( in_array($user['data']->roles[0], ['administrator', 'an_administrator', 'editor', 'author', 'contributor']) ){
                $access = true;
            }
        } else {
            $access = true;
        }

        if( $access && file_exists(get_template_directory() .'/functions/includes/my-account/'. $slug . '.php') ){ ?>
            <div <?php echo $class; ?>>
                <?php get_template_part( 'functions/includes/my-account/'. $slug, null, $user ); ?>
            </div>
            <?php
        }
    } ?>

    <div class="preloader" data-type="section" style="display: none;">
        <span class="loader"></span>
    </div>
<?php }, 1, 1);

shenk_init();       // NOTE: This must be last on the page
