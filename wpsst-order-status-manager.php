<?php
/*
Plugin Name:    WPSST Order Status Manager
Plugin URI:     https://www.syriasmart.net
Description:    A plugin to manage WooCommerce order statuses.
Version:        1.0
Author:         Syria Smart Technology 
Author URI:     https://www.syriasmart.net
License:        GPL v2 or later
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:    WPSST-Order-Status-Manager
Domain Path:    /languages
*/

// Add a new submenu item under the WooCommerce menu
add_action('admin_menu', 'wosm_add_menu_item');
function wosm_add_menu_item()
{
    add_submenu_page(
        'woocommerce',
        'Order Status Manager',
        'Order Status Manager',
        'manage_options',
        'wosm',
        'wosm_render_page'
    );
}

// Render the page
function wosm_render_page()
{
    ?>
    <div class="wrap">
        <h2>Order Status Manager</h2>
        <<table class="widefat">
            <thead>
                <tr>
                    <th>Status Name</th>
                    <th>Status Color</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $statuses = wc_get_order_statuses();
                foreach ($statuses as $status => $label) {
                    $color = get_option("wosm_status_color_$status");
                    ?>
                    <tr>
                        <td>
                            <?php echo $label; ?>
                        </td>
                        <td>
                            <input type="text" class="wosm-color-picker" name="wosm_status_color_<?php echo $status; ?>"
                                value="<?php echo $color; ?>" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            </table>
    </div>
    <?php
}

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', 'wosm_enqueue_scripts');
function wosm_enqueue_scripts()
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('wosm-admin', plugins_url('js/admin.js', __FILE__), array('jquery', 'wp-color-picker'), '1.0', true);
}

// Save status colors
add_action('woocommerce_order_status_changed', 'wosm_save_status_color', 10, 3);
function wosm_save_status_color($order_id, $status_from, $status_to)
{
    update_post_meta($order_id, 'wosm_status_color', get_option("wosm_status_color_$status_to"));
}