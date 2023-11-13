<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * Site Header
 * @property hook before_get_header
 * @property hook before_site_header
 * @property hook site_header
 * @property hook after_site_header
 * 
 * Main Container
 * @property hook before_main_content
 * @property hook main_content
 * @property hook after_main_content
 * 
 * Page Header
 * @property hook before_entry_header
 * @property hook entry_header
 * @property hook entry_header, do_entry_title, 10			Page Title
 * @property hook entry_header, site_breadcrumbs, 15		Breadcrumbs
 * @property hook after_entry_header
 * 
 * Page Content
 * @property hook before_entry
 * @property hook entry
 * @property hook entry, the_content, 10					Page Content
 * @property hook after_entry
 * 
 * Site Footer
 * @property hook before_site_footer
 * @property hook site_footer
 * @property hook after_site_footer
 * 
 * Extra Functions
 * @property hook wrapper_open			Page Wrapper
 * @property hook wrapper_close			Page Wrapper
**/

/**
 * @package Main function to generate the template(s)
 */
function shenk_init() {
	
	do_action( 'before_get_header' );

	get_header(); ?>

	<!-- Start Website -->

	<?php
	do_action( 'before_main_site' );

	do_action( 'main_site' );

	do_action( 'after_main_site' );
	?>

	<!-- End Website -->

	<?php get_footer();
}


/**
 * @subpackage The Site Header
 */
function do_site_header() {
	do_action('before_site_header'); ?>

	<header id="site-header">
		<?php do_action('site_header'); ?>
	</header>

	<?php do_action('after_site_header');
}


/**
 * @subpackage The Site Container
 */
function do_site_main() {
	do_action( 'before_main_content' ); ?>

	<main id="main" class="site-main">
		<?php do_action( 'main_content' ); ?>
	</main>

	<?php do_action( 'after_main_content' );
}


/**
 * @subpackage Inner Site Container
 */
function do_entry() {
	do_action('before_entry'); 
	
	if( is_singular( 'post' ) ){ ?>
		<article <?php post_class( ['entry', 'post'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
		<?php

	} else if( is_category() ){ ?>
		<article <?php post_class( ['entry', 'category'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
		<?php

	} else if( is_tag() ){ ?>
		<article <?php post_class( ['entry', 'tag'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
		<?php

	} else if( is_search() ){ ?>
		<article <?php post_class( ['entry', 'search'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
		<?php

	} else if( is_privacy_policy() ){ ?>
		<article <?php post_class( ['entry', 'privacy-policy'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
		<?php

	} else { ?>
		<article <?php post_class( ['entry'] ); ?>>
			<?php do_action('entry'); ?>
		</article>
	<?php } ?>

	<?php do_action('after_entry');
}


/**
 * @subpackage Page Header
 */
function do_entry_header() {
	do_action('before_entry_header'); ?>

	<header class="entry-header alignfull" role="region" aria-label="Page Banner">
		<?php do_action('entry_header'); ?>
	</header>

	<?php do_action('after_entry_header');
}


/**
 * @subpackage Page Title
 */
function do_entry_title() { ?>
	<h1 class="entry-title">
		<?php do_action('before_entry_title'); ?>

		<span><?php echo get_the_title(); ?></span>

		<div id="page-title"></div>

		<?php do_action('after_entry_title'); ?>
	</h1>
<?php }


/**
 * @subpackage The Site Footer
 */
function do_site_footer() {
	do_action( 'before_site_footer' ); ?>

	<footer id="footer" aria-label="Site Footer">
		<?php do_action( 'site_footer' ); ?>
	</footer>
	
	<?php do_action( 'after_site_footer' );
}


/**
 * @subpackage Helper Functions
 */
function wrapper_open() { ?>
	<div class="wrapper">
<?php }

function wrapper_close() { ?>
	</div>
<?php }


/**
 * @subpackage Bringing it all together
 */
add_action( 'main_site', 'do_site_header', 10 );
add_action( 'main_site', 'do_site_main', 10 );
add_action( 'main_site', 'do_site_footer', 10 );
add_action( 'main_content', 'do_entry', 10);
add_action( 'entry', 'do_entry_header', 1);
add_action( 'entry_header', 'do_entry_title', 10);
add_action( 'entry', 'the_content', 10);
