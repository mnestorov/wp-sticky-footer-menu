<?php
/**
 * Plugin Name: MN - WordPress Sticky Footer Menu
 * Plugin URI: https://github.com/mnestorov/wp-sticky-footer-menu
 * Description: Adds a customizable sticky footer menu to selected pages.
 * Version: 1.0
 * Author: Martin Nestorov
 * Author URI: https://github.com/mnestorov
 * Text Domain: mn-wordpress-sticky-footer-menu
 * Tags: wp, wp-plugin, wp-admin, wordpress, wordpress-plugin, wordpress-menu, wordpress-multisite
 */

// Activation and Deactivation Hooks
register_activation_hook(__FILE__, 'mn_activate');
register_deactivation_hook(__FILE__, 'mn_deactivate');

function mn_activate() {
    add_option('mn_menu_items', '');
    add_option('mn_visible_pages', '');
}

function mn_deactivate() {
    delete_option('mn_menu_items');
    delete_option('mn_visible_pages');
}

// Create Settings Page
add_action('admin_menu', 'mn_create_settings_page');

function mn_create_settings_page() {
    add_options_page(
        'Sticky Footer Menu Settings',
        'Sticky Footer Menu',
        'manage_options',
        'sticky-footer-menu',
        'mn_render_settings_page'
    );
}

function mn_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Sticky Footer Menu Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('mn-settings-group');
            do_settings_sections('mn-settings-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Menu Items (Label,URL; separated)</th>
                    <td><input type="text" name="mn_menu_items" value="<?php echo esc_attr(get_option('mn_menu_items')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Visible Pages (Page IDs, comma separated)</th>
                    <td><input type="text" name="mn_visible_pages" value="<?php echo esc_attr(get_option('mn_visible_pages')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register Settings
add_action('admin_init', 'mn_register_settings');

function mn_register_settings() {
    register_setting('mn-settings-group', 'mn_menu_items');
    register_setting('mn-settings-group', 'mn_visible_pages');
}

// Render Footer Menu
add_action('wp_footer', 'mn_render_footer_menu');

function mn_render_footer_menu() {
    if (mn_should_display_menu()) {
        $menu_items = explode(';', get_option('mn_menu_items'));
        echo '<div id="mn-footer-menu"><ul>';
        foreach ($menu_items as $item) {
            list($label, $url) = explode(',', $item);
            echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
        }
        echo '</ul></div>';
    }
}

function mn_should_display_menu() {
    $visible_pages = explode(',', get_option('mn_visible_pages'));
    return in_array(get_the_ID(), $visible_pages);
}

// Enqueue Styles
add_action('wp_enqueue_scripts', 'mn_enqueue_styles');

function mn_enqueue_styles() {
    wp_enqueue_style('mn-styles', plugins_url('styles.css', __FILE__));
}
