<?php
/**
 * Error Page
 */

// Scripts for the page
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('error-page');
});

// Remove default page content
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// The content
add_action('entry', 'the_error_page', 8);
function the_error_page(){
    ?>
    <div class="wp-block-cover alignfull is-light" style="min-height:668px">
        <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>

        <img width="1920" height="1372" class="wp-block-cover__image-background wp-image-498" alt="Person holding phone with bubble leading to the text." 
        src="<?php echo THEME_DIR; ?>/assets/dist/images/Depositphotos_309286418_XL.jpg" />
        
        <div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained">
            <h1 class="wp-block-heading has-text-align-center" style="font-size:100px">Oops!</h1>
            <h2 class="wp-block-heading has-text-align-center">
                <strong>Page Not Found</strong>
            </h2>
            <p class="has-text-align-center" style="padding-bottom:var(--wp--preset--spacing--30)">The page you are looking for doesn’t exist or another error occurred.</p>
            
            <div class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-1 wp-block-buttons-is-layout-flex">
                <div class="wp-block-button is-style-fill">
                    <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url('/') ); ?>" target="noopener">
                        Home
                    </a>
                </div>
                <div class="wp-block-button is-style-fill">
                    <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url('/sitemap/') ); ?>" target="noopener">
                        Sitemap
                    </a>
                </div>
            </div>
        </div>
    
    </div>
    <?php
}

shenk_init();       // NOTE: This must be last on the page
