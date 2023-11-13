<small> [Back to Functionality](./../../../../documentation/FUNCTIONALITY.md) </small>

# Mmenu Light

<img src="https://mmenujs.com/img/preview-mmlight.png" alt="lightweigh mobile menu" width="100%" />

## Overview

The (extremely) lightweight alternative to the mmenu.js plugin for creating off-canvas mobile menus with the exact look and feel.

This documentation page assumes you have some knowledge of HTML, CSS and JavaScript. If you are totally new to frontend development, it might not be the best idea to jump right into a framework as your first step - grasp the basics then come back!

> If you're new to using the mmenu light plugin, try the [tutorial](https://www.mmenujs.com/mmenu-light/tutorial.html).
>
> You can also checkout the [changelog](https://www.mmenujs.com/mmenu-light/changelog.html).

#### Example:
```Javascript
<script>
    document.addEventListener(
        "DOMContentLoaded", () => {
            const menu = new MmenuLight(
                document.querySelector( "#menu" )
            );

            const navigator = menu.navigation();
            const drawer = menu.offcanvas();

            document.querySelector( 'a[href="#menu"]' )
                .addEventListener( 'click', ( evnt ) => {
                    evnt.preventDefault();
                    drawer.open();
                });
        }
    );
</script>
```

<br />

## Create a menu
After the plugin is invoked, there are two add-ons available for the menu: navigation and offcanvas.

| Function        | Description                                                                                                            |
| --------------- | ---------------------------------------------------------------------------------------------------------------------- |
| navigation()    | Creates the navigation with a fixed navbar and sliding submenus from &lt;nav&gt;, &lt;ul&gt;, &lt;li&gt; and &lt;a&gt; (or &lt;span&gt;) elements. |
| offcanvas()     | Creates an off-canvas drawer. This drawer can be populated with any kind of content. |

<br />

## Options

The mmenu light plugin and both the navigation and offcanvas add-ons provide a set of options for customizing your menu. The default values can be overridden by passing new values to the method.

```javascript
<script>
    document.addEventListener(
        "DOMContentLoaded", () => {
            const menu = new MmenuLight(
                document.querySelector( "#menu" ),
                "(max-width: 600px)"
            );

            const navigator = menu.navigation({
                // options
            });

            const drawer = menu.offcanvas({
                // options
            });
        }
    );
</script>
```

### Options for the plugin

| Option                | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| node                  | HTMLElement     | null            | The HTML element you want to turn in an off-canvas menu.                 |
| mediaQuery            | String          | "all"           | MediaQuery for when the menu should be enabled.                          |

### Options for the navigation add-on

| Option                | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| selected              | String          | "Selected"      | Classname to use for finding the selected &lt;li&gt;.                          |
| slidingSubmenus       | boolean         | true            | Wether or not to use sliding submenus (forced to be false in IE11).      |
| title                 | String          | "Menu"          | The title above the main panel. For subpanels the text in its parent menu item is used. |
| theme                 | String          | "light"         | Color scheme to use. Possible values: "light" or "dark"                  |

### Options for the offcanvas add-on

| Option                | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| position              | String          | "left"          | The position for the menu. Possible values: "left" or "right"            |

<br />

## Styling

It's pretty easy change the styling for the mmenu light plugin, just override some of the CSS values and variables.

```css
<style>
    .mm {
        background: #ffe;
        color: #330;
        --mm-spn-item-height: 46px;
        --mm-ocd-max-width: 500px;
    }
</style>
```

### CSS for the navigation add-on

| Variables             | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| background-color      | color           | #f3f3f3         | Background-color for the menu.                                           |
| color                 | color           | #444            | Color for the text and borders in the menu.                              |
| --mm-spn-item-height  | pixels          | 50px            | Height for menu items.                                                   |
| --mm-spn-item-indent  | pixels          | 20px            | Indent for menu items.                                                   |
| --mm-spn-line-height  | pixels          | 24px            | Line-height for text in the menu.                                        |

### CSS for the offcanvas add-on

| Variables             | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| top                   | pixels          | 0               | Position relative to the top of the page.                                |
| bottom                | pixels          | 0               | Position relative to the bottom of the page.                             |
| --mm-ocd-width        | percentage      | 80%             | Width for the menu.                                                      |
| --mm-ocd-min-width    | pixels          | 200px           | Minimal width for the menu.                                              |
| --mm-ocd-max-width    | pixels          | 440px           | Maximum width for the menu.                                              |

<br />

## API

After invoking the add-ons, an API is available for each add-on. With this API, you can invoke the add-on methods to control the plugin manually.

```Javascript
<script>
    document.addEventListener(
        "DOMContentLoaded", () => {
            const menu = new MmenuLight(
                document.querySelector( "#menu" )
            );

            const navigator = menu.navigation();
            const drawer = menu.offcanvas();

            navigator.openPanel(
                document.querySelector( "#ul" );
            );

            document.querySelector( 'a[href="#menu"]' )
                .addEventListener( 'click', ( evnt ) => {
                    evnt.preventDefault();
                    drawer.open();
                });
        }
    );
</script>
```

### API methods for the navigation add-on

| Method( arguments )   | Datatype        | Default value   | Description                                                              |
| --------------------- | --------------- | --------------- | ------------------------------------------------------------------------ |
| openPanel             |                 |                 | Invoke this method to open a panel in the menu.                          |
| ( panel )             | HTMLElement     | null            | Panel (UL) to open.                                                      |

### API methods for the offcanvas add-on

| Method( arguments )   | Description                                                              |
| --------------------- | ------------------------------------------------------------------------ |
| open                  | Invoke this method to open the menu.                                     |
| close                 | Invoke this method to close the menu.                                    |

<br />

## License

The mmenu-light plugin is licensed under the [CC-BY-4.0 license](https://choosealicense.com/licenses/cc-by-4.0/).
