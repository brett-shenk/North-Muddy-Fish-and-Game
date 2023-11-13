<small> [Back to Readme](./../README.md) </small>

# WordPress Functions

#### Some useful remarks

The meaning of the `get_` prefix - There are pairs of functions whose names differ only by the `get_` prefix. The function with `get_` simply returns some value, while the function without `get_` displays the value. Example: `the_title()` - `get_the_title()`

The meaning of the `_e` suffix - There are pairs of functions whose names differ only in the ending `_e` (echo). The function with the `_e` suffix displays the value, while the other simply returns the value. Example: `esc_html()` - `esc_html_e()`

#### Links

<https://www.pontikis.net/blog/most-commonly-used-wordpress-functions>

---

<details>
    <summary><strong>Generally Useful</strong></summary>

| Function                       | Description                                                          |
| ------------------------------ | -------------------------------------------------------------------- |
| home_url()                     | https://[your-domain].com &nbsp; Note: The lack of trailing slash    |
| get_bloginfo()                 | Retrieves information about the current site &nbsp; [Docs](https://developer.wordpress.org/reference/functions/get_bloginfo/#description) |
| get_stylesheet_directory_uri() | Retrieves stylesheet directory URI for the active theme. To avoid being overridden by a child theme use: get_template_directory_uri() |
| get_template_directory_uri()   | Retrieves template directory URI for the active theme.               |
| wp_get_theme()                 | Get info from style.css &nbsp; [Docs](https://developer.wordpress.org/reference/functions/get_theme_mod/) |
| get_theme_file_uri()           | Searches in the stylesheet directory before the template directory so themes which inherit from a parent theme can just override one file. |
| get_template_part()            | Loads a template part into a template &nbsp; [Docs](https://developer.wordpress.org/reference/functions/get_template_part/) |
| get_option()                   | Retrieves an option value based on an option name.                   |
| antispambot()                  | Prevent Email Spam with email address obfuscating.                   |
| wp_get_attachment_image()      | $attachment_id, $size = 'thumbnail', $icon = false, $attr = [] &nbsp; [Docs](https://developer.wordpress.org/reference/functions/wp_get_attachment_image/) |

</details>
