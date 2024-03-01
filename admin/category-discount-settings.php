<?php 
// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Register Settings 
add_action('admin_init', 'category_discount_pro_register_settings');
function category_discount_pro_register_settings() {
    register_setting( 'category-discount-pro-settings-group', 'category_discount_pro_settings' ); 
    add_settings_section( 'category_discount_pro_section', '', null, 'category-discount-pro');  // Add a settings section (optional)
}

// Settings Page Form
function category_discount_pro_settings_form() {
    ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'category-discount-pro-settings-group' ); ?>
        <?php do_settings_sections('category-discount-pro'); ?> 

        <h2>Category Discount Settings</h2>
        <?php display_categories_hierarchically(); ?> 
        <?php submit_button(); ?>
    </form>
    <?php
} 

// Function to display categories
function display_categories_hierarchically($parent_id = 0) {
    $args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $parent_id
    );
    $categories = get_categories($args);

    if ($categories) {
        echo '<ul>';
        foreach ($categories as $category) {
            echo '<li>';
            echo '<input type="checkbox" name="category_discount_pro_settings[categories][' . $category->term_id . ']" />'; 
            echo $category->name; 

            echo '<input type="text" name="category_discount_pro_settings[discounts][' . $category->term_id . ']" placeholder="Discount" />';

            // Add a select field for discount type
            echo '<select name="category_discount_pro_settings[types][' . $category->term_id . ']">
                    <option value="percent">Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                 </select>'; 

            display_categories_hierarchically($category->term_id); // Recursion 
            echo '</li>';
        }
        echo '</ul>';
    }
} 

// Handle settings saving 
function save_category_discount_pro_settings() {
    if (isset($_POST['category_discount_pro_settings'])) {
        $new_settings = $_POST['category_discount_pro_settings']; 

        // Basic sanitization 
        if (isset($new_settings['discounts'])) {
            foreach ($new_settings['discounts'] as $key => $value) {
                $new_settings['discounts'][$key] = sanitize_text_field($value); 
            }
        } 
        if (isset($new_settings['types'])) {
            foreach ($new_settings['types'] as $key => $value) {
                $new_settings['types'][$key] = sanitize_text_field($value); // Sanitize type
            }
        } 

        update_option('category_discount_pro_settings', $new_settings);
    }
} 
add_action('admin_init', 'save_category_discount_pro_settings'); 
