// https://www.advancedcustomfields.com/resources/javascript-api/

wp.domReady( () => {

	/**
	 * Remove Unnecessary Blocks
	 */
	wp.blocks.unregisterBlockType( 'core/rss' );
	wp.blocks.unregisterBlockType( 'core/comments' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination-numbers' );
	wp.blocks.unregisterBlockType( 'core/comments-title' );
	wp.blocks.unregisterBlockType( 'core/latest-comments' );
	wp.blocks.unregisterBlockType( 'core/loginout' );
	wp.blocks.unregisterBlockType( 'core/avatar' );
	wp.blocks.unregisterBlockType( 'core/calendar' );

	wp.blocks.unregisterBlockType( 'core/details' );

	/**
	 * Remove...
	 */
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'taxonomy-panel-category' ) ; 		// category
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'taxonomy-panel-TAXONOMY-NAME' ) ; // custom taxonomy
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'taxonomy-panel-post_tag' ); 		// tags
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'featured-image' ); 				// featured image
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'post-link' ); 					// permalink
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'page-attributes' ); 				// page attributes
	// wp.data.dispatch('core/edit-post').removeEditorPanel( 'post-excerpt' ); 					// Excerpt
	wp.data.dispatch('core/edit-post').removeEditorPanel( 'discussion-panel' ); 				// Discussion

	/**
	 * Remove Block Styles
	 */
	wp.blocks.unregisterBlockStyle( 'core/social-links',
		[ 'default', 'logos-only', 'pill-shape' ]
	);

	/**
	 * Add Block Styles
	 */
	wp.blocks.registerBlockStyle( 'core/social-links', [ 
		{
			name: 'logos-only',
			label: 'Logos Only',
			isDefault: true,
		},
	]);

} );

/**
 * @package Page Template
 */
const { select, subscribe } = wp.data;

class PageTemplate {
    constructor() {
        this.template = null;
    }

    init() {
        subscribe( () => {

			// Get the template attribute
            const newTemplate = select( 'core/editor' ).getEditedPostAttribute( 'template' );

			// On page load
            if (newTemplate !== undefined && this.template === null) {
                this.template = newTemplate;
				this.loadTemplate();
            }

			// When the template is switched
            if ( newTemplate !== undefined && newTemplate !== this.template ) {
                this.template = newTemplate;
                this.loadTemplate();
            }

        });
    }


    loadTemplate() {

		// ACF must be defined
		if ( typeof acf !== 'object' ) {
			console.log( 'ACF is not defined' );
			return;
		}

		/**
		 * @project Page Header Status
		 * 
		 * Pages and posts
		 */
		let pageHeaderStatus = acf.getField('field_64bbfb8831e65');
		if( pageHeaderStatus[0] !== undefined ){
			pageHeaderStatus = false;
		}
		if( pageHeaderStatus !== false && wp.data.select('core/editor').getCurrentPostType() == ('page' || 'post') ){
			function updateStatus(){
				if( pageHeaderStatus.val() == 'disable' ){
					document.querySelector('.edit-post-visual-editor__post-title-wrapper').style.marginBottom = 0;
				} else {
					document.querySelector('.edit-post-visual-editor__post-title-wrapper').style.marginBottom = '2.1875em';
				}
			}

			// Page load
			window.addEventListener("load", function(){
				updateStatus();
			}); 

			// When the field(s) are clicked / changed
			let page_header_ACF = pageHeaderStatus.$el[0].querySelectorAll('input[type="radio"]');
			let x = 0;
			while( x < page_header_ACF.length ){
				page_header_ACF[x].parentElement.addEventListener('click', function(){
					updateStatus();
				});
				x++;
			}
		}
		
		/**
		 * @project Page Background Image
		 * 
		 * This template only
		 */
		if( this.template == 'page-templates/background-image.php' ){
			var backgroundImage = false;

			function page_background_img(){
				backgroundImage = acf.getField('field_64ba8cbd70e98');			// background_image_for_full_page

				if( backgroundImage[0] !== undefined ){
					backgroundImage = false;
					document.querySelector('.editor-styles-wrapper').style.backgroundImage = "";
				}

				if( backgroundImage !== false ){
					fetch( window.location.protocol +"//"+ window.location.host +"/wp-json/wp/v2/media/"+ backgroundImage.val() )
						.then((response) => response.json())	// Error
						.then((json) => {
							let pageOffsetHeight = 0;
							let slickSlider = document.querySelector('.wp-block-shenk-slick-slider');
							let postTitle = document.querySelector('.edit-post-visual-editor__post-title-wrapper');
							
							if( slickSlider !== null && slickSlider !== undefined ){
								pageOffsetHeight += slickSlider.offsetHeight;
							}
							if( postTitle !== null && postTitle !== undefined ){
								pageOffsetHeight += postTitle.offsetHeight;
							}

							document.querySelector('.editor-styles-wrapper').style.backgroundImage = "url('"+ json.source_url +"')";
							document.querySelector('.editor-styles-wrapper').style.backgroundPositionY = pageOffsetHeight + 'px';
							document.querySelector('.editor-styles-wrapper').style.backgroundRepeat = "no-repeat";
							// document.querySelector('.editor-styles-wrapper').style.backgroundSize = "100% calc(125% - "+ pageOffsetHeight +"px)";
							document.querySelector('.editor-styles-wrapper').style.backgroundSize = "100%";
						});
				} else {
					document.querySelector('.editor-styles-wrapper').style.backgroundImage = "";
				}
			}

			// Page load
			page_background_img();

			// When the field loses focus / was changed
			if( backgroundImage !== false && backgroundImage[0] !== undefined ){
				backgroundImage.$el[0].addEventListener('focusout', function(){
					page_background_img();
				});
			}

			
		} // end  page-templates/background-image.php
    }
}
new PageTemplate().init();
