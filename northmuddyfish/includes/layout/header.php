<?php if( !defined( 'ABSPATH' )){ exit; } // Exit if accessed directly

/**
 * Site Header
 * @property hook before_site_header
 * @property hook site_header
 * @property hook after_site_header
 * 
 * Main Container
 * @property hook before_main_content
 * @property hook main_content
 * @property hook after_main_content
 * 
 * Page Header
 * @property hook before_entry_header
 * @property hook entry_header
 * @property hook entry_header, do_entry_title, 10			Page Title
 * @property hook entry_header, site_breadcrumbs, 15		Breadcrumbs
 * @property hook after_entry_header
 * 
 * Page Content
 * @property hook before_entry
 * @property hook entry
 * @property hook entry, the_content, 10					Page Content
 * @property hook after_entry
 * 
 * Extra Functions
 * @property hook wrapper_open			Page Wrapper
 * @property hook wrapper_close			Page Wrapper
**/

add_action('site_header', 'primary_site_header', 1);
add_action( 'header_navigation', 'my_account', 1 );
add_action( 'header_navigation', 'site_navigation', 2 );
add_action( 'header_navigation', 'mobile_nav_button', 3 );
add_action( 'before_main_content', 'header_placeholder', 1 );

// The action = site header
function primary_site_header(){
	?>
	<div class="wrapper">
		<?php 
		$site_info = get_bloginfo('name');

		if ( function_exists( 'get_field' ) ) {
			$logo = get_field('logo', 'options'); ?>
			<a href="<?php echo esc_url( site_url() ); ?>" class="site-logo" title="<?php echo $site_info; ?>">
				<img src="<?php echo $logo['url']; ?>" width="<?php echo $logo['width']; ?>" height="<?php echo $logo['height']; ?>" alt="<?php echo $site_info; ?>" />
			</a>
			<?php 
		} else { ?>
			<a href="<?php echo esc_url( site_url() ); ?>" class="site-logo" title="<?php echo $site_info; ?>">
				<?php echo $site_info; ?>
			</a>
			<?php 
		} ?>
		
		<div class="wrapper">
			<?php do_action('header_navigation'); ?>
		</div>
	</div>
	<?php
}

function header_placeholder(){ ?>
	<div id="navigation_placeholder"></div>
	<?php
}

function mobile_nav_button(){ ?>
	<button class="hamburger hamburger--squeeze" type="button">
		<span class="hamburger-box">
			<span class="hamburger-inner"></span>
		</span>
	</button>
	<?php 
}

// Primary navigation
function site_navigation() {
	?>
	<nav class="main-nav">
		<?php
		wp_nav_menu( array(
			'theme_location'		=> "primary-navigation",	// Must be registered with register_nav_menu() in order to be selectable by the user
			'container'				=> '',
			'fallback_cb'			=> 'page_menu_fallback',
			'walker'				=> new walker_texas_ranger,
		) );
		?>
	</nav>
	<?php
}

add_filter('wp_nav_menu_items', 'add_admin_link', 10, 2);
function add_admin_link($items, $args){
    if( $args->theme_location == 'primary-navigation' ){

		// Logged in user
		if( is_user_logged_in() ){
			$user = wp_get_current_user();
			$menu_location = 'my_account';
			$locations = get_nav_menu_locations();

			// Check for My Account Menu and possibly add a log out link
			if (isset($locations[$menu_location]) && $locations[$menu_location] != 0) {
				$items .= wp_nav_menu( array(
					'theme_location'		=> $menu_location,
					'container'				=> false,
					'echo' 					=> false,
					'items_wrap' 			=> '%3$s',
					'fallback_cb'			=> 'mobile_menu_addon',
					'walker'				=> new mobile_walker_texas_ranger
				) );
			} else {
				$items .= '<li class="menu__item mobile"><a href="'. esc_url( wp_logout_url( home_url() ) ) . '" class="menu__link">Log Out</a></li>';
			}

			include( THEME_ABS . '/functions/includes/settings/my-account-creation.php' );
			
			// Add My Account links
			$my_account = '<li class="menu__item menu__item--has-children mobile">';
				$my_account .= '<a href="'. esc_url( home_url('/my-account/') ) . '" class="menu__link">My Account</a>';
				$my_account .= '<ul>';
				
					foreach( $args['tabs'] as $tab ){
						$slug = $tab['slug'];
						$tab_title = $tab['tab_title'];
						$page_title = $tab['page_title'];
						$icon = $tab['icon'];
					
						$class = '';
						if( ! empty( $tab['class'] ) ){
							$class .= ' class="';
							$class .= $tab['class'];
							$class .= '"';
						}
					
						$access = false;
						if( ! empty($tab['access']) ){
							if( in_array($user->roles[0], ['administrator', 'an_administrator', 'editor', 'author', 'contributor']) ){
								$access = true;
							}
						} else {
							$access = true;
						}
					
						if( $access ){
							$my_account .= '<li class="menu__item menu__item--child">';
								$my_account .= '<a href="'. esc_url( home_url("/my-account/?active_tab=". $slug) ) . '" class="menu__link">'. $tab_title .'</a>';
							$my_account .= '</li>';
						}
					}

				$my_account .= '</ul>';
			$my_account .= '</li>';

			return $my_account . $items;

		// Not Logged-in
		} else {
			$user_login = '<li class="menu__item mobile block-columns btn">';
				$user_login .= '<div class="wp-block-button">';
					$user_login .= '<a href="'. esc_url( home_url("/login/") ) .'" class="wp-block-button__link wp-element-button">Login</a>';
				$user_login .= '</div>';
				$user_login .= '<div class="wp-block-button">';
					$user_login .= '<a href="'. esc_url( home_url("/register/") ) .'" class="wp-block-button__link wp-element-button">Create Account</a>';
				$user_login .= '</div>';
			$user_login .= '</li>';
			
			$items .= $user_login;
		}
    }
	
    return $items;
}

// My Account Register / Login / Menu
function my_account() {
	if( is_user_logged_in() ){ ?>
		<div class="my-account">
			<?php 
			$user = wp_get_current_user();
			$menu_location = 'my_account';
			$locations = get_nav_menu_locations();

			/**
			 * @see https://www.itsupportguides.com/knowledge-base/wordpress/using-wordpress-get_nav_menu_locations-php-function/
			 */
			if (isset($locations[$menu_location]) && $locations[$menu_location] != 0) {
				wp_nav_menu( array(
					'theme_location'		=> $menu_location,			// Must be registered with register_nav_menu() in order to be selectable by the user
					'container'				=> '',
					'walker'				=> new walker_texas_ranger,
				) );
			} else { ?>
				<ul class="menu">
					<li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="menu__link">Log Out</a></li>
				</ul>
			<?php } ?>
			
			<a id="head-my-account-container" href="<?php echo esc_url( home_url('/my-account/') ); ?>">
				<img src="<?php echo get_avatar_url($user->ID, ['size' => '43']); ?>" width="43" height="43" alt="<?php echo get_users_name(); ?>'s Profile Image" />
				<div class="block-columns">
					<span class="my-account-name">
						<?php echo "Welcome ". get_users_name(); ?>
					</span>
					<strong class="my-account-txt">
						My Account
					</strong>
				</div>
			</a>
		</div>

	<?php } else { ?>
		<div class="my-account">
			<ul class="menu">
				<li><a href="<?php echo esc_url( home_url('/login/') ); ?>" class="menu__link">Login</a></li>
				<li>
					<div class="wp-block-button">
						<a href="<?php echo esc_url( home_url('/register/') ); ?>" class="wp-block-button__link wp-element-button">Create Account</a>
					</div>
				</li>
			</ul>
		</div>
	<?php }
}

// Possibly append Log Out link to the My Account Menu
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
	if ($args->theme_location == 'my-account' || $args->theme_location == 'my_account') {
		$items .= '<li class="menu__item mobile"><a href="'. esc_url( wp_logout_url( home_url() ) ) .'" class="menu__link">Log Out</a></li>';
	}
	return $items;
}

// Check ACF exists first
if ( function_exists( 'get_field' ) ) {
	add_action('before_main_site', 'page_header_status', 5);
	function page_header_status(){
		
		// Should we remove the default page header?
		$header_status = get_field('page_header_status');
		if( $header_status !== 'enable' ){
			remove_action( 'entry', 'do_entry_header', 1);
		}
	}
}
