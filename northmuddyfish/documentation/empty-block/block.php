<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "empty-container"
] );

?>
<div <?php echo $wrapper_attributes; ?>>
    <?php
	// Restrict InnerBlocks to allowed block list.
	$allowed_blocks = array( 'core/heading', 'core/image', 'core/paragraph', 'core/columns' );

    // Start InnerBlocks with a template.
    $template = array(
        array( 'core/columns', 
            array(),
            array(
                array( 'core/column',
                    array(),
                    array(
                        array('core/image')
                    )
                ),
                array( 'core/column',
                    array(),
                    array(
                        array('core/paragraph', 
							array(
								'placeholder' => 'Enter side content...'
							)
						)
                    )
                ),
            )
        )
    ); ?>
	<InnerBlocks 
		allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
        template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
		templateLock="false" <?php // https://github.com/WordPress/gutenberg/blob/trunk/packages/block-editor/src/components/inner-blocks/README.md#templatelock ?>
    />
</div>
