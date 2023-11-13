const CONFIG = {
    "files": [                              // Asset files that need compiled
        {
            "id": "primary-theme",
            "styles": [ "assets/sass/theme.scss" ],
            "js":     [ 
                "assets/js/theme.js", 
                "assets/js/vendor/mmenu-light/mmenu-light.js"
            ],
            "images": [
                "assets/images/*",
                "assets/images/**/*"
            ]
        },
        {
            "id":       "fonts",
            "styles": [ "assets/sass/fonts.scss" ]
        },
        {
            "id":       "theme-admin",
            "styles": [ "assets/sass/admin.scss" ],
        },
        {
            "id":       "theme-login",
            "styles": [ "assets/sass/login-styles.scss" ]
        },
        {
            "id":       "theme-print",
            "styles": [ "assets/sass/print.scss" ]
        },
        {
            "id":       "my-account",
            "styles": [ "assets/sass/my-account.scss" ],
            "js":     [ "assets/js/my-account.js" ]
        },
        {
            "id":       "template-background-img",
            "styles": [ "assets/sass/template-background-img.scss" ]
        },
        {
            "id":       "posts",
            "styles": [ "assets/sass/posts.scss" ]
        },
        {
            "id":       "tribe-calendar",
            "styles": [ "assets/sass/vendor/tribe-calendar.scss" ]
        },
        {
            "id":       "error-page",
            "styles": [ "assets/sass/404.scss" ]
        },
        {
            "id":       "theme-editor",
            "js": [ "assets/js/editor.js" ]
        },
        {
            "id":   "theme-global",
            "js": [ "assets/js/global.js" ]
        },
        {
            "id":       "slick-slider",
            "styles": [ "assets/sass/vendor/slick.scss" ],
            "js":     [ "assets/js/vendor/slick/slick.min.js" ]
        },
        {
            "id":   "valid-form",
            "js": [
                // "assets/js/vendor/maskedinput/jquery.maskedinput.js",
                "assets/js/vendor/valid-form/valid-form.min.js",
            ]
        }
    ],
    "inlined": {                                // Asset files to be inlined in the header. Map file is excluded
        "styles": ["assets/sass/inline/*.scss"]
    },
    "font": {
        pathIn: "./assets/fonts/",              // Absolute path only
        pathOut: "./assets/fonts/",
        outputFormats: [".woff", ".woff2"],
        inputFormats: [".ttf"],                 // woff, woff2, svg, ttf, otf
    },
    "iconFont": {                               // Icon Font
        name :       "shenks-icons",
        folder :     "assets/icons/*.svg",                          // Files to be included in the icon font
        cssPath :    "../icon-font/",                               // Included into sass as the path to the icon font
        sassInput :  "./assets/sass/setup/_icons-template.scss",    // The raw sass file before being compiled
        sassOutput : "../../sass/global/_icons.scss",               // The compiled Icon font sass file
    },
    "watch": {                                  // Folders to watch for changes
        "styles": [
            "assets/sass/**/*.scss",
            "assets/sass/*.scss",
            "!assets/sass/inline/",
            "!_icons-template.scss"
        ],
        "scripts": [
            "assets/js/*.js",
            "assets/js/vendor/**/*.js"
        ],
        "images": [
            "assets/images/*",
            "assets/images/**/*"
        ]
    },
    "dest":  {                                  // Output Folders
        "styles":     "./assets/dist/css",
        "scripts":    "./assets/dist/js",
        "images":     "./assets/dist/images",
        "fontFolder": "assets/dist/icon-font/",
        "fontFile":   "assets/dist/icon-font/*.svg",
    }
};

exports.CONFIG = CONFIG;
