<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "accordion-container"
] );

if ( function_exists( 'get_field' ) ) {
    $header = ! empty(get_field('faq_question')) ? get_field('faq_question') : 'Placeholder Question';
} else {
    $header = 'Placeholder Question';
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="accordion-header">
        <?php echo $header; ?>
    </div>
    
    <?php
    // Restrict InnerBlocks to allowed block list
	$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/buttons', 'core/file', 'core/group', 'core/list', 'core/spacer', 'core/html' );

    // Start InnerBlocks with a template
    $template = array(
        array( 'core/paragraph', 
            array(
                'placeholder' => 'An answer to the question.'
            ),
        )
    ); ?>
	<InnerBlocks 
		allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
        template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
        templateLock="false"
    />
</div>
