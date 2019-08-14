<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Dental_CPT')) :

    class Dental_CPT
    {
        public function __construct()
        {
            add_action('init', array($this, 'news'));
            add_action('init', array($this, 'treatments'));
            add_action('init', array($this, 'products'));
            add_action('admin_menu', array($this, 'remove_menus'));
        }

        public function news()
        {
            $labels = array(
                'name' => __('News'),
                'singular_name' => __('News'),
                'menu_name' => __('News'),
                'name_admin_bar' => __('News'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New'),
                'new_item' => __('New News'),
                'edit_item' => __('Edit News'),
                'view_item' => __('View News'),
                'all_items' => __('All News'),
                'search_items' => __('Search News'),
                'parent_item_colon' => __('Parent News:'),
                'not_found' => __('No News found.'),
                'not_found_in_trash' => __('No News found in Trash.')
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('with_front' => false, 'slug' => 'news'),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => true,
                'menu_position' => null,
                'menu_icon' => 'dashicons-buddicons-pm',
                'supports' => array('title', 'thumbnail', 'author')
            );

            register_post_type('news', $args);
            flush_rewrite_rules(false);
        }

        public function products()
        {
            $labels = array(
                'name' => __('Products'),
                'singular_name' => __('Product'),
                'menu_name' => __('Products'),
                'name_admin_bar' => __('Product'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New'),
                'new_item' => __('New Product'),
                'edit_item' => __('Edit Product'),
                'view_item' => __('View Product'),
                'all_items' => __('All Products'),
                'search_items' => __('Search Products'),
                'parent_item_colon' => __('Parent Product:'),
                'not_found' => __('No Product found.'),
                'not_found_in_trash' => __('No Product found in Trash.')
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('with_front' => false, 'slug' => 'product'),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => true,
                'menu_position' => null,
                'menu_icon' => 'dashicons-cart',
                'supports' => array('title', 'thumbnail', 'author')
            );

            register_post_type('product', $args);
            flush_rewrite_rules(false);
        }

        public function treatments()
        {
            $labels = array(
                'name' => __('Treatments'),
                'singular_name' => __('Treatment'),
                'menu_name' => __('Treatments'),
                'name_admin_bar' => __('Treatments'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New'),
                'new_item' => __('New Treatment'),
                'edit_item' => __('Edit Treatment'),
                'view_item' => __('View Treatment'),
                'all_items' => __('All Treatments'),
                'search_items' => __('Search Treatments'),
                'parent_item_colon' => __('Parent Treatment:'),
                'not_found' => __('No Treatments found.'),
                'not_found_in_trash' => __('No Treatments found in Trash.')
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('with_front' => false, 'slug' => 'treatment'),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => true,
                'menu_position' => null,
                'menu_icon' => 'dashicons-universal-access',
                'supports' => array('title', 'thumbnail', 'author')
            );

            $types = array(
                'name' => _x('Categories', 'taxonomy general name'),
                'singular_name' => _x('Category', 'taxonomy singular name'),
                'search_items' => __('Search Categories'),
                'all_items' => __('All Categories'),
                'parent_item' => __('Parent Category'),
                'parent_item_colon' => __('Category Type:'),
                'edit_item' => __('Edit Category'),
                'update_item' => __('Update Category'),
                'add_new_item' => __('Add New Category'),
                'new_item_name' => __('New Type Category'),
                'menu_name' => __('Categories'),
            );

            // register treatment post type
            register_post_type('treatment', $args);
            // register category taxonomy
            register_taxonomy('treatment_category', array('treatment'), array(
                'hierarchical' => true,
                'labels' => $types,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'treatment_category'),
            ));
            flush_rewrite_rules(false);
        }

        public function remove_menus()
        {
            remove_menu_page('edit.php'); //Posts
            remove_menu_page('edit.php?post_type=page'); //Pages
            remove_menu_page('edit-comments.php'); //Comments
            remove_menu_page('themes.php'); //Themes
        }
    }

endif; // End if class_exists check