/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @copyright	North Muddy Fish & Game Association
 */
jQuery( function($) {

    /**
     * @package Frequently Asked Questions
     */
    var sidebar_accordion_menu = {
        init: function(){

            // Global Variables
            let $this = '';
            let $accordion = $('.sidebar-accordion');

            if( $accordion[0] === undefined ){
                return;
            }

            // Loop through each question / answer
            $accordion.each(function(){
                $this = $(this);
                let $container = $this.find('.acf-innerblocks-container');
                let $sidebar_height = Math.round($this.height());
                let $sidebar_head = $this.find('.accordion-header');
                
                // Add height for later use
                $sidebar_head.attr('data-height', $sidebar_height);
                
                // Initial page load
                $container.css('visibility', 'visible');    // unhide the element
                $container.addClass('active');
                $container.css( 'height', $sidebar_head.attr('data-height') +'px' );

                
                // Listen for something to be clicked
                $sidebar_head.on('click', function(){
                    $this = $(this);
                    
                    // ??
                    // $accordion.each(function(){
                    //     if( $this.parent('.sidebar-accordion')[0] !== $(this)[0] ){
                    //         $(this).parent('div').css('height', $('.sidebar-accordion .accordion-header').height() +'px' );
                    //         $(this).parent('div').removeClass('active');
                    //     }
                    // });

                    
                    if( $this.parent('div').hasClass('active') ){
                        $height = Math.round($this.height());
                        $this.parent('div').css( 'height', $height + 'px' );
                        $this.parent('div').removeClass('active');
                    } else {
                        $this.parent('div').css( 'height', $sidebar_head.attr('data-height') + 'px' );
                        $this.parent('div').addClass('active');
                    }
                });
            });
        }   // end init
    }
    var fix_sidebar_resize = {
        init: function(){
            let $accordion = $('.sidebar-accordion');
            let $this = '';

            if( $accordion[0] === undefined ){
                return;
            }

            $accordion.each(function(){
                $this = $(this);
                let $container = $this.find('.acf-innerblocks-container');

                // Unset the height so we can calculate the new height. Hide the content until finished
                $container.css({
                    'height': 'auto',
                    'visibility': ''
                });
                
                // Get the new height
                let $sidebar_height = Math.round($this.height());
                $this.find('.accordion-header').attr('data-height', $sidebar_height);
                
                // Re-apply the height to the active element and hide the rest
                if( $container.hasClass('active') ){
                    $container.css({
                        'height': $sidebar_height + 'px',
                        'visibility': 'visible'
                    });
                } else {
                    $height = Math.round($this.find('.accordion-header').height());
                    $container.css({
                        'height': $height + 'px',
                        'visibility': 'visible'
                    });
                }
            });
        }
    }

    /**
     * @package Mission Control
     */
    window.addEventListener('load', (event) => {
        sidebar_accordion_menu.init();
    });

    // wait for the last event
    let resize0;
    $( window ).on( "resize", function() {
        if( resize0 !== '' ){
            clearTimeout(resize0);
            resize0 = setTimeout( function(){
                fix_sidebar_resize.init();
            }, 250 );
        }
    });
});
