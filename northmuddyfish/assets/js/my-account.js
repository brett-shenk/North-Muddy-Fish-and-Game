/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @copyright	North Muddy Fish & Game Association
 * 
 * @package  	My Account Functions
 * @version		1.0
 */

jQuery( function($) {

    /**
     * @package Honeypot
     * 
     * Prevent some bots from submitting the form
     */
    var honeypot_field = {
        init: function(){
            let $form = $('form.account-form-wrapper');

            // Bail if no form is found on the page
            if( $form[0] == undefined ){
                return false;
            }

            $form.each(function(){
                $(this).on('submit', function(){

                    // does element exist? if yes and it's not set to empty prevent submission
                    if( $('input[type="text"]#mywebsite')[0] !== undefined && $('input[type="text"]#mywebsite').val() !== '' ){
                        return false;
                    }
                    if( $('input[type="text"]#myfacebook')[0] !== undefined && $('input[type="text"]#myfacebook').val() !== '' ){
                        return false;
                    }
                    if( $('input[type="text"]#myoutube')[0] !== undefined && $('input[type="text"]#myoutube').val() !== '' ){
                        return false;
                    }
                    if( $('input[type="text"]#mytwitter')[0] !== undefined && $('input[type="text"]#mytwitter').val() !== '' ){
                        return false;
                    }
                });
            });
        } // end init
    }


    /**
     * @package Initialize the form validation
     */
    var form_validation = {
        init: function(){
            const form_element = document.querySelectorAll('form.account-form-wrapper');

            // Bail if no form is found on the page
            if( form_element[0] == undefined ){
                return false;
            }

            let i = 0;
            while( i < form_element.length ){
                ValidForm(form_element[i], {
                    errorPlacement: 'after',
                });
                i++;
            }

        } // end init
    }


    /**
     * @package Form Button Text
     */
    var button_txt = {
        init: function(){
            let $form = $('form.account-form-wrapper');

            // Bail if no form is found on the page
            if( $form[0] == undefined ){
                return false;
            }

            $form.each(function( event, element ){

                // Initial load
                setTimeout(function() {
                    determine_btn_txt('instant', element);
                }, 2500);

                // After form submit
                $('form.account-form-wrapper').on('submit', function( event, element ){
                    determine_btn_txt('wait', element);
                });
            });

            /**
             * @param {string} version      [instant, wait] Unique text for each form
             * @param {object} $submit_btn  Form button to be updated
             */
            function determine_btn_txt( version, $submit_btn ){

                let $submit = $($submit_btn).find('button[type="submit"]');

                // Get something unique for the form. Tries to use the Form Name, ID, then lastly Action.
                let $form_type = '';
                if( $form_type === '' && typeof $($submit_btn).attr('name') !== ( 'undefined' ) ){
                    $form_type = $($submit_btn).attr('name');

                } else if( $form_type === '' && typeof $($submit_btn).attr('id') !== 'undefined' ){
                    $form_type = $($submit_btn).attr('id');

                }else if( $form_type === '' && typeof $($submit_btn).attr('action') !== 'undefined' ){
                    $form_type = $($submit_btn).attr('action');
                }

                // Bail if an element doesn't exist
                if( $form_type == undefined || $submit == undefined ){
                    return false;
                }

                if( version == 'instant' ){
                    update_button( $submit, $form_type );
                
                } else if( version == 'wait' ){
                    $submit.html('Please Wait..');
                    $submit.attr('disable', '');

                    setTimeout(function(){
                        update_button( $submit, $form_type );
                    }, 2000);
                }

                /**
                 * @param {object} $submit       The Submit button
                 * @param {string} $form_type    The form's name
                 */
                function update_button( $submit, $form_type ){
                    $submit.html('Submit');
                    $submit.removeAttr('disabled');

                    let safety = false;
                    if( window.location.href.indexOf( site_domain ) !== -1 ){
                        safety = true;
                    }

                    if( safety ){
                        if( $form_type == "login" ){
                            $submit.html('Login');
                        }
                        if( $form_type == "register" ){
                            $submit.html('Sign Up');
                        }
                        if( $form_type == "account-details" ){
                            $submit.html('Save Changes');
                        }
                        if( $form_type == "profile-image" ){
                            $submit.html('Upload Image');
                        }
                        if( $form_type == "lostpasswordform" || $form_type == "resetpasswordform" ){
                            if( $submit.parent('.wp-block-button').children('#somfrp_btn_txt')[0] !== undefined ){
                                $submit.html( $submit.parent('.wp-block-button').children('#somfrp_btn_txt').val() );
                            } else {
                                $submit.html('Reset Password');
                            }
                        }
                        if( $form_type == "dlp-submit-form" ){
                            if( $submit.parent('.wp-block-button').children('#dlp_fe_sub_btn_txt')[0] !== undefined ){
                                $submit.html( $submit.parent('.wp-block-button').children('#dlp_fe_sub_btn_txt').val() );
                            } else {
                                $submit.html('Submit Document');
                            }
                        }
                    }
                }
            }
        } // end init
    }


    /**
     * @package Password Eye Button
     * 
     * Add a password eye after every password field on the page. Simply 
     * let's you change the input type to see the password text.
     */
    var password_eye = {
        init: function(){
            let $input = $('input[type="password"]');

            // Bail if it doesn't exist
            if( $input[0] == undefined ){
                return false;
            }

            $input.each(function(){

                // Add password eye after any password field(s)
                let $newbutton = $('<button type="button" class="password-eye"><i class="icon-eye-blocked"></i></button>')
                $(this).after( $newbutton );

                // Listen for it to be clicked
                $(this).parent('.input-wrap').find('.password-eye').on('click', function( event ){
                    event.preventDefault();
                    let $input = $(this).parent('.input-wrap').find('input.password');
                    let $this = $(this);
                    
                    if( $this.children('i').hasClass('icon-eye-blocked') ){
                        $this.children('i').removeClass('icon-eye-blocked').addClass('icon-eye');
                        $input.attr('type', 'text');
                    } else {
                        $this.children('i').removeClass('icon-eye').addClass('icon-eye-blocked');
                        $input.attr('type', 'password');
                    }
                });
            });
        } // end init
    }


    /**
     * @project Form Input Animation
     */
    var form_input_animation = {
        init: function(){
            let $form = $('form.account-form-wrapper');

            // Bail if no form is found on the page with our class
            if( $form[0] == undefined ){
                return false;
            }

            var $this = '';

            // Loop over all forms on the page so we have them all updated
            $form.each(function(){

                // Bail if it doesn't have the correct wrappers
                if( $(this).find('.input-wrap')[0] == undefined ){
                    return;
                }
                
                $(this).find('.input-wrap').each(function(){
                    $this = $(this);
                    let $label = $this.find('label');
                    let $input = $this.find('input');
                    let $textarea = $this.find('textarea');

                    // Bail for the following input types:
                    var bail_types = [ 
                        'radio', 'checkbox', 'file', 
                        'hidden', 'submit', 'button', 
                        'reset', 'image', 'range', 
                    ];
                    if( jQuery.inArray( $input.attr('type'), bail_types ) !== -1 ){
                        return; 
                    }

                    // Fallback if the element is not found             
                    $input =    ( $input[0] == undefined )      ? false : $input;
                    $textarea = ( $textarea[0] == undefined )   ? false : $textarea;
                    
                    // Proceed with what exists
                    if( $input !== false ){
                        new_input_field( $input, $label );
                    }
                    if( $textarea !== false ){
                        new_input_field( $textarea, $label );
                    }
                });
            }); // End loop

            function new_input_field( $input, $label ){
                if( $input.val() !== "" ){
                    $label.parent('.input-wrap').addClass('input-not-empty');
                }

                $input.on('focusin', function(){
                    if( this.value === "" ){
                        $label.parent('.input-wrap').addClass('input-not-empty');
                    }
                });
                $input.on('focusout', function(){
                    if( this.value === "" ){
                        $label.parent('.input-wrap').removeClass('input-not-empty');
                    }
                });
            }
        } // end init
    }
    
    /**
     * @project Let the notice be closeable
     */
    var toast_notice = {
        init: function(){
            let $toast = $('#toast-container');

            // Bail if it doesn't exist
            if( $toast[0] == undefined ){
                return false;
            }

            $toast.each(function(){
                $(this).on('click', function(){
                    $(this).slideUp();
                });
            });
        } // end init
    }

    /**
     * @project My Account Menu
     */
    var my_account_page_nav = {
        init: function(){

            // Bail if not the my account page
            if( ! $('body').hasClass('my-account-page') ){
                return false;
            }

            // Query string support for changing the page on load
            my_account_page_nav.query_string_support();
            
            let $selector = '';
            $('.my-account-sidebar-container li').each(function(){

                // Which element type is the button
                if( $(this).children('button')[0] !== undefined ){
                    $selector = $(this).children('button');
                }
                if( $(this).children('a')[0] !== undefined ){
                    $selector = $(this).children('a');
                }
                
                // Prevent incorrect elements from proceeding
                if( $selector !== '' ){
                    $selector.on('click', function(){

                        // Nice animation for the user while we transition
                        $('.preloader[data-type="section"]').fadeIn(200);

                        // Update the page title
                        $('header.entry-header h1 span').html( $(this).children('.page-title').html() );

                        // Reset elements
                        my_account_page_nav.tab_reset();

                        // Change active tab
                        $(this).addClass('active');

                        let menu_name = $(this).attr('name');
                        setTimeout(function(){

                            // Change active content
                            $('.my-account-container .tab.'+ menu_name).addClass('active');

                            // Remove the animation
                            $('.preloader[data-type="section"]').fadeOut(200);
                        }, 500);

                        // Custom event for other scripts
                        $('body').trigger('my-account-navigation', $(this));
                    });
                }
            });
        },
        tab_reset: function(){

            // Reset tab content
            $('.my-account-container .tab').each(function(){
                $(this).removeClass('active');
            });

            // Reset all tabs to inactive
            $('.my-account-sidebar-container li').each(function(){
                $(this).children('button').removeClass('active');
                $(this).children('a').removeClass('active');
            });
        },
        query_string_support: function(){
            
            // Make sure we are on the domain we expect
            let safety = false;
            if( window.location.href.indexOf( site_domain ) !== -1 ){
                safety = true;
            }

            // Search the URL
            let search_params = new URLSearchParams(window.location.search);

            if( search_params.has('active_tab') && safety ){

                let $param;
                if( $('.my-account-container .tab.'+ search_params.get('active_tab'))[0] !== undefined ){
                    $param = search_params.get('active_tab');
                } else {
                    $param = 'account-details';
                }

                // Update the page title
                $('header.entry-header h1 span').html(
                    $('.my-account-sidebar-container button[name="'+ $param +'"]').children('.page-title').html()
                );

                // Reset elements
                my_account_page_nav.tab_reset();

                // Change active tab
                $('.my-account-sidebar-container button[name="'+ $param +'"]').addClass('active');

                // Change active content
                $('.my-account-container .tab.'+ $param).addClass('active');
            }
        } // end query_string_support
    }

    /**
     * @project Remove specific query strings from the browser's URL
     * 
     * This is to prevent the user from refreshing the page and seeing the same message they 
     * already possibly closed from an earlier script. Wait 1 second to give our scripts time
     * to finish.
     */
    var base_query_string = {
        init: function(){
            let raw_url = location.href.split("?");

            // Should this trigger?
            if( raw_url[1] === undefined ){
                return false;
            }

            let is_my_account_page = false;
            let possibly_remove_query = false;
            let final_url =  raw_url[0];

            // Get the end of the string's parameters
            if( raw_url.includes("&amp;") ){
                raw_url = raw_url[1].split("&amp;");
            } else if( raw_url.includes("&#38;") ){
                raw_url = raw_url[1].split("&#38;");
            } else if( raw_url.includes("&#x26;") ){
                raw_url = raw_url[1].split("&#x26;");
            } else if( raw_url.includes("&") ){
                raw_url = raw_url[1].split("&");
            } else {
                raw_url = raw_url[1];
            }

            // Does it have what we want?
            if( raw_url.includes('success') ){
                possibly_remove_query = true;
            }
            if( raw_url.includes('active_tab') ){
                is_my_account_page = true;
            }

            // Make sure we are on the domain we expect
            let safety = false;
            if( window.location.href.indexOf( site_domain ) !== -1 ){
                safety = true;
            }

            // Finally, possibly some action
            if( possibly_remove_query && safety && is_my_account_page == false ){
                setTimeout(function(){
                    history.pushState(null, "", final_url);
                }, 1000);
            } else if( possibly_remove_query && safety && is_my_account_page ){
                final_url += "?active_tab=" + document.querySelector( '.my-account-sidebar-container button.active' ).name;
                setTimeout(function(){
                    history.pushState(null, "", final_url);
                }, 1000);
            }
        },

        // Mirror the URL for when the 'my account tab' has changed, allowing for page refreshing
        my_account: function( event, element ){

            // Make sure we are on the domain we expect
            let safety = false;
            if( window.location.href.indexOf( site_domain ) !== -1 ){
                safety = true;
            }
            
            if( safety ){
                let $final_url = location.href.split("?")[0];
                $final_url += '?active_tab=' + element.id;
    
                setTimeout(function(){
                    history.pushState(null, "", $final_url);
                }, 500);
            }
        }
    }

    /**
     * @project Profile Image Drop Zone Extension
     */
    var file_upload_areas = {
        init: function(){

            dropzone( 'default', {
                form: document.querySelector('form#form-profile-image'),
                update_file_name: true
            } );

            dropzone( 'default', {
                form: document.querySelector('form#dlp-submit-form'),
                submit_on_upload: false,
                update_file_name: true
            } );

            /**
             * My Custom Drag-n-Drop Zone
             * 
             * @param {Object}       container  File upload container object. [default] is for  $('.dropzone')
             * @param {Object Array} options    Options to be passed. Which includes: 
             *                                  {Object} form               Required. Unique form identifier for the file upload.
             *                                  {Object} button             The form submit button. To override the default.
             *                                  {Object} file_upload        The form file upload. To override the default.
             *                                  {Bool}   submit_on_upload   Should the form auto upload afterwords? Default is true. [true, false]
             *                                  {Bool}   update_file_name   Insert the uploaded file name somewhere. Create element with the class 
             *                                                                  `file_name` inside it's dropzone. Default is false. [true, false]
             */
            function dropzone( the_container, options ){

                /**
                 * Warn about a hard requirement
                 */
                if( options.form == undefined || options.form == '' ){
                    // console.log("Please add the form you want it to go to:   dropzone('default', { options });");
                    return false;
                }

                // Get something unique for the form. Tries to use the Form Name, ID, then lastly Action.
                let unique_form = '';
                if( unique_form === '' && options.form.name !== '' ){
                    unique_form = '[name='+ options.form.name +']';

                } else if( unique_form === '' && options.form.id !== '' ){
                    unique_form = '[id='+ options.form.id +']';

                } else if( unique_form === '' && options.form.action !== '' ){
                    unique_form = '[action='+ options.form.action +']';
                }

                // variables
                let container =     (the_container == '' || the_container == 'default')                       ? $( options.form.tagName + unique_form +' .dropzone' ) : undefined;
                let upload_field =  (options.file_upload == undefined || options.file_upload == '')           ? $( options.form.tagName + unique_form +' input[type="file"]' ) : $( options.file_upload );
                let button =        (options.button == undefined || options.button == '')                     ? $( options.form.tagName + unique_form +' [type="submit"]' ) : $( options.button );
                let auto_upload =   ((options.submit_on_upload == undefined || options.submit_on_upload == '') && options.submit_on_upload !== false) ? true : (options.submit_on_upload === false) ? false : true;
                let file_name =     ((options.update_file_name == undefined || options.update_file_name == '') && options.update_file_name !== true) ? false : (options.update_file_name === true) ? true : false;

                // Silently fail if something required is missing
                if( container[0] === undefined || upload_field[0] === undefined ){
                    return false;
                }

                // Removes the disable field if it's set on the file upload
                setTimeout(function() {
                    if( upload_field.attr('disabled') === 'disabled' ){
                        upload_field.prop('disabled', false);
                        container.removeClass('disabled');
                    }
                }, 2500);


                /**
                 * Prevent browser default
                 */
                $.each( ['dragenter', 'dragover', 'dragleave', 'drop'], function(index, event){
                    container.on(event, preventDefaults, false);
                });
                function preventDefaults( event ){
                    event.preventDefault();
                    event.stopPropagation();
                }

                // Add class to drop element container
                $.each( ['dragenter', 'dragover'], function(index, event){
                    container.on(event, highlight);
                });
                function highlight(){
                    container.addClass('highlight');
                }

                // Remove class to drop element container
                $.each( ['dragleave', 'drop'], function(index, event){
                    container.on(event, unhighlight);
                });
                function unhighlight(){
                    container.removeClass('highlight');
                }


                /**
                 * Drag and drop dialog box
                 */
                container.on('drop', function( event ){
                    let dt = event.originalEvent.dataTransfer;
                    let files = dt.files;

                    // Make sure we are on the domain we expect
                    let safety = false;
                    if( window.location.href.indexOf( site_domain ) !== -1 ){
                        safety = true;
                    }

                    if( safety ){
                        upload_field.prop("files", files);
                        add_file_name();

                        if( auto_upload && button[0] !== undefined ){
                            button[0].click();
                        }
                    }
                });

                
                /**
                 * Click file dialog box
                 */
                container.on("click", function(){

                    // Make sure we are on the domain we expect
                    let safety = false;
                    if( window.location.href.indexOf( site_domain ) !== -1 ){
                        safety = true;
                    }
 
                    if( safety ){
                        upload_field[0].click();
                    }
                });

                // Should we auto submit the form
                upload_field.on('change', function(){

                    // Make sure we are on the domain we expect
                    let safety = false;
                    if( window.location.href.indexOf( site_domain ) !== -1 ){
                        safety = true;
                    }

                    if( safety ){
                        add_file_name();
                        
                        if( auto_upload && button[0] !== undefined ){
                            button[0].click();
                        }
                    }

                });

                
                /**
                 * Add file name
                 */
                function add_file_name(){
                    if( file_name && container.find('.file_name') !== undefined ){
                        let upload_name = upload_field.val().replace(/C:\\fakepath\\/i, '');
                        container.find('.file_name').text(upload_name);
                    }
                }

            }
        } // end init
    }

    /**
     * @project Fix TinyMCE height
     * 
     * After the page is loaded and you change to a new tab in my my account the height is very small
     */
    var tiny_mce_height = {
        init: function(){
            
            // Iframe element ID, page load height
            let elements_to_update = [
                ['description_ifr', '360px'],
                ['custom_content_ifr', '176px'],
            ];

            let i = 0;
            while( elements_to_update.length > i ){
                if( document.querySelector('#'+ elements_to_update[i][0]) !== null ){
                    document.querySelector('#'+ elements_to_update[i][0]).style.height = elements_to_update[i][1];
                }
                i++;
            }
        }
    }
    

    /**
     * @package Let's kick the tires and light the fires!
     * 
     * AKA Start the script...
     */
    
    // As soon as it's loaded
    honeypot_field.init();
    form_validation.init();
    my_account_page_nav.init();
    toast_notice.init();
    file_upload_areas.init();
    base_query_string.init();
    
    // When HTML-Document is loaded and DOM is ready
    $(document).ready(function(){
        button_txt.init();
        password_eye.init();
        form_input_animation.init();
    });

    // Wait until after the document (HTML, CSS and some JS)
    $(window).on('load', function() {
        waitForFinalEvent(function(){
            tiny_mce_height.init();
        }, 250);
    });

    // My Account Menu Clicked
    $('body').on('my-account-navigation', function( event, element ){
        base_query_string.my_account( event, element );
    });
});
