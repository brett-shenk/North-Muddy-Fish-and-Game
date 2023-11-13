<?php
/**
 * Post Archive Page
**/

// Remove default page content and title
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ ?>
    <header class="entry-header alignfull" role="region" aria-label="Page Banner">
	    <h1 class="entry-title">
            <span><?php echo get_the_title( get_queried_object_id() ); ?></span>
            <div id="page-title"></div>
		</h1>
	</header>
<?php }, 1);

// The content
add_action('entry', 'the_posts_page', 8);
function the_posts_page(){
    $post_id = get_queried_object_id();
    $the_post = get_post( $post_id );
    $the_content = apply_filters('the_content', $the_post->post_content);

    if ( !empty($the_content) ) {
        echo $the_content;
    }
}

shenk_init();       // NOTE: This must be last on the page
