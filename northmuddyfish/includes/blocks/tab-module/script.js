/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @copyright	North Muddy Fish & Game Association
 */

window.addEventListener("load", function(){
(function($){

    var spotlightImgArray = [];

    /**
     * @package Tab Module
     */
    var tab_module = {
        init: function(){

            // Setup
            this.tab_creation();
            let $this = '';
            let $count = 0;
            let $tab_container = $('#tab-switch-container');
            
            let $mobile_n_text = '';
            $mobile_n_text += '<div class="input-wrap select tab-switch">';
                $mobile_n_text += '<label for="mobile-tab-switch-container" class="sr-only">Change Current Tab</label>';
                $mobile_n_text += '<select id="mobile-tab-switch-container"></select>';
                $mobile_n_text += '<i class="icon-chevron-left"></i>';
            $mobile_n_text += '</div>';

            let $mobile_n_tab = $($mobile_n_text);

            $('.tab-module-container').each(function(){
                $count++;
                $this = $(this);
                let $tab_text = $this.attr('data-tab-name');

                // Desktop
                // Assemble a button for each tab
                let $new_tab = $("<button></button>").text( $tab_text );
                $new_tab.attr('data-tab-name', $tab_text);

                if( $count == 1 ){
                    $new_tab.addClass('active');
                    $this.addClass('active')
                }

                $tab_container.append( $new_tab );

                // Mobile
                $mobile_n_tab.children('select').append('<option value="'+ $tab_text +'">'+ $tab_text +'</option>');

                // Possibly hide the tab
                if( $count != 1 ){
                    $this.css('display', 'none');
                    $this.css('opacity', '0');
                }
            });

            $tab_container.before( $mobile_n_tab );

            this.tab_event();
        },

        // Possibly create the main tab container above the first tab
        tab_creation: function(){
            if( $('.tab-module-container')[0] == undefined ){
                return;
            }
            
            let new_container = document.createElement('div');
            new_container.id = 'tab-switch-container';
            
            $('.tab-module-container')[0].before( new_container );
        },

        // Add the event to switch tabs
        tab_event: function(){
            
            // Desktop
            $('#tab-switch-container button').on('click', function(){
                let $this = $(this);
                
                $('#tab-switch-container button').each(function(){
                    if( $this.attr('data-tab-name') == $(this).attr('data-tab-name') ){
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
                $('.tab-module-container').each(function(){
                    if( $this.attr('data-tab-name') == $(this).attr('data-tab-name') ){
                        $(this).addClass('active');
                        $(this).css('display', 'block');
                        $(this).animate({ opacity: '1' }, 250);
                    } else {
                        $(this).removeClass('active');
                        $(this).css('display', 'none');
                        $(this).animate({ opacity: '0' }, 250);
                    }
                });

                $('body').trigger('tabSwitchEvent', $('.tab-module-container'));
            });

            // Mobile
            $('#mobile-tab-switch-container').on('change', function(){
                let $this = $(this);

                $('#tab-switch-container button').each(function(){
                    if( $this.val() == $(this).attr('data-tab-name') ){
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
                $('.tab-module-container').each(function(){
                    if( $this.val() == $(this).attr('data-tab-name') ){
                        $(this).addClass('active');
                        $(this).css('display', 'block');
                        $(this).animate({ opacity: '1' }, 250);
                    } else {
                        $(this).removeClass('active');
                        $(this).css('display', 'none');
                        $(this).animate({ opacity: '0' }, 250);
                    }
                });

                $('body').trigger('tabSwitchEvent', $('.tab-module-container'));
            });
        } // end init
    }

    /**
     * @package Image Lightbox / Pop Up
     */
    var spotlight = {
        init: function(){
            this.findImages();

            // Spotlight settings
            // spotlight.show(spotlightImgArray,{  });

            $('body').on('tabSwitchEvent', function(){
                spotlight.findImages();
            });

        },
        findImages: function(){

            // Only run against the active tab
            let $selector = $('.tab-module-container.active');

            // Guard against missing tab element
            if( $selector.length == 0 ){
                return;
            }
            // Check that it's actually enabled
            if( $selector.attr('data-spotlight') == 'disable'){
                return;
            }

            let $this = '';

            // Loop over all images in the active tab
            $selector.find('figure.wp-block-image').each(function(){
                /**
                 * @var {object} $this          The current object
                 * @var {string} $src           Image URL
                 * @var {string} $title         Image alt text or image file name
                 * @var {string} $description   Image Caption
                 */
                $this = $(this);
                $src = $this.find('img').attr('src');
                
                // If Alt tag isn't set get image title
                $title = $this.find('img').attr('alt');
                if( $title == '' ){

                    // Split into array and get the last element of the array
                    $title = $src.split('/');
                    $title = $title[ $title.length - 1 ];

                    // Remove Image file type
                    $title = $title.replace('.jpg', '');
                    $title = $title.replace('.png', '');
                    $title = $title.replace('.jpeg', '');
                    $title = $title.replace('.webp', '');
                    $title = $title.replace('.gif', '');
                    $title = $title.replace('.png', '');

                    // Add spaces in
                    $title = $title.replace(/-/g, ' ');
                    $title = $title.replace(/_/g, ' ');

                    // Remove Image size
                    $title = $title.replace(/([0-9]+)x([0-9]+)/g, '');
                }

                $description = $this.find('figcaption');
                if( $description.val() !== undefined ){
                    $description = $description.html();
                } else {
                    $description = '';
                }

                // Does a link already exist? Important to check before adding our own
                if( $this.children('a').prop('tagName') === 'A' ){
                    let link = $this.children('a');

                    if( ! link.hasClass('spotlight') ){
                        link.addClass('spotlight');
                    }
                    if( ! link.attr('href') ){
                        link.attr('href', $src);

                        if( ! link.attr('target') ){
                            link.attr('target', '');
                        }
                    }

                    link.attr('data-title', $title);

                    if( $description !== '' ){
                        link.attr('data-description', $description);
                    }
                }

                // Insert a link if the element is in the allowed list
                // If it's not, it probably already has a link or isn't what we are looking for
                let allowedTags = [
                    'PICTURE', 'IMG'
                ];
                if( allowedTags.includes( $this.children('*').prop('tagName') ) ){
                    let link = document.createElement('a');
                    link.classList.add('spotlight');
                    link.href = $src;
                    link.setAttribute('data-title', $title);
                    if( $description !== '' ){
                        link.setAttribute('data-description', $description);
                    }

                    if( $this.children('picture').prop('tagName') === 'PICTURE' ){
                        $this.children('picture').wrap( link );
                    }
                    if( $this.children('img').prop('tagName') === 'IMG' ){
                        $this.children('img').wrap( link );
                    }
                }

                // Add to the master image array
                spotlightImgArray.push({
                    title: $title,
                    description: "",
                    src: $src
                });
            });
        } // end init
    }

    /**
     * @package Support for the user list block
     */
    var members_list_block = {
        init: function(){

            // Do the element(s) exist?
            if( $('[data-rows]')[0] == undefined || $('[data-blk-width]')[0] == undefined ){
                return;
            }

            // Page Load
            member_block_calculate_css( $(window).width() );

            // Wait for the last Page Resize event to trigger
            $(window).on('resize', function(){
                waitForFinalEvent(function(){
                	member_block_calculate_css( $(window).width() );
                }, 500);
            });

            // On Tab Switch
            $('body').on('tabSwitchEvent', function( event, element ){
                member_block_calculate_css( $(window).width() );
            });

            // Core Function
            function member_block_calculate_css( window_width ){
                let element_active = document.querySelector('.tab-module-container.active');
                let width = element_active.getAttribute('data-blk-width');
                let rows = element_active.getAttribute('data-rows');

                // Update row based on window
                if( window_width <= 650 && Array('8', '7', '6', '5', '4', '3', '2', '1').includes( rows ) ){
                    rows = '1';
                } else if( window_width < 1000 && Array('8', '7', '6', '5', '4', '3').includes( rows ) ){
                    rows = '2';
                } else if( window_width <= 1400 && Array('8', '7', '6', '5', '4').includes( rows ) ){
                    rows = '3';
                } else if( window_width <= 1500 && Array('8', '7', '6', '5').includes( rows ) ){
                    rows = '4';
                } else {
                    rows = rows;
                }

                // Update Officers block which is wider than the members block
                if( element_active.querySelectorAll('.column').length !== element_active.querySelectorAll('.user-block').length ){
                    if( window_width <= 1150 ){
                        rows = '1';
                    }
                }

                // Calculate how many columns and their size
                let calc_grid_size = '';
                switch( rows ){
                    case '1':
                        calc_grid_size = width +'fr';
                        break;
                    case '2':
                    default:
                        calc_grid_size = width +'fr ' + width +'fr';
                        break;
                    case '3':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr';
                        break;
                    case '4':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr ' + width +'fr';
                        break;
                    case '5':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr';
                        break;
                    case '6':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr';
                        break;
                    case '7':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr';
                        break;
                    case '8':
                        calc_grid_size = width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr ' + width +'fr';
                        break;
                }

                // Get the element's parent and add CSS
                element_active.children[0].style.gap = '1.25em';
                element_active.children[0].style.display = "grid";
                element_active.children[0].style.justifyItems = "center";
                element_active.children[0].style.gridTemplateColumns = calc_grid_size;
            }
        } // end init
    }

    /**
     * @package Mission Control
     * 
     * AKA Start the script...
     * As soon as it's loaded
     */
    if( $('body').hasClass('wp-admin') ){
        tab_module.init();
    } else {
        tab_module.init();
        
        setTimeout(function(){
            members_list_block.init();
            spotlight.init();
        }, 150);
    }
    
})(jQuery);
});
