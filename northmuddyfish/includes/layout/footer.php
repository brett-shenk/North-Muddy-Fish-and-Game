<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly

/** 
 * Main Container
 * @property hook before_main_content
 * @property hook main_content
 * @property hook after_main_content
 * 
 * Page Content
 * @property hook before_entry
 * @property hook entry
 * @property hook entry, the_content, 10					Page Content
 * @property hook after_entry
 * 
 * Site Footer
 * @property hook before_site_footer
 * @property hook site_footer
 * @property hook after_site_footer
 * 
 * Extra Functions
 * @property hook wrapper_open			Page Wrapper
 * @property hook wrapper_close			Page Wrapper
**/

add_action( 'site_footer', 'the_site_footer', 3 );
add_action( 'site_footer', 'the_copyright', 4 );
add_action( 'wp_footer', 'form_site_check', 50 );

function the_site_footer(){
    ?>
    <div id="footer-inner-container">
        <div class="wrapper">
            
            <?php if ( is_active_sidebar('footer_column_one') ){ ?>
                <div id="footer_column_one" class="widget-area">
                    <?php dynamic_sidebar( 'footer_column_one' ); ?>
                </div>
            <?php } ?>

            <?php if ( is_active_sidebar('footer_column_two') ){ ?>
                <div id="footer_column_two" class="widget-area">
                    <?php dynamic_sidebar( 'footer_column_two' ); ?>
                </div>
            <?php } ?>

            <?php if ( is_active_sidebar('footer_column_three') ){ ?>
                <div id="footer_column_three" class="widget-area">
                    <?php dynamic_sidebar( 'footer_column_three' ); ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}

function the_copyright(){
    $theme_name = get_bloginfo('name');
    $menu_location = 'copyright_bar';
    $locations = get_nav_menu_locations();
    ?>
    <div id="copyright-container">
        <div class="wrapper">
            <div class="block-columns">
                <div class="block-column">
                    <p>&copy; <?php echo date("Y") . ' ' . $theme_name; ?>. All Rights Reserved.</p>
                </div>
                <div class="block-column">
                    <?php
                    /**
                     * If menu is not set, nothing shows
                     * @see https://www.itsupportguides.com/knowledge-base/wordpress/using-wordpress-get_nav_menu_locations-php-function/
                     */
                    if (isset($locations[$menu_location]) && $locations[$menu_location] != 0) {
                        wp_nav_menu( array(
                            'theme_location'		=> $menu_location,			// Must be registered with register_nav_menu() in order to be selectable by the user
                            'container'				=> '',
                            // 'fallback_cb'			=> 'page_menu_fallback',
                            'walker'				=> new walker_texas_ranger,
                        ) );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function form_site_check(){ ?>
    <script type="module">
        document.querySelectorAll('form').forEach(function( emnt ){
            let allow = false; if( emnt.classList.contains('account-form-wrapper') ){ allow = true; }
            if( allow ){ emnt.addEventListener('submit', function(){ let safety = true; if( window.location.href.indexOf( site_domain ) !== -1 ){ safety = false; } if( safety ){ return false; } }); }
        });
    </script>
<?php }
