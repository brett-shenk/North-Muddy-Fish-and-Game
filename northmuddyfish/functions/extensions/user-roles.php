<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly
/**
 * User Role Functions
 * 
 * @internal    User Roles In Order:
 *         Role name | Display name for role
 *   administrator      Developer
 *   an_administrator   An Administrator
 *   editor             Editor
 *   author             Author
 *   contributor        Contributor
 *   subscriber         Subscriber
**/

/**
 * Rename the original Administrator role to Developer
 * 
 * @category    Trigger on Page Switch
 */
add_action('after_switch_theme', 'change_administrator_role_name');
function change_administrator_role_name() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
    $wp_roles = new WP_Roles();
    $wp_roles->roles['administrator']['name'] = 'Developer';
    $wp_roles->role_names['administrator'] = 'Developer';
}

/**
 * Create custom role...
 * 
 * @category    Trigger on Page Switch
 * @link        https://wordpress.org/documentation/article/roles-and-capabilities/
 */
add_action('after_switch_theme', 'add_client_admin_role');
function add_client_admin_role() {
    
    // Client if it doesn't exist
    if( ! wp_roles()->is_role( 'an_administrator' ) ){
        add_role(
            'an_administrator',
            'An Administrator',
            array(
                "activate_plugins"          => true,
                "delete_others_pages"       => true,
                "delete_others_posts"       => true,
                "delete_pages"              => true,
                "delete_posts"              => true,
                "delete_private_pages"      => true,
                "delete_private_posts"      => true,
                "delete_published_pages"    => true,
                "delete_published_posts"    => true,
                "edit_dashboard"            => true,
                "edit_others_pages"         => true,
                "edit_others_posts"         => true,
                "edit_pages"                => true,
                "edit_posts"                => true,
                "edit_private_pages"        => true,
                "edit_private_posts"        => true,
                "edit_published_pages"      => true,
                "edit_published_posts"      => true,
                "edit_theme_options"        => true,
                "export"                    => true,
                "import"                    => true,
                "list_users"                => true,
                "manage_categories"         => true,
                "manage_links"              => true,
                "manage_options"            => true,
                "moderate_comments"         => true,
                "promote_users"             => true,
                "publish_pages"             => true,
                "publish_posts"             => true,
                "read_private_pages"        => true,
                "read_private_posts"        => true,
                "read"                      => true,
                "create Reusable Blocks"    => true,
                "edit Reusable Blocks"      => true,
                "read Reusable Blocks"      => true,
                "delete Reusable Blocks"    => true,
                "remove_users"              => true,
                "switch_themes"             => true,
                "upload_files"              => true,
                "customize"                 => true,
                "update_core"               => false,
                "update_plugins"            => false,
                "update_themes"             => false,
                "install_plugins"           => true,
                "install_themes"            => false,
                "delete_themes"             => false,
                "delete_plugins"            => true,
                "edit_plugins"              => true,
                "edit_themes"               => true,
                "edit_users"                => true,
                "add_users"                 => true,
                "create_users"              => true,
                "delete_users"              => true,
                "unfiltered_html"           => true
            )
        );
    }
}

// Was a plugin updated? Let's update the role's capabilities to match.
add_action( 'activated_plugin', 'update_an_administrator_capabilities', 50, 2 );
function update_an_administrator_capabilities( $plugin, $network_activation ){
    if( wp_roles()->is_role( 'an_administrator' ) ){
        
        // Role 1
        $role = get_role( 'an_administrator' );
        $role_cap = array_keys( $role->capabilities );

        // Role 2
        $admin = get_role( 'administrator' );
        $admin_cap = array_keys( $admin->capabilities );
        
        // Remove Duplicate's already present and undesirable ones
        $admin_cap = array_diff( $admin_cap, ['update_core', 'update_plugins', 'update_themes', 'install_themes', 'delete_themes'] );
        $admin_cap = array_diff( $admin_cap, $role_cap );
        $admin_cap = array_values($admin_cap);

        // Add any new capabilities
        if( isset($admin_cap) ){
            foreach( $admin_cap as $cap ){
                $role->add_cap( $cap );
            }
        }
    }
}

// Remove a capability
/*
add_action('admin_init', function(){
    global $wp_roles;
    $wp_roles->remove_cap('an_administrator', "delete_themes");
});
*/

// Hide input fields when uploading or editing media
add_action('admin_print_scripts', function(){
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
    
    if( $user_id != '' ){
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        if( in_array('administrator', $user_roles) && ! current_user_can('administrator') ){ ?>
            <style>
                body.user-edit-php #your-profile .user-role-wrap{ display: none; }
            </style>
        <?php }
    }
});

/**
 * Removes Administrator from roles list if user isn't an admin themselves.
 *
 * This way, only admins can make new admins.
 *
 * @param array $all_roles List of roles.
 * @return array Modified list of roles.
 */
add_filter( 'editable_roles', 'prevent_developer_role_being_selected' );
function prevent_developer_role_being_selected( $all_roles ) {
    if( ! current_user_can('administrator') ){
        unset( $all_roles['administrator'] );
    }

    return $all_roles;
}
