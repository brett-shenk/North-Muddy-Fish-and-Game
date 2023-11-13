jQuery( function($) {
    
    if( $('.slick-slider-container')[0] == undefined ){
        return;
    }

    $('.slick-slider-container').each(function(){

        // Variable setup for each slider on the page
        var $slick_data =   $(this).children('.slick-slider-inner-container');
        var $slick =        $slick_data.children(".acf-innerblocks-container");
        let window_width = 0;
        let breakpoints = {                     // Editing theses means the functions might need adjusted and other code (css)
            0: {"width": 1199, "slides": 5 },
            1: {"width": 1023, "slides": 4 },
            2: {"width": 850, "slides": 3 },
            3: {"width": 768, "slides": 2 },
            4: {"width": 550, "slides": 1 }
        };
        
        /**
         * @package Add padding to each active slide
         * 
         * @param {object} the event
         * @param {object} slick slider
         */
        function slider_padding_update( event, slick ){
            let count_slide_active = 0;
            window_width = window.innerWidth;       // update each time the function is triggered

            slick.$slides.each(function(index){
                let $selector = slick.$slides[index].querySelector('.single-slide-container > .acf-innerblocks-container');
                let $count_total = $slick_data.attr('data-slidesToShow');

                // If slide is active, increase count
                if( slick.$slides[index].classList.contains('slick-active') ){
                    count_slide_active++;
                }

                // Check for slick slider mobile breakpoints and update accordingly
                for( let x = 0; x < 5; x++ ){       // This number needs updated to add more slides
                    if( window_width <= breakpoints[x]["width"] ){
                        $count_total =  breakpoints[x]["slides"];
                    }
                }

                // For mobile we swap the spacing to be the same on either side for our single slide
                if( window_width > breakpoints[4]["width"] ){
                    // Desktop & tablet. More than one slide
                    if( slick.$slides[index].classList.contains('slick-active') ){
                        if( count_slide_active == 1 ){
                            $selector.style.paddingLeft = "0";
                            $selector.style.paddingRight = $slick_data.attr('data-space-between') +"em";
                        } else if( count_slide_active == $count_total ){
                            $selector.style.paddingLeft = $slick_data.attr('data-space-between') +"em";
                            $selector.style.paddingRight = "0";
                        } else {
                            let both_sides = $slick_data.attr('data-space-between') / 2;
                            $selector.style.paddingLeft = both_sides +"em";
                            $selector.style.paddingRight = both_sides +"em";
                        }
                    } else {
                        $selector.style.paddingLeft = "0";
                        $selector.style.paddingRight = "0";
                    }
                } else {
                    // Mobile single slide only. Mobile has it's own spacing already so more is overkill
                    $selector.style.paddingLeft = "0";
                    $selector.style.paddingRight = "0";
                }
            });
        }

        /**
         * @package All active slides will have the same height
         * 
         * @param {object} the event
         * @param {object} slick slider
         */
        function slider_height_update( event, slick ){
            let same_height = $slide_height = '';

            slick.$slides.each(function(index){

                $slide_height = Math.round( $(slick.$slides[index].querySelector('.single-slide-container > .acf-innerblocks-container')).height() );

                if( same_height == '' ){
                    same_height = $slide_height;
                }
                if( same_height < $slide_height ){
                    same_height = $slide_height;
                }
            });

            // Prevent execution if height is empty
            if( same_height != '' ){
                slick.$slides.each(function(index){

                    // Fix height of slider containers
                    slick.$slides[index].querySelector('.single-slide-container > .acf-innerblocks-container').style.height = same_height +"px";

                    // Attempt to fix the height for the child element
                    if( typeof slick.$slides[index].querySelector('.single-slide-container > .acf-innerblocks-container > *') != "undefined" ){
                        slick.$slides[index].querySelector('.single-slide-container > .acf-innerblocks-container > *').style.height = '100%';
                    }
                });
            }
        }

        /**
         * @package Action
         * 
         * @link https://kenwheeler.github.io/slick/#settings
         * 
         * @note Editing a function means your editing every slider the user created  :)
         * @note Check the data attributes first before running. If this slider equals the default then
         *          it wasn't modified by the user. So let's skip it.
         */
        $slick.on('init', function( event, slick ){                 // Page Load
            if( $slick_data.attr('data-space-between') != '' ){
                slider_padding_update( event, slick );
            }
            if( $slick_data.attr('data-same-height') ){
                slider_height_update( event, slick );
            }
        });
        $slick.on('afterChange', function( event, slick ){          // After the user has changed to the next slide
            if( $slick_data.attr('data-space-between') != '' ){
                slider_padding_update( event, slick );
            }
        });

        let resize0, resize1;
        $( window ).on( "resize", function() {                      // User resizes the browser
            $slick.on('breakpoint', function( event, slick ){
                if( $slick_data.attr('data-space-between') != '' ){
                    clearTimeout(resize0);                          // Wait for the last resize event to trigger
                    resize0 = setTimeout( slider_padding_update( event, slick ), 500 );
                }
                if( $slick_data.attr('data-same-height') ){
                    clearTimeout(resize1);                          // Wait for the last resize event to trigger
                    resize1 = setTimeout( slider_height_update( event, slick ), 525 );
                }
            });
        });

    }); // End Each
});
