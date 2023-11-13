/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @copyright	North Muddy Fish & Game Association
 * 
 * @package  	Primary Functions
 * @version		1.0
 */

document.addEventListener("DOMContentLoaded", function(event){
(function($){

    /**
     * @package Hide Header on on scroll down
     * 
     * @param {class} .nav-up
     * @param {class} .nav-down
     * @param {class} .nav-init
     */
    var header_fixed = {
        init: function(){
            if( $('#navigation_placeholder')[0] === undefined ){
                return;
            }

            var did_scroll;
            var last_scroll_top = 0;
            var placeholder_height = 5;
            var navbar_height = $('#navigation_placeholder').outerHeight();
            var navbar_element = $('#site-header');

            // Page Load
            if( window.scrollY < navbar_height ){
                navbar_element.addClass('nav-init');
            }
            
            $(window).on('gesturechange', function(){
                did_scroll = true;
            });
            $(window).on('touchmove', function(){   // Fix IOS scroll
                did_scroll = true;
            });

            $(window).scroll(function(event){
                did_scroll = true;
            });
            setInterval(function() {
                if (did_scroll) {
                    has_scrolled();
                    did_scroll = false;
                }
            }, 250);

            function has_scrolled(){
                var window_top = window.scrollY;

                // Make sure they scroll more than placeholder_height
                if(Math.abs(last_scroll_top - window_top) <= placeholder_height)
                    return;
                
                // Top of the page
                if( window_top == 0 ){
                    navbar_element.removeClass('nav-down').removeClass('nav-up').addClass('nav-init');

                // Scroll Down
                } else if( window_top > last_scroll_top && window_top > navbar_height ){
                    navbar_element.removeClass('nav-init').removeClass('nav-down').addClass('nav-up');

                // Scroll Up
                } else {
                    if(window_top + $(window).height() < $(document).height()) {
                        navbar_element.removeClass('nav-init').removeClass('nav-up').addClass('nav-down');
                    }
                }
                
                last_scroll_top = window_top;
            }
        }
    }

    /**
     * @package Main Menu Drop Down
     */
    function create_main_menu_drop_down(){
        let menu_element = $('#menu-main-menu > li.menu__item.menu__item--has-children');

        // Bail if a sub menu doesn't exist to loop through
        if( typeof menu_element === 'undefined' ){
            return false;
        }

        // Bail if window is too small
        if( $(window).width() < 1000 ){
            return false;
        }

        // Calculate the height of each LI in all sub menus and store it in an attribute for later
        menu_element.each(function(){
            $(this).children('ul.menu__sub-menu').children('li').each(function(){
                var height = $(this).height();
                height = Math.round( height );
                $(this).attr( 'data-height', height );
                $(this).css( 'height', 0 );
            });
        });

        // Remove class that keeps menu hidden for page load so we can calculate the height properly
        menu_element.each(function(){
            $(this).children('ul.menu__sub-menu').each(function(){
                $(this).removeClass('not-visible');
            });
        });
        
        // initialize the hover animation
        menu_element.hoverIntent(

            // handlerIn
            function(){
                if( $(window).width() < 1000 ){
                    return false;
                }

                $(this).children('ul.menu__sub-menu').css( 'padding-bottom', '5px' );
                $(this).children('ul.menu__sub-menu').css( 'box-shadow', '0px 2px 5px 3px black' );
                $(this).children('ul.menu__sub-menu').children('li').each(function(){
                    $(this).css( 'height', $(this).attr('data-height') +'px' );
                });
            },

            // handlerOut
            function(){
                if( $(window).width() < 1000 ){
                    return false;
                }

                $(this).children('ul.menu__sub-menu').css( 'padding-bottom', '0' );
                $(this).children('ul.menu__sub-menu').css( 'box-shadow', 'none' );
                $(this).children('ul.menu__sub-menu').children('li').each(function(){
                    $(this).css( 'height', 0 );
                });
            }
        );
    }
    // Remove the height from the main menu for mobile
    function destroy_main_menu_drop_down(){
        $('#menu-main-menu > li.menu__item.menu__item--has-children').each(function(){
            $(this).children('ul.menu__sub-menu').css( 'padding-bottom', '0' );
            $(this).children('ul.menu__sub-menu').css( 'box-shadow', 'none' );
            $(this).children('ul.menu__sub-menu').children('li').each(function(){
                $(this).css( 'height', 'auto' );
            });
        });
    }

    /**
     * @package Page Background Image
     * 
     * Account for the page header and/or the slider being at the top of a page and offset the 
     *      background page image, if it exists
     */
    function extend_page_background(){
        if( ! $('body').hasClass('page-template-background-image') ){
            return false;
        }

        let totalHeight = 0;
        let slickSlider = $('.slick-slider-container');
        let postTitle = $('header.entry-header');
        
        if( slickSlider[0] !== null && slickSlider[0] !== undefined ){
            totalHeight += slickSlider.height();
        }
        if( postTitle[0] !== null && postTitle[0] !== undefined ){
            totalHeight += postTitle.outerHeight();
        }
        if( totalHeight !== 0 ){
            $('.page-background-image').css('background-position-y', totalHeight);
        }
    }

    /**
     * @package Mobile Menu via Mmenu Light
     * 
     * @see {@link ./vendor/mmenu-light/README.md}
     */
    var mobile_menu = {
        init: function(){

            // Menu Selector and init width
            let menu = new MmenuLight(
                document.querySelector( ".main-nav" ), 
                "(max-width: 999px)"
            );

            // Shorten Site Title
            let site_name = document.querySelector('.site-logo').attributes.title.value;
            site_name = site_name.replace('and', '&');
            site_name = site_name.replace('And', '&');
            site_name = site_name.replace('association', 'Assoc.');
            site_name = site_name.replace('Association', 'Assoc.');

            // Settings
            let navigator = menu.navigation({
                title: site_name
            });
            let drawer = menu.offcanvas({
                position:	"left"
            });
            
            // The nav panel to open
            navigator.openPanel( document.querySelector( "nav" ) );

            // Activate the mobile menu and add button class
            let menu_button = document.querySelector('.hamburger');
            menu_button.addEventListener('click', function(){
                drawer.open();
                menu_button.classList.add('is-active');
            });

            // Remove button class
            $('.mm-ocd__backdrop').bind('click', function(){
                menu_button.classList.remove('is-active');
            });
        }
    }

    /**
     * @package Document Center Changes
     */
    var the_document_center =  {
        init: function(){
            let $template = $('.facetwp-template');
            let $this = '';

            if( $template[0] === undefined || $template.attr('data-name') !== 'dlp_documents' ){
                return;
            }

            $template.find('th').each(function(){
                $this = $(this);

                if( $this.hasClass('col-title') ){
                    $this.css( 'width', '200px' );
                } else if( $this.hasClass('col-excerpt') ){
                } else if( $this.hasClass('col-doc_categories') ){
                    $this.css( 'width', '100px' );
                } else if( $this.hasClass('col-file_size') ){
                    $this.css( 'width', '30px' );
                } else if( $this.hasClass('col-file_type') ){
                    $this.css( 'width', '30px' );
                } else if( $this.hasClass('col-link') ){
                    $this.css( 'width', '100px' );
                }
            });
        }
    }


    /**
     * @package Mission Control
     * 
     * AKA Start the script...
     */

    // As soon as it's loaded
    create_main_menu_drop_down();
    header_fixed.init();
    mobile_menu.init();

    // Give other scripts a chance to finish
    $(window).on('load', function(){
        the_document_center.init();

        setTimeout(function(){  // Force the browser to re-paint
            extend_page_background();
        }, 50);
    });

    // Window Resize
    $(window).on('resize', function(){
        waitForFinalEvent(function(){
            the_document_center.init();
            extend_page_background();
            
            // First we destroy the current menu and then we can re-create it or bail as needed
            destroy_main_menu_drop_down();
            create_main_menu_drop_down();
        }, 500);
    });

})(jQuery);
});
