<?php

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
            <span>Searched: </span>
            <span><?php echo esc_attr( get_search_query() ); ?></span>
		</h1>
	</header>
<?php }, 1);

// The content
add_action('entry', 'the_search_page', 8);
function the_search_page(){
    global $query_string;

    $search_query = array(
        'order'             => 'DESC',
        'orderby'           => 'date',
    );

    wp_parse_str( $query_string, $search_query );
    $search = new WP_Query( $search_query );

    echo '<div class="wrapper">';

        echo '<div class="wrapper" style="width: 100%; display: flex;">';

            $not_singular = $search->found_posts > 1 ? 'results' : 'result';
            ?>

            <div style="flex-basis: 180px; align-self: center;">
                <?php echo $search->found_posts ." ". $not_singular ." found"; ?>
            </div>
            
            <?php get_search_form();
        
        echo '</div>';

    if ( $search->have_posts() ){

        echo '<div class="post-archive-container">';

        // Post Content
        while ( $search->have_posts() ){
            $search->the_post(); ?>
            
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
                        <?php echo substrwords(get_the_title(), 40); ?>
                    </a>
                </h2>

                <?php the_excerpt(); ?>

                <div class="wp-block-button">
                    <a href="<?php the_permalink(); ?>" class="wp-block-button__link wp-element-button">
                        Go To Page
                        <span class="sr-only"> <?php echo substrwords(get_the_title(), 40); ?></span>
                    </a>
                </div>
            </div>
            <?php
        }

        echo '</div>';
        
        // Pagination Links ?>
        <div class="pagination">
            <?php 
                echo paginate_links( array(
                    'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'total'        => $search->max_num_pages,
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
        <div class="post-archive-container">
            <div class="single-post-container">
                <h2>None</h2>
                <p>Sorry no results found.</p>
            </div>
        </div>
        <?php
    }

    echo '</div>';
}

shenk_init();
