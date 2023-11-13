<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "resources-blk-container"
] );

if ( function_exists( 'get_field' ) ) {
    $header = get_field('br_resource_header')   ? get_field('br_resource_header')   : 'Lorum Ipsum';
    $content = get_field('br_description')      ? get_field('br_description')       : '';
} else {
    $header = 'Lorum Ipsum';
    $content = '';
}
?>
<div <?php echo $wrapper_attributes; ?>>
    <?php if( $content != '' ){ ?>
        <h3><?php echo $header; ?></h3>
    <?php } else { ?>
        <h3 style="margin-bottom: 0;"><?php echo $header; ?></h3>
    <?php } ?>

    <div class="resource-content">
        <?php echo $content; ?>
    </div>
    <div class="resource-button-wrapper">
        <div class="resource-button">
            <span></span>
        </div>
        <div class="resource-cta-drop-down">
            <?php
            // Restrict InnerBlocks to allowed block list.
            $allowed_blocks = array( 'shenk/contact-info' );
    
            // Start InnerBlocks with a template.
            $template = array( 
                array(
                    'shenk/contact-info', 
                    array(
                        'cib_what_kind' => 'phone',
                        'cib_phone_number' => '123-456-7890'
                    )
                )
            );
            ?>
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
                template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
            />
        </div>
    </div>
</div>
