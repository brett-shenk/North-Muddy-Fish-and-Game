<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */
?>
<div class="single-slide-container">
    <InnerBlocks template="<?php echo esc_attr( wp_json_encode( array( array('core/paragraph', array( 'placeholder' => 'This is placeholder paragraph text.' )) ) ) ); ?>" />
</div>
