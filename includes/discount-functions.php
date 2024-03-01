<?php
// Function to calculate and apply the discount
function apply_category_discount($original_price, $product) {
    if (!$product->is_in_stock()) {
        return $original_price; // Out of stock, no discount
    } 

    $product_categories = get_the_terms($product->get_id(), 'product_cat');

    foreach ($product_categories as $category) {
        $discount_data = get_category_discount_data($category->term_id);

        if ($discount_data) {
            if ($discount_data['type'] === 'percent') {
                $discount_amount = $original_price * ($discount_data['value'] / 100);
            } else if ($discount_data['type'] === 'fixed') {
                $discount_amount = $discount_data['value'];
            }

            $new_price = $original_price - $discount_amount; 
            return $new_price; // Apply the first applicable discount
        }
    }

    return $original_price; // No discount applicable
}
add_filter('woocommerce_product_get_price', 'apply_category_discount', 10, 2);  


// Function to retrieve discount data (with Type)
function get_category_discount_data($category_id) {
    $saved_settings = get_option('category_discount_pro_settings');

    if (isset($saved_settings['categories'][$category_id]) && 
        isset($saved_settings['discounts'][$category_id]) &&
        isset($saved_settings['types'][$category_id])) {

        return array(
            'type' => $saved_settings['types'][$category_id], 
            'value' => $saved_settings['discounts'][$category_id]
        );
    } else {
        return false; 
    }
}