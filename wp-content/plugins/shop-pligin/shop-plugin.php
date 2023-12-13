<?php
/*
Plugin Name: Shop Plugin
Description: A plugin to display products from API.
Version: 1.0
Author: Arkady Aronov
*/

register_activation_hook(__FILE__, 'shop_plugin_activate');

// Activation tasks
function shop_plugin_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'shop_settings';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        option_name VARCHAR(255) NOT NULL,
        option_value TEXT,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add admin menu item
function shop_plugin_menu() {
    add_menu_page(
        'Shop Settings',
        'Shop',
        'manage_options',
        'shop-admin',
        'shop_admin_page',
        'dashicons-cart', 
        1 
    );

    // Enqueue Bootstrap and custom styles
    add_action('admin_enqueue_scripts', 'shop_enqueue_admin_styles');
}
add_action('admin_menu', 'shop_plugin_menu');

// Enqueue admin styles
function shop_enqueue_admin_styles() {
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot2','https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ),'',true );
    wp_enqueue_style('shop-admin-style', plugins_url('assets/css/admin/shop-admin.css', __FILE__));
    wp_enqueue_script('shop-admin-script', plugins_url('assets/js/admin/shop-admin.js', __FILE__), array('jquery'), null, true);
}


// Admin page callback
function shop_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form is submitted
    if (isset($_POST['submit'])) {

        $products_endpoint  = sanitize_text_field($_POST['products_endpoint']);
        $products_per_page  = intval($_POST['products_per_page']);
        $orders_endpoint    = sanitize_text_field($_POST['orders_endpoint']);

        $category_prefix    = sanitize_text_field($_POST['category_prefix']);
        $category_postfix   = sanitize_text_field($_POST['category_postfix']);
        $product_prefix     = sanitize_text_field($_POST['product_prefix']);
        $product_postfix    = sanitize_text_field($_POST['product_postfix']);        

        update_option('shop_products_endpoint', $products_endpoint);
        update_option('shop_products_per_page', $products_per_page);
        update_option('shop_orders_endpoint', $orders_endpoint);

        update_option('shop_category_prefix', $category_prefix);
        update_option('shop_category_postfix', $category_postfix);
        update_option('shop_product_prefix', $product_prefix);
        update_option('shop_product_postfix', $product_postfix);


        

        // Display success message
       echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';

    }    

    // Load the admin page file from the "admin" folder
    require_once plugin_dir_path(__FILE__) . 'admin/shop-admin.php';
}
?>