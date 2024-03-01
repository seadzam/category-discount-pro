<?php
/*
Plugin Name: Category Discount Pro
Description: Apply discounts to WooCommerce products based on categories and subcategories.
Author: EPIFRONT
Version: 1.0.0
*/

// Prevent direct access to the plugin files
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'includes/discount-functions.php');
include_once(plugin_dir_path(__FILE__) . 'admin/category-discount-settings.php');

// Register settings page with WordPress
add_action('admin_menu', 'category_discount_pro_settings_page');
function category_discount_pro_settings_page() {
    add_options_page( 
        'Category Discount Pro', // Page title
        'Category Discount Pro', // Menu title
        'manage_options',        // Required user capability
        'category-discount-pro', // Unique menu slug
        'category_discount_pro_settings_form' // Callback function for the page content
    );
}
