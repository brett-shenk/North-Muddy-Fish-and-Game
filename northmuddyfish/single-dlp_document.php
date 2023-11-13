<?php
/**
 * The template for displaying single documents.
 */

use Barn2\Plugin\Document_Library_Pro\Util\Options;
use Barn2\Plugin\Document_Library_Pro\Frontend_Scripts;

// Scripts for the page
// add_action('wp_enqueue_scripts', function(){
// });

// Remove default page content
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ 
    while ( have_posts() ) :
        the_post(); ?>
        <header class="entry-header alignfull" role="region" aria-label="Page Banner">
            <h1 class="entry-title">
                <span><?php the_title(); ?></span>
                <div id="page-title"></div>
            </h1>
        </header>
    <?php endwhile;
}, 1);

// Wrapper
add_action('entry', 'wrapper_open', 5);
add_action('entry', 'wrapper_close', 20);

// The content
add_action('entry', 'the_single_document_page', 8);
function the_single_document_page(){
    while ( have_posts() ) :
        the_post();
        $document = dlp_get_document( get_the_ID() );

        $display_options = Options::get_document_display_fields();
        $options = Options::get_shortcode_options();
        ?>

        <div class="dlp-document-main">
            <?php
            the_post_thumbnail();
            the_content();
            ?>
        </div>

        <div class="dlp-document-info">
            <div class="document-sidebar-container">
                <?php if ( $document->get_download_url() ) : ?>
                    <?php Frontend_Scripts::load_download_count_scripts(); ?>
                    <div class="dlp-document-info-buttons">
                        <?php echo $document->get_download_button( $options['link_text'], $options['link_style'], 'direct', $options['link_target'] ); ?>
                        <?php
                        if ( $document->is_allowed_preview_mime_type() && $options['preview'] ) :
                            Frontend_Scripts::load_preview_scripts();
                            ?>
                            <?php echo $document->get_preview_button( $options['preview_text'], $options['preview_style'], 'single' ); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div id="dlp-document-info-list">

                    <?php if ( $document->get_file_type() && in_array( 'file_type', $display_options, true ) ) : ?>
                        <div class="dlp-document-file-type">
                            <span class="dlp-document-info-title"><?php esc_html_e( 'File Type: ', 'document-library-pro' ); ?></span>
                            <?php echo $document->get_file_type(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $document->get_category_list() && in_array( 'doc_categories', $display_options, true ) ) : ?>
                        <div class="dlp-document-info-categories">
                            <span class="dlp-document-info-title"><?php esc_html_e( 'Categories: ', 'document-library-pro' ); ?></span>
                            <?php echo $document->get_category_list(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $document->get_tag_list() && in_array( 'doc_tags', $display_options, true ) ) : ?>
                        <div class="dlp-document-info-tags">
                            <span class="dlp-document-info-title"><?php esc_html_e( 'Tags: ', 'document-library-pro' ); ?></span>
                            <?php echo $document->get_tag_list(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $document->get_author_list() && in_array( 'doc_author', $display_options, true ) ) : ?>
                        <div class="dlp-document-info-author">
                            <span class="dlp-document-info-title"><?php esc_html_e( 'Author: ', 'document-library-pro' ); ?></span>
                            <?php echo $document->get_author_list(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $document->get_download_count() && in_array( 'download_count', $display_options, true ) ) : ?>
                        <div class="dlp-document-info-downloads">
                            <span class="dlp-document-info-title"><?php esc_html_e( 'Downloads: ', 'document-library-pro' ); ?></span>
                            <?php echo $document->get_download_count(); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    <?php endwhile;
}

shenk_init();       // NOTE: This must be last on the page
