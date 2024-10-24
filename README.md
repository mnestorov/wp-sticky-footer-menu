<p align="center"><a href="https://wordpress.org" target="_blank"><img src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/wordpress/wordpress.png" width="100" alt="WordPress Logo"></a></p>

# WordPress - Sticky Footer Menu

[![Licence](https://img.shields.io/badge/LICENSE-GPL2.0+-blue)](./LICENSE)

- Developed by: [Martin Nestorov](https://github.com/mnestorov)
- Plugin URI: https://github.com/mnestorov/wp-sticky-footer-menu

## Overview

Adds a customizable sticky footer menu to selected pages. This plugin allows users to create a sticky footer menu and manage its visibility across different pages. Additionally, it enables customization of the menu's appearance and the ability to upload icons for each menu item.

## Features

- Create a sticky footer menu with custom menu items.
- Manage visibility of the footer menu on different pages.
- Customize the appearance of the footer menu, including background color, text color, and font size.
- Upload custom icons for each menu item.
- Provide fields in the settings page for users to add custom CSS and JavaScript to further customize the behavior and appearance of the footer menu.
- Translation-ready for different locales.

## Installation

1. Download the plugin zip file.
2. Navigate to the WordPress admin dashboard.
3. Go to Plugins > Add New.
4. Click on Upload Plugin and browse to select the plugin zip file.
5. Click on Install Now and then Activate the plugin.

## Usage

1. Navigate to the WordPress admin dashboard.
2. Go to Settings > Sticky Footer Menu to configure the plugin.
3. Add menu items, upload icons, set visibility on pages, and customize the appearance as needed.
4. View the sticky footer menu on the front-end on the specified pages.

## Functions

- `mn_activate()`: Initializes plugin options upon activation.
- `mn_deactivate()`: Cleans up plugin options upon deactivation.
- `mn_create_settings_page()`: Creates the settings page in the WordPress admin.
- `mn_render_settings_page()`: Renders the settings page HTML.
- `mn_register_settings()`: Registers settings and handles file uploads for menu icons.
- `mn_render_footer_menu()`: Renders the footer menu on the front-end.
- `mn_should_display_menu()`: Determines if the footer menu should be displayed on the current page.
- `mn_enqueue_styles()`: Enqueues plugin styles.
- `mn_enqueue_scripts()`: Enqueues plugin scripts.
- `mn_load_textdomain()`: Loads the text domain for translation.

## Error Handling and Debugging

Ensure that file uploads are enabled and the file types for icons are allowed on your WordPress installation. Check the browser console and server error logs for any issues.

## Uninstallation

1. Navigate to the WordPress admin dashboard.
2. Go to Plugins.
3. Locate the Sticky Footer Menu plugin.
4. Click on Deactivate and then Delete to uninstall the plugin.

## FAQs

- How do I add icons to menu items?
   - In the Sticky Footer Menu settings, use the Upload Icons field to upload your icons. Ensure the order of icons matches the order of menu items.

- How do I control which pages the menu appears on?
   - In the Sticky Footer Menu settings, specify the Page IDs in the Visible Pages field, separated by commas.

## Changelog

For a detailed list of changes and updates made to this project, please refer to our [Changelog](./CHANGELOG.md).

---

## License

This project is released under the [GPL-2.0+ License](http://www.gnu.org/licenses/gpl-2.0.txt).
