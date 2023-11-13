<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "frequently-asked-q-container"
] );

if ( function_exists( 'get_field' ) ) {
    $faq_category = ! empty(get_field('faq_category_name')) ? get_field('faq_category_name') : 'Some Category';
} else {
    $faq_category = 'Some Category';
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="wp-block-columns is-layout-flex wp-container-3">
        <div class="wp-block-column is-layout-flow" style="flex-basis:400px">
            <h3 class="frequently-asked-header"><?php echo $faq_category; ?></h3>
        </div>
        <div class="wp-block-column is-layout-flow">
            <hr class="wp-block-separator aligncenter has-alpha-channel-opacity" style="width: 100%;">

            <?php
            $allowed_blocks = array( 'shenk/accordion' );

            $template = array( array( 'shenk/accordion' ) );
            ?>
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
                template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
            />
        </div>
    </div>

</div>
