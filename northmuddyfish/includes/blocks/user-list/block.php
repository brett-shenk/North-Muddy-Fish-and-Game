<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

if ( function_exists( 'get_field' ) ) {
    $user_type = !empty( get_field('user_type') )           ? get_field('user_type')        : 'subscriber';
    $positions    = !empty( get_field('positions') )        ? get_field('positions')        : NULL;
} else {
    $user_type = 'subscriber';
    $positions = NULL;
}

if ( !defined('SLA_VERSION') ){
    return 'Error Simple Local Avatars plugin is missing.';
}


if( $user_type == 'subscriber' ){
    $args_user = array(
        'role__in'      => [ 'subscriber' ],
        'number'       	=> -1,
        'order' 		=> 'ASC',
        'orderby' 		=> 'display_name',
    );

} else {
    $args_user = array(
        'role__in'      => [ 'administrator', 'an_administrator', 'editor', 'author', 'contributor' ],
        'number'       	=> -1,
        'order' 		=> 'ASC',
        'orderby' 		=> 'positions-title display_name',
        'meta_key'		=> 'positions-title',
        'meta_value'	=> $positions,
        'meta_compare'	=> 'LIKE'
    );
}

$user_query = new WP_User_Query( $args_user );
if( ! empty( $user_query->get_results() ) ){
    foreach( $user_query->get_results() as $user ){
        $args_template = [
            'ID'            => $user->ID,
            'display_name'  => $user->display_name,
            'user_email'    => $user->user_email,
            'roles'         => $user->roles,
        ];

        get_template_part( 'includes/blocks/user-list/block-user', null, $args_template );
    }
} else {
    if( is_admin() ){
        echo 'No users found';
    } else {
        echo '<!-- No users found. -->';
    }
}

?>
