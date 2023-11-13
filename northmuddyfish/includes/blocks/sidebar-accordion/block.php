<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */


// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "sidebar-accordion"
] );

?>
<div <?php echo $wrapper_attributes; ?>>
    <?php
    $template = array(
        array( 'core/heading',
            array(
                'placeholder'   => 'Lorum Ipsum',
                'level'         => 3,
                'className'     => 'accordion-header',
            ),
        ),
        array('core/shortcode'),
    ); ?>
	<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
</div>
