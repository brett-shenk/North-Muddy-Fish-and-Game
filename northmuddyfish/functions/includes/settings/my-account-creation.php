<?php

if( class_exists( 'ACF' ) && ! empty( get_field('my_account_page', 'options') ) ){
    $my_account = get_field('my_account_page', 'options');
    $my_account = $my_account->ID;
} else {
    $my_account = url_to_postid( get_option('siteurl') );
}
$page_id = get_queried_object_id();

// Variables that are accessible on all my account page(s)
if( $my_account === $page_id ){
    $user_id = get_current_user_id();
    $profile_user = get_userdata( $user_id );

// What should those variables be on other pages? Lets save some resources
} else {
    $profile_user = '';
}

if( class_exists( 'ACF' ) && ! empty( get_field('my_account_page', 'options') ) ){
    $my_account_url = get_field('my_account_page', 'options');
    $my_account_url = esc_url( get_permalink( $my_account_url->ID ) );
} else {
    $my_account_url = esc_url( home_url('/') );
}

/**
 * @package      My Account Pages
 * 
 * @version 	 2.1
 * @license		 GNU AGPLv3  https://choosealicense.com/licenses/agpl-3.0/
 * @copyright    Brett Shenk 2023
 * 
 * @param string slug           Used to identify what content goes with what tab. Also generates a query string 
 *                                  for the page to be visited.  Ex: ?active_tab=[your-tab-slug-here]
 * @param string tab_title      Tab Sidebar Name.
 * @param string page_title     Blue Bar Page Title.
 * @param string icon           The Tab Sidebar Icon.
 * @param string class          Extra button classes. Active must be on one of them.
 * @param bool   access         How should the page be displayed?  [true, '']
 */
$args = [
    'data' => $profile_user,
    'my-account-url' => $my_account_url,
    'tabs' => [
        'tab_1' => [ 
            'slug'          => 'account-details', 
            'tab_title'     => 'Account Details', 
            'page_title'    => 'My Account Details', 
            'icon'          => 'icon-person',
            'class'         => 'active',
        ],
        'tab_2' => [ 
            'slug'          => 'profile-image', 
            'tab_title'     => 'Profile Image', 
            'page_title'    => 'My Profile Image', 
            'icon'          => 'icon-camera',
        ],
        'tab_3' => [ 
            'slug'          => 'officer-details', 
            'tab_title'     => 'Officer Details', 
            'page_title'    => 'Officer Details', 
            'icon'          => 'icon-badge',
            'access'        => 'true'
        ]
    ]
];

if(in_array('document-library-pro/document-library-pro.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	$args['tabs']['tab_4'] = [
		'slug'          => 'submit-document', 
        'tab_title'     => 'Submit A Document', 
        'page_title'    => 'Submit A Document', 
        'icon'          => 'icon-file-lines',
        'access'        => 'true'
	];
}
