<?php
/**
 * Template Name: Page Size Background Image
**/

add_action('before_entry', 'page_background_img', 1);
function page_background_img(){
    if ( function_exists( 'get_field' ) ) {
        $image = get_field('background_image_for_full_page');
    } else {
        $image = "";
    }
    $style = '';

    if( !empty($image) ){
        $style .= ' style="background-image: url(';
        $style .= $image['url'];
        $style .= ');"';
    }
    ?>
    <div class="page-background-image"<?php echo $style; ?>></div>
<?php }

shenk_init();       // NOTE: This must be last on the page
