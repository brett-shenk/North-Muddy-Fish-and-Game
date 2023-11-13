<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;


// Scripts for the page
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('event-calendar');
});

// Remove default page content
remove_action('entry', 'the_content', 10);
remove_action('entry', 'do_entry_header', 1);

// Page Title
add_action('entry', function(){ ?>
    <header class="entry-header alignfull" role="region" aria-label="Page Banner">
	    <h1 class="entry-title">
            
            <?php if( is_archive() ){ ?>
                <span>Calendar</span>
            <?php } else { ?>
                <span><?php the_title(); ?></span>
            <?php } ?>

            <div id="page-title"></div>
		</h1>
	</header>
<?php }, 1);

// Wrapper
add_action('entry', 'wrapper_open', 5);
add_action('entry', 'wrapper_close', 20);

// The content
add_action('entry', 'the_calendar_archive', 8);
function the_calendar_archive(){
    echo tribe( Template_Bootstrap::class )->get_view_html();
}

shenk_init();       // NOTE: This must be last on the page

