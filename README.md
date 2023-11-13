# North Muddy Fish & Game Association
[Live](https://www.northmuddy.com/) &nbsp; | &nbsp; [dev](http://192.168.0.241:8080/) &nbsp; | &nbsp; [Database](http://192.168.0.241:8282/?server=192.168.0.241%3A3306) 

## Getting Started
Run `npm install`

#### Requirements
- NODE `>=16.17.1`
- NPM `>=8.15.0`
- GULP `4.0.2`


## Commands

<details>
    <summary><strong>Gulp</strong></summary>

The core processes of the theme and what it can do.
- `gulp` watch for and compile theme assets
- `gulp dev` / `gulp prod` processes the theme assets once
- dev is default. prod compresses and removes comments
- `gulp images` compress images
- `gulp icons` Re-generate the icon font
- `gulp fonts` generate fonts

</details>
<details>
    <summary><strong>Browserslist</strong></summary>

The can-i-use for Gulp in processing SASS. Auto including helpful stuff for older browsers so you don't have to.
- `npx browserslist` | Lists all supported browsers
- `npx browserslist@latest --update-db` | Update to the latest browsers. Need to process sass afterwords.

</details>
<details>
    <summary><strong>Lighthouse</strong></summary>

| Commands                           | Description |
| ---------------------------------- | ---------------------------- |
| `npm install lighthouse -g`        | Install the package globally for the first time. |
| `lighthouse <url>`                 | Check a URL for mobile |
| `lighthouse <url> --preset=desktop`| Check URL for desktop |
| `lighthouse <url> --view`          | View report after it's ran |

</details>
<details>
    <summary><strong>Debug</strong></summary>

| Commands                         | Description                                  |
| -------------------------------- | -------------------------------------------- |
| `gulp CleanHouse`                | Removes everything inside of the dist folder |
| `npx rimraf ./**/node_modules`   | *Efficiently* remove node_modules folder     |

</details>

## Additional Sections

#### [Theme Functionality](./documentation/FUNCTIONALITY.md)

#### [Resources](./documentation/RESOURCE.md)

#### [WordPress Functions](./documentation/CORE.md)

#### Icon Preparation
- Color does not matter. It's changed to black regardless.
- Its trying to unify everything in size but it still does matter if one is too small for your liking.
- If size is a problem, a common and recommended size is a 50x50 pixel box in Illustrator.
- *The Icon's invisible! HELP!* First, don't panic.. yet, and open the icon in Illustrator. Next select the whole icon and right click it. If the option to `ungroup` is showing ungroup everything. Then de-select everything and re-select the whole icon and this time `group` everything. Now that you are sure everything is in 1 group with everything still selected, at the top of Illustrator `Object` > `Path` > `Outline Stroke`. Then `File` > `Save` and close! If that didn't solve your problem, time to panic.
