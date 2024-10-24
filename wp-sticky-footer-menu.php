<?php
/**
 * Plugin Name: MN - WordPress Sticky Footer Menu
 * Plugin URI: https://github.com/mnestorov/wp-sticky-footer-menu
 * Description: Adds a customizable sticky footer menu to selected pages.
 * Version: 1.7
 * Author: Martin Nestorov
 * Author URI: https://github.com/mnestorov
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mn-wordpress-sticky-footer-menu
 * Tags: wp, wp-plugin, wp-admin, wordpress, wordpress-plugin, wordpress-menu, wordpress-multisite
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'mn_activate');
register_deactivation_hook(__FILE__, 'mn_deactivate');

// Function to run upon plugin activation to initialize options
function mn_activate() {
    add_option('mn_menu_items', '');  // Initialize menu items option
    add_option('mn_visible_pages', '');  // Initialize visible pages option
    add_option('mn_menu_icons', '');  // Initialize menu icons option
}

// Function to run upon plugin deactivation to clean up options
function mn_deactivate() {
    delete_option('mn_menu_items');
    delete_option('mn_visible_pages');
    delete_option('mn_menu_icons');
}

// Function to load text domain
function mn_load_textdomain() {
    load_plugin_textdomain('mn-wordpress-sticky-footer-menu', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'mn_load_textdomain');

// Function to create settings page
function mn_create_settings_page() {
    add_options_page(
        'Sticky Footer Menu Settings',
        'Sticky Footer Menu',
        'manage_options',
        'sticky-footer-menu',
        'mn_render_settings_page'
    );
}
add_action('admin_menu', 'mn_create_settings_page');

// Function to render settings page
function mn_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Sticky Footer Menu Settings', 'mn-wordpress-sticky-footer-menu'); ?></h1>
        <!-- Form with enctype attribute to allow file uploads -->
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('mn-settings-group');
            do_settings_sections('mn-settings-group');
            ?>
            <table class="form-table">
                <!-- Field for entering menu items -->
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Menu Items (Label,URL; separated)', 'mn-wordpress-sticky-footer-menu'); ?></th>
                    <td><input type="text" name="mn_menu_items" value="<?php echo esc_attr(get_option('mn_menu_items')); ?>" /></td>
                </tr>
                <!-- File upload field for menu icons -->
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Upload Icons (Ensure the order of icons matches the order of menu items)'); ?></th>
                    <td><input type="file" name="mn_menu_icons[]" multiple="multiple" /></td>
                </tr>
                <!-- Other settings fields for customizing appearance -->
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Visible Pages (Page IDs, comma separated)'); ?></th>
                    <td><input type="text" name="mn_visible_pages" value="<?php echo esc_attr(get_option('mn_visible_pages')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Background Color'); ?></th>
                    <td><input type="text" name="mn_bg_color" value="<?php echo esc_attr(get_option('mn_bg_color', '#333')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Text Color'); ?></th>
                    <td><input type="text" name="mn_text_color" value="<?php echo esc_attr(get_option('mn_text_color', '#fff')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Font Size'); ?></th>
                    <td><input type="text" name="mn_font_size" value="<?php echo esc_attr(get_option('mn_font_size', '16px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Border Size'); ?></th>
                    <td><input type="text" name="mn_border_size" value="<?php echo esc_attr(get_option('mn_border_size', '1px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Border Color'); ?></th>
                    <td><input type="text" name="mn_border_color" value="<?php echo esc_attr(get_option('mn_border_color', '#000')); ?>" /></td>
                </tr>
                <!-- Field for entering custom CSS -->
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Custom CSS', 'mn-wordpress-sticky-footer-menu'); ?></th>
                    <td><textarea name="mn_custom_css" rows="10" cols="50"><?php echo esc_textarea(get_option('mn_custom_css')); ?></textarea></td>
                </tr>
                <!-- Field for entering custom JavaScript -->
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Custom JavaScript', 'mn-wordpress-sticky-footer-menu'); ?></th>
                    <td><textarea name="mn_custom_js" rows="10" cols="50"><?php echo esc_textarea(get_option('mn_custom_js')); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Function to register Settings and handle file uploads
function mn_register_settings() {
    register_setting('mn-settings-group', 'mn_menu_items');
    register_setting('mn-settings-group', 'mn_visible_pages');
    register_setting('mn-settings-group', 'mn_bg_color');
    register_setting('mn-settings-group', 'mn_text_color');
    register_setting('mn-settings-group', 'mn_font_size');
    register_setting('mn-settings-group', 'mn_border_size');
    register_setting('mn-settings-group', 'mn_border_color');
    register_setting('mn-settings-group', 'mn_custom_css');
    register_setting('mn-settings-group', 'mn_custom_js');

    // Handling file upload for menu icons
    if (!empty($_FILES['mn_menu_icons']['name'][0])) {
        $icons = [];
        $files = $_FILES['mn_menu_icons'];
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = [
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key]
                ];
                $overrides = ['test_form' => false];
                $upload = wp_handle_upload($file, $overrides);
                if (!isset($upload['error'])) {
                    $icons[] = $upload['url'];
                }
            }
        }
        update_option('mn_menu_icons', implode(';', $icons));
    }
}
add_action('admin_init', 'mn_register_settings');

// Function to render the footer menu
function mn_render_footer_menu() {
    if (mn_should_display_menu()) {
        $bg_color = get_option('mn_bg_color', '#333');
        $text_color = get_option('mn_text_color', '#fff');
        $font_size = get_option('mn_font_size', '16px');
        $border_size = get_option('mn_border_size', '1px');  // Default border size to 1px
        $border_color = get_option('mn_border_color', '#000');  // Default border color to black
        $menu_items = explode(';', get_option('mn_menu_items'));
        $menu_icons = explode(';', get_option('mn_menu_icons'));  // Get menu icons from database

        echo '<div id="mn-footer-menu" style="background-color:' . esc_attr($bg_color) . ';color:' . esc_attr($text_color) . ';font-size:' . esc_attr($font_size) . ';border-top:' . esc_attr($border_size) . ' solid ' . esc_attr($border_color) . ';"><ul>';
        foreach ($menu_items as $index => $item) {
            list($label, $url) = explode(',', $item);
            $icon_url = isset($menu_icons[$index]) ? $menu_icons[$index] : '';
            echo '<li><a href="' . esc_url($url) . '" style="color:' . esc_attr($text_color) . ';font-size:' . esc_attr($font_size) . ';">';
            if ($icon_url) {
                echo '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr(sprintf(__('Icon for %s', 'mn-wordpress-sticky-footer-menu'), $label)) . '" /> ';
            }
            echo '<span>' . esc_html($label) . '</span>';
            echo '</a></li>';
        }
        echo '</ul></div>';
    }
}
add_action('wp_footer', 'mn_render_footer_menu');

// Function to determine if the footer menu should be displayed on the current page
function mn_should_display_menu() {
    $visible_pages = explode(',', get_option('mn_visible_pages'));
    return in_array(get_the_ID(), $visible_pages);
}

// Function to enqueue styles and output custom CSS
function mn_enqueue_styles() {
    wp_enqueue_style('mn-styles', plugins_url('css/styles.css', __FILE__));
    
    $custom_css = get_option('mn_custom_css');
    if ($custom_css) {
        wp_add_inline_style('mn-styles', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'mn_enqueue_styles');

// Function to enqueue Scripts
function mn_enqueue_scripts() {
    wp_enqueue_script('mn-scripts', plugins_url('js/script.js', __FILE__), array(), null, true);
}
add_action('wp_enqueue_scripts', 'mn_enqueue_scripts');
