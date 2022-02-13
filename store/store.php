<?php
function mps_store_locator_init()
{
    $labels = array(
        'name'                  => _x('Stores', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Store', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Stores', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Store', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Store', 'textdomain'),
        'new_item'              => __('New Store', 'textdomain'),
        'edit_item'             => __('Edit Store', 'textdomain'),
        'view_item'             => __('View Store', 'textdomain'),
        'all_items'             => __('All Stores', 'textdomain'),
        'search_items'          => __('Search Stores', 'textdomain'),
        'parent_item_colon'     => __('Parent Store:', 'textdomain'),
        'not_found'             => __('No stores found.', 'textdomain'),
        'not_found_in_trash'    => __('No stores found in Trash.', 'textdomain'),
        'featured_image'        => _x('Store Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
        'archives'              => _x('Store archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
        'insert_into_item'      => _x('Insert into hotel', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
        'uploaded_to_this_item' => _x('Uploaded to this hotel', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
        'filter_items_list'     => _x('Filter stores list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
        'items_list_navigation' => _x('Stores list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
        'items_list'            => _x('Stores list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'mps-store'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 104,
        'supports'           => array('title'),
    );
// Shop Name, Address, Post code, city, country, Phone, website
    register_post_type('mps-store', $args);
    include 'address.php';
    include 'pincode.php';
    include 'city.php';
    include 'country.php';
    include 'phone.php';
    include 'website.php';
    include 'latlong.php';
}

add_action('init', 'mps_store_locator_init');
add_filter('manage_mps-store_posts_columns','filter_cpt_columns');

function filter_cpt_columns( $columns ) {
    // this will add the column to the end of the array
    $columns['address'] = 'Address';
    $columns['city'] = 'City';
    $columns['country'] = 'Country';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}
add_action( 'manage_posts_custom_column','action_custom_columns_content', 10, 2 );
function action_custom_columns_content ( $column_id, $post_id ) {
    //run a switch statement for all of the custom columns created
    switch( $column_id ) { 
        case 'address':
            echo ($value = get_post_meta($post_id, 'mps_stores_address', true ) ) ? $value : 'NA';
            break;
        case 'city':
            echo ($value = get_post_meta($post_id, 'mps_stores_city', true ) ) ? $value : 'NA';
            break;
        case 'country':
            echo ($value = get_post_meta($post_id, 'mps_stores_country', true ) ) ? $value : 'NA';
            break;

        //add more items here as needed, just make sure to use the column_id in the filter for each new item.

   }
}