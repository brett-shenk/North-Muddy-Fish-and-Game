<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Generate Theme Image
 * 
 * @link        Only on the page: Appearance > Theme
 * @author      Brett Shenk
 * @version     1.4
**/
add_action( 'admin_print_scripts', 'generate_custom_theme_image', 50, 1 );
function generate_custom_theme_image()
{
    if( $_SERVER['REQUEST_URI'] === '/wp-admin/themes.php' ){
    ?>
    <script>
    (function($){

        // URL for local environment check
        var domain = window.location.href;
        domain = String( domain );

        // URL for screenshot replacement
        function getURL() {
            <?php 
            $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
            $server = $_SERVER['SERVER_NAME'];
            ?>

            var url = "<?php echo $protocol . '://' . $server . '/'; ?>";
            var output = window.location.protocol + "//s.wordpress.com/mshots/v1/" + encodeURIComponent(url) + "?w=1200";
            return output;
        }

        function generate_theme_img(){
            // Used for multiple themes
            document.querySelector('.theme-browser .theme[data-slug="<?php echo get_stylesheet(); ?>"]').querySelector('.theme-screenshot img').src = getURL();
            
            // Used for the theme pop up + single themes
            if( document.querySelector('.theme-overlay .screenshot img') !== null ){
                document.querySelector('.theme-overlay .screenshot img').src = getURL();
            }
        }

        // The response API is slow for new images and will return a "loading image" in the meantime. So to handle this we
        //      start with loading it as soon as possible. Next it waits a set amount of time before trying the API again.
        if( !domain.includes('lndo') && !domain.includes('local') && !domain.includes('192.') ){
            getURL();
        }

        // When the Window is finished loading
        window.addEventListener("load", function(){
            if( !domain.includes('lndo') && !domain.includes('local') && !domain.includes('192.') ){
                generate_theme_img();
                
                setTimeout(function(){
                    generate_theme_img();
                }, 1500);           // 1.5s

                setTimeout(function(){
                    generate_theme_img();
                }, 4000);           // 4s

                setTimeout(function(){
                    generate_theme_img();
                }, 7000);           // 7s

                setTimeout(function(){
                    generate_theme_img();
                }, 10000);           // 10s

				setTimeout(function(){
                    generate_theme_img();
                }, 20000);          // 20s

                setTimeout(function(){
                    generate_theme_img();
                }, 30000);          // 30s

                setTimeout(function(){
                    generate_theme_img();
                }, 60000);          // 60s

                // The Theme Details pop up
                $('.theme[data-slug="<?php echo get_stylesheet(); ?>"]').on('click', function(){
                    generate_theme_img();
                });

            }
        });
    })(jQuery);
    </script>
    <?php
    }
}
