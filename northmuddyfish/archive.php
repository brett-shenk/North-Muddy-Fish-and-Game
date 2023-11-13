<?php
/**
 * Template file for the following:
 * Author, Category, Taxonomy, Date, Tag
**/

// Scripts for the page
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('wp-block-library'); 			# WordPress Block styles
	wp_enqueue_style('wp-block-library-theme');		# WordPress Block styles
});

// Remove default page content and title
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ ?>
    <header class="entry-header alignfull" role="region" aria-label="Page Banner">
	    <h1 class="entry-title">
            <span><?php echo single_term_title(); ?></span>
            <div id="page-title"></div>
		</h1>
	</header>
<?php }, 1);

// Wrapper
add_action('entry', 'wrapper_open', 5);
add_action('entry', 'wrapper_close', 20);

// The content
add_action('entry', 'the_taxonomy_page', 8);
function the_taxonomy_page(){
    
    echo '<div class="post-archive-content">';

    $post_count = get_option('posts_per_page');     // Settings > Reading
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

    if( $paged == 1 ){
        $offset = 0;
    } else {
        $offset = ($paged - 1) * $post_count;
    }

    $args = array(
        'post_type'         => array('post'),
        'post_status'       => array('publish'),
        'posts_per_page'    => $post_count,
        'paged'             => $paged,
        'offset'            => $offset,
        'order'             => 'DESC',
        'orderby'           => 'date',
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    );
    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ){

        echo '<div class="post-archive-container">';

        // Post Content
        while ( $the_query->have_posts() ){
            $the_query->the_post(); ?>
            
            <div class="single-post-container">
                <?php if( has_post_thumbnail( get_the_id()) ){ ?>
                    <div class="featured-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                            $width = '500';
                            $height = '9999';

                            if( attachment_url_to_postid( get_the_id() ) != false ){
                                echo wp_get_attachment_image( attachment_url_to_postid( get_the_post_thumbnail_url(get_the_id(), 'full') ), array($width, $height), false, array( "class" => "post-image" ) ); 
                            } else {
                                $img = attachment_url_to_postid( get_the_post_thumbnail_url(get_the_ID(), array($width, $height)) );
                                echo wp_get_attachment_image( $img, array($width, $height), false, array( "class" => "post-image" ) ); 
                            } ?>
                        </a>
                    </div>
                <?php } ?>
                <h2>
                    <a href="<?php the_permalink(); ?>">
                        <?php echo substrwords(get_the_title(), 55); ?>
                    </a>
                </h2>
                <?php the_excerpt(); ?>
            </div>
            <?php
        }

        echo '</div>';
        
        // Pagination Links ?>
        <div class="pagination">
            <?php 
                echo paginate_links( array(
                    'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'total'        => $the_query->max_num_pages,
                    'current'      => max( 1, get_query_var( 'paged' ) ),
                    'format'       => '?paged=%#%',
                    'show_all'     => false,
                    'type'         => 'plain',
                    'end_size'     => 2,
                    'mid_size'     => 1,
                    'prev_next'    => true,
                    'prev_text'    => sprintf( '<i class="icon-chevron-left"></i> <span class="sr-only">%1$s</span>', 'Previous Posts' ),
                    'next_text'    => sprintf( '<span class="sr-only">%1$s</span> <i class="icon-chevron-right"></i>', 'Next Posts' ),
                    'add_args'     => false,
                    'add_fragment' => '',
                ) );
            ?>
        </div>
        <?php
    
        wp_reset_postdata();

    } else { ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php 
    }

    echo '</div>';
    
    // The Sidebar
    if ( is_active_sidebar('post_archive_sidebar') ){ ?>
        <div id="post_archive_sidebar" class="widget-area">
            <?php dynamic_sidebar( 'post_archive_sidebar' ); ?>
        </div>
        <?php
    }
}

shenk_init();       // NOTE: This must be last on the page
