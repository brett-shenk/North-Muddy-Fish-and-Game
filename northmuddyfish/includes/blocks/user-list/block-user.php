<?php
$user_id        = $args['ID'];
$display_name   = $args['display_name'];
$user_email     = $args['user_email'];
$roles          = $args['roles'];

$user_profile_id = get_user_meta($user_id, 'simple_local_avatar', true);
$position = ( $position = get_field('positions-title', 'user_'. $user_id) ) ? $position : '';

require( THEME_ABS . '/functions/includes/settings/allowed-tags.php' );
$allowed_roles = [
    'administrator',
    'an_administrator',
    'editor',
    'author',
    'contributor'
];

if( is_admin() ){       // Prevent links from being annoying when in the editor
    $tag = "div";
} else {
    $tag = "a";
}
?>

<div class="user-block">
    <div class="column">

        <div>
            <?php
            if( isset($user_profile_id) && !empty($user_profile_id["media_id"]) ){
                echo wp_get_attachment_image( $user_profile_id["media_id"], array('300', '300'), false, array( "class" => "profile-image", "alt" => esc_html( $display_name ). "'s Profile Image" ) );
            } else {
                echo wp_get_attachment_image( get_option('simple_local_avatar_default'), array('300', '300'), false, array( "class" => "profile-image", "alt" => esc_html( $display_name ). "'s Profile Image" ) );
            } ?>
        </div>

        <?php
        if( !empty( array_intersect($allowed_roles, $roles) ) && !empty($position) ){

            // Are there multiple positions?
            if( gettype($position) == 'array' ){
                foreach($position as $single_position){
                    $term = get_term( $single_position );
                    if( $term !== null ){
                        echo '<strong>'. $term->name .'</strong>';
                    }
                }

            // Or just one?
            } else {
                $term = get_term( $position );
                if( $term !== null ){
                    echo '<strong>'. $term->name .'</strong>';
                }
            }
            echo '<br />';
        }
        ?>

        <span><?php echo esc_html( $display_name ); ?></span>

        <?php if( !empty( array_intersect($allowed_roles, $roles) ) ){
            $display_contact = ( $display_contact = get_field('display_contact_info', 'user_'. $user_id) ) ? $display_contact : 'email+phone';
            $phone = esc_html( get_user_meta($user_id, 'phone', true) );
            $email = antispambot( esc_html($user_email) );

            if( $display_contact == 'email+phone' ){
                echo '<br />';
                if( !empty( $phone ) ){
                    echo '<'. $tag . ' href="tel:'. $phone .'" class="link">'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M39.75 42q-6.1 0-12.125-3T16.8 31.2Q12 26.4 9 20.375 6 14.35 6 8.25q0-.95.65-1.6Q7.3 6 8.25 6h7q.7 0 1.225.475.525.475.675 1.275l1.35 6.3q.1.7-.025 1.275t-.525.975l-5 5.05q2.8 4.65 6.275 8.1Q22.7 32.9 27.1 35.3l4.75-4.9q.5-.55 1.15-.775.65-.225 1.3-.075l5.95 1.3q.75.15 1.25.75T42 33v6.75q0 .95-.65 1.6-.65.65-1.6.65Zm-28.3-23.4 4.05-4.1L14.35 9H9q0 1.95.6 4.275t1.85 5.325ZM29.9 36.75q2.05.95 4.45 1.55 2.4.6 4.65.7v-5.35l-5.15-1.05ZM11.45 18.6ZM29.9 36.75Z"/></svg>
                        <span><?php echo $phone; ?></span>
                    <?php echo '</'. $tag . '>';
                }
                if( !empty( $email ) ){
                    echo '<'. $tag . ' href="mailto:'. $email .'" class="link">'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M7 40q-1.2 0-2.1-.9Q4 38.2 4 37V11q0-1.2.9-2.1Q5.8 8 7 8h34q1.2 0 2.1.9.9.9.9 2.1v26q0 1.2-.9 2.1-.9.9-2.1.9Zm17-15.1L7 13.75V37h34V13.75Zm0-3L40.8 11H7.25ZM7 13.75V11v26Z"/></svg>
                        <span><?php echo $email ?></span>
                    <?php echo '</'. $tag . '>';
                }
            } else if( $display_contact == 'phone' ){
                echo '<br />';
                if( !empty( $phone ) ){
                    echo '<'. $tag . ' href="tel:'. $phone .'" class="link">'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M39.75 42q-6.1 0-12.125-3T16.8 31.2Q12 26.4 9 20.375 6 14.35 6 8.25q0-.95.65-1.6Q7.3 6 8.25 6h7q.7 0 1.225.475.525.475.675 1.275l1.35 6.3q.1.7-.025 1.275t-.525.975l-5 5.05q2.8 4.65 6.275 8.1Q22.7 32.9 27.1 35.3l4.75-4.9q.5-.55 1.15-.775.65-.225 1.3-.075l5.95 1.3q.75.15 1.25.75T42 33v6.75q0 .95-.65 1.6-.65.65-1.6.65Zm-28.3-23.4 4.05-4.1L14.35 9H9q0 1.95.6 4.275t1.85 5.325ZM29.9 36.75q2.05.95 4.45 1.55 2.4.6 4.65.7v-5.35l-5.15-1.05ZM11.45 18.6ZM29.9 36.75Z"/></svg>
                        <span><?php echo $phone; ?></span>
                    <?php echo '</'. $tag . '>';
                }
            } else if( $display_contact == 'email' ){
                echo '<br />';
                if( !empty( $email ) ){
                    echo '<'. $tag . ' href="mailto:'. $email .'" class="link">'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M7 40q-1.2 0-2.1-.9Q4 38.2 4 37V11q0-1.2.9-2.1Q5.8 8 7 8h34q1.2 0 2.1.9.9.9.9 2.1v26q0 1.2-.9 2.1-.9.9-2.1.9Zm17-15.1L7 13.75V37h34V13.75Zm0-3L40.8 11H7.25ZM7 13.75V11v26Z"/></svg>
                        <span><?php echo $email ?></span>
                    <?php echo '</'. $tag . '>';
                }
            } else{
                echo "<!-- The user has chosen nothing or has nothing entered. -->";
            }
        } ?>
    </div>

    <?php
    if( !empty( array_intersect($allowed_roles, $roles) ) ){ ?>
        <div class="column">
            <?php
            $description = get_field( 'user_description', 'user_' . $user_id );
            
            if( isset( $description ) ){
                $description = clean_hex( $description );
                $description = wp_kses( $description, $allowed_tags );
                // echo '<div class="user-content">'. shenk_input_formatter($description) .'</div>';
                echo '<div class="user-content">'. $description .'</div>';
            }
            ?>
        </div>
    <?php } ?>
</div>
