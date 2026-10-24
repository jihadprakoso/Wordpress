<?php
/**
 * Functions and definitions for Jihad WP Theme
 */

// Enqueue styles and scripts
function jihadwp_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800&display=swap', [], null);
    // Bootstrap 5 CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', [], '5.3.0');
    // FontAwesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    // Main Theme Stylesheet
    wp_enqueue_style('theme-style', get_stylesheet_uri(), ['bootstrap'], '1.0.0');
    // Bootstrap 5 JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [], '5.3.0', true);
}
add_action('wp_enqueue_scripts', 'jihadwp_enqueue_assets');

// Theme supports
function jihadwp_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

    // Register Navigation Menus
    register_nav_menus([
        'primary' => __('Primary Navigation', 'jihad-wp-theme'),
        'footer'  => __('Footer Navigation', 'jihad-wp-theme'),
    ]);
}
add_action('after_setup_theme', 'jihadwp_setup');

// Register Sidebar Widget Area
function jihadwp_sidebars() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'jihad-wp-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets in this area will be shown on the sidebar.', 'jihad-wp-theme'),
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5>',
    ]);
}
add_action('widgets_init', 'jihadwp_sidebars');
