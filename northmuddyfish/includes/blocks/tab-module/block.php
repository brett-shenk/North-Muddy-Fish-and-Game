<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

if ( function_exists( 'get_field' ) ) {
    $tab_name = get_field('btm_tab_name') ? get_field('btm_tab_name') : 'Category Name';
    $spotlight = get_field('tm_image_spotlight') ? get_field('tm_image_spotlight') : 'disabled';
    
    $member_block_status = get_field('member_list_status') ? get_field('member_list_status') : 'off';
    if( $member_block_status == 'on' ){
        $numb_of_rows = get_field('ml_numb_of_rows') ? get_field('ml_numb_of_rows') : '2';
        $block_width = get_field('ml_width') ? get_field('ml_width') : '1.2';
    }
} else {
    $tab_name = 'Category Name';
    $spotlight = 'disabled';
    
    $member_block_status = 'off';
    $numb_of_rows = '2';
    $block_width = '1.2';
}

if( $member_block_status == 'on' ){
    $member_block_attrs = ' data-rows="'. $numb_of_rows .'"';
    $member_block_attrs .= ' data-blk-width="'. $block_width .'"';
} else {
    $member_block_attrs = '';
}

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "tab-module-container",
    "data-tab-name" => $tab_name,
    "data-spotlight" => $spotlight,
] );

?>
<div <?php echo $wrapper_attributes . $member_block_attrs; ?>>
	<?php
    $template = array(
        array('core/paragraph', 
			array(
				'placeholder' => 'Enter content...'
			)
		)
    ); ?>
	<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
</div>
