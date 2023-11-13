<?php
/**
 * Single Post Page
**/

// Remove default page content and title
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Featured Image
add_action('entry', 'single_post_page_featured_img', 4);
function single_post_page_featured_img(){
    $width = '9999';
    $height = '400';

    echo '<div class="featured-image alignfull">';
    if( attachment_url_to_postid( get_the_id() ) != false ){
        echo wp_get_attachment_image( attachment_url_to_postid( get_the_post_thumbnail_url(get_the_id(), 'full') ), array($width, $height), false, array( "class" => "post-image" ) ); 
    } else {
        $img = attachment_url_to_postid( get_the_post_thumbnail_url(get_the_ID(), array($width, $height)) );
        echo wp_get_attachment_image( $img, array($width, $height), false, array( "class" => "post-image" ) ); 
    }
    echo '</div>';
}

// Wrapper
add_action('entry', 'wrapper_open', 5);
add_action('entry', 'wrapper_close', 15);

// page title
add_action('entry', 'single_post_page_title', 10);
function single_post_page_title(){ ?>
    <h1 class="entry-title">
        <span><?php echo get_the_title( get_the_id() ); ?></span>
	</h1>
    <?php 
}

// The content
add_action('entry', 'single_post_page_content', 10);
function single_post_page_content(){
    the_content();
}

// After the post info
add_action('entry', 'single_post_page_post_info', 18);
function single_post_page_post_info(){
    $post_author_id = get_post_field( 'post_author', get_the_id() );
    $categories = get_the_category(get_the_id());
    ?>
    <div class="after-post-content-info">
        <strong>Posted On:</strong>&nbsp;<span><?php echo get_the_date(); ?></span><br />
        <strong>Author:</strong>&nbsp;<span><?php echo get_the_author_meta('display_name', $post_author_id); ?></span><br />
        <strong>Category:</strong>&nbsp;<?php
            $i = 0;
            while( $i < count($categories) ){ ?>
                <span>
                    <a href="<?php echo get_term_link($categories[$i]); ?>">
                        <?php echo $categories[$i]->name; ?>
                    </a>
                </span>
                <?php
                
                $i++;
            }
        ?><span><?php ?></span>
    </div>
    <?php
}

shenk_init();       // NOTE: This must be last on the page
