<small> [Back to Readme](./../../README.md) </small>

# Resources

<details>
    <summary><strong>Server Configuration</strong></summary>

WordPress installed on Unraid has its own apache built in.

| Commands                         | Description                                  |
| -------------------------------- | -------------------------------------------- |
| `a2enmod <module_name>`          | To install a module such as... |
| `a2enmod headers`                | headers_module, mod_headers. Which is required for some plugins |

</details>
<details>
    <summary><strong>Permissions</strong></summary>

| File          | Permissions |
| ------------- | ----------- |
| wp-config.php | 400         |
| .htaccess     | 444 / 644   |
| Folders       | 755         |
| Files         | 644         |

</details>
<details>
    <summary><strong>.htaccess</strong></summary>

```apache
# Place in Primary .htaccess File
# Disable directory browsing
Options -Indexes

# Protect Uploads Folder
# Kill PHP Execution
<Files *.php>
deny from all
</Files>
```

</details>
<details>
    <summary><strong>wp-config.php</strong></summary>

```php
// Set environment type  [local, development, staging, and production]
define( 'WP_ENVIRONMENT_TYPE', 'development' );

if( defined( 'WP_ENVIRONMENT_TYPE' ) == 'development' ){
	
	// Enable WP_DEBUG mode
	define( 'WP_DEBUG', true );
	
	// Enable Debug logging to the /wp-content/debug.log file
	define( 'WP_DEBUG_LOG', true );
	
	// Disable display of errors and warnings
	define( 'WP_DEBUG_DISPLAY', false );
	@ini_set( 'display_errors', 0 );
}

// Disable Contact Form 7 JavaScript
define( 'WPCF7_LOAD_JS', false );

// Disable Contact Form 7 StyleSheet
define( 'WPCF7_LOAD_CSS', false );
```

</details>

<br />

<details>
    <summary><strong>Core WordPress Links</strong></summary>

- [WP_Query Arguments](https://www.billerickson.net/code/wp_query-arguments/)
- [Core Blocks](https://developer.wordpress.org/block-editor/reference-guides/core-blocks/)
- [Theme json](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/)
- [Theme json Advanced](https://developer.wordpress.org/themes/advanced-topics/theme-json/)
- [Block json](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/)

</details>
<details>
    <summary><strong>Vendors Links</strong></summary>

- See FUNCTIONALITY.md.
- [Social Sharing Buttons](https://www.cssscript.com/customizable-social-sharing-buttons/)
- [Parallax](http://pixelcog.github.io/parallax.js/)

</details>

<br />

<details>
    <summary><strong>Licenses</strong></summary>

- [Licenses](https://choosealicense.com/)
- [All Licenses](https://choosealicense.com/appendix/)

</details>
<details>
    <summary><strong>Popular Features</strong></summary>

- [Pagination for Custom Loops](https://rudrastyh.com/wordpress/multisite-posts-pagination.html)
- [Infinite Scrolling](https://www.billerickson.net/infinite-scroll-in-wordpress/)
- [Load More Posts Button + Numeric Pagination](https://rudrastyh.com/wordpress/load-more-and-pagination.html)
- [Previous Next Post Navigation for Multiple Post Types at once](https://rudrastyh.com/wordpress/previous-next-multiple-post-types.html)

### AJAX
- [Load More Posts with AJAX](https://rudrastyh.com/wordpress/load-more-posts-ajax.html)
- [Combine AJAX load more button with AJAX filters](https://rudrastyh.com/wordpress/ajax-load-more-with-filters.html)
- [3 Steps to Create AJAX Post Filters](https://rudrastyh.com/wordpress/ajax-post-filters.html)
- [Comment Submission Without Page Refresh](https://rudrastyh.com/wordpress/ajax-comments.html)
- [Comments Load More. With Button or Infinity Scroll](https://rudrastyh.com/wordpress/load-more-comments.html)

</details>
<details>
    <summary><strong>Custom WordPress Admin</strong></summary>

- [WordPress plugin boilerplate](https://wppb.me/)
- [Create WordPress Plugin Settings Page](https://blog.wplauncher.com/create-wordpress-plugin-settings-page/)
- [How to Add Custom Endpoints to WordPress API](https://artisansweb.net/how-to-add-custom-endpoints-to-wordpress-api/)
- [Properly Include CSS and JS in your WordPress Themes and Plugins](https://rudrastyh.com/wordpress/include-css-and-javascript.html)
- [Custom Fields in Bulk Edit](https://rudrastyh.com/wordpress/bulk-edit.html)
- [Custom Fields in Quick Edit](https://rudrastyh.com/wordpress/quick-edit-tutorial.html)
- [Add Custom Fields to User Profiles](https://rudrastyh.com/wordpress/custom-fields-in-user-profiles.html)
- [Everything about plugin_action_links and plugin_row_meta](https://rudrastyh.com/wordpress/plugin_action_links-plugin_row_meta.html)

</details>
<details>
    <summary><strong>Private Plugins</strong></summary>

- [How to Configure Self-Hosted Updates for Your Private Plugins](https://rudrastyh.com/wordpress/self-hosted-plugin-update.html)
- [Check License in Plugin Updates](https://rudrastyh.com/wordpress/check-license-key-in-plugin-updates.html)

</details>
