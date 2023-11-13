/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @copyright	North Muddy Fish & Game Association
 */

document.addEventListener("DOMContentLoaded", function(event){
(function($){

    /**
     * @package Frequently Asked Questions
     */
    var frequently_asked_questions = {
        init: function(){

            // Global Variables
            let $this = '';
            let $faq = $('.accordion-container');

            if( $faq[0] === undefined ){
                return;
            }

            // Loop through each question / answer
            $faq.each(function(){
                $this = $(this);
                let $question_height = Math.round($this.find('.acf-innerblocks-container').height());
                let $question_head = $this.find('.accordion-header');

                // Add height for later use
                $question_head.attr('data-height', $question_height);
                
                // Initial page load
                $this.find('.acf-innerblocks-container').css('height', '0px');
                $this.find('.acf-innerblocks-container').css('visibility', 'visible');
                
                // Listen for a question to be clicked
                $question_head.on('click', function(){
                    $this = $(this);
                    $question = $this.parent('.accordion-container').children('.acf-innerblocks-container');
                    $container = $this.parent('.accordion-container');

                    $faq.each(function(){
                        if( $this.parent('.accordion-container')[0] !== $(this)[0] ){
                            $(this).find('.acf-innerblocks-container').css('height', '0px');
                            $(this).removeClass('active');
                        }
                    });
                    
                    if( $container.hasClass('active') ){
                        $question.css( 'height', '0px' );
                        $container.removeClass('active');
                    } else {
                        $question.css( 'height', $question_head.attr('data-height') +'px' );
                        $container.addClass('active');
                    }
                });
            });
        }   // end init
    }
    var fix_faq_resize = {
        init: function(){
            let $faq = $('.accordion-container');
            let $this = '';

            if( $faq[0] === undefined ){
                return;
            }

            $faq.each(function(){
                $this = $(this);

                // Unset the height so we can calculate the new height. Hide the content until finished
                $this.find('.acf-innerblocks-container').css({
                    'height': 'auto',
                    'display': 'none'
                });

                // Get the new height
                let $question_height = Math.round($this.find('.acf-innerblocks-container').height());
                $question_height = parseInt($question_height) + 30;

                $this.find('.accordion-header').attr('data-height', $question_height);

                // Re-apply the height to the active element and hide the rest
                if( $this.hasClass('active') ){
                    $this.find('.acf-innerblocks-container').css({
                        'height': $question_height + 'px',
                        'display': ''
                    });
                } else {
                    $this.find('.acf-innerblocks-container').css({
                        'height': '0px',
                        'display': ''
                    });
                }
            });
        }
    }

    /**
     * @package Mission Control
     */
    if( $('body').hasClass('wp-admin') ){
        $(window).load(function() {         // Need to wait longer for the admin to finish running scripts
            frequently_asked_questions.init();
        });
    } else {
        frequently_asked_questions.init();
    }

    // wait for the last event
    let resize0;
    $( window ).on( "resize", function() {
        if( resize0 !== '' ){
            clearTimeout(resize0);
            resize0 = setTimeout( function(){
                fix_faq_resize.init();
            }, 250 );
        }
    });
    
})(jQuery);
});
