<?php

/**
 * Plugin Name:     Posts By Date
 * Plugin URI:      https://bettercollective.com
 * Description:     It will be used to display a list of posts from certain categories with date and limit filter.
 * Author:          Nima Rahbar
 * Author URI:      https://nimarahbar.com
 * Text Domain:     posts-by-date
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Posts_By_Date
 */
class PostsByDate
{
    public function __construct()
    {
    }

    public function posts_by_date_enqueue_scripts()
    {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', false, '5.1.3', 'all');
        wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css', false, '1.7.0', 'all');
        wp_enqueue_style('posts-by-date', plugin_dir_url(__FILE__) . 'assets/css/posts-by-date.css', false, time(), 'all');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array('jquery'), '5.1.3', true);
        wp_enqueue_script('posts-by-date', plugin_dir_url(__FILE__) . 'assets/js/posts-by-date.js', array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-spinner'), time(), true);
    }

    
}
require_once plugin_dir_path(__FILE__) . 'includes' . DIRECTORY_SEPARATOR . 'admin-menu.php';
if (is_admin()) {
    $admin_menu = new AdminMenu();
}
$pbd = new PostsByDate();