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
        if(!is_admin()){
            add_action('init', array($this, 'posts_by_date_enqueue_scripts'));
            add_shortcode('posts_by_date', array($this, 'posts_by_date_shortcode'));
        }
    }

    public function posts_by_date_enqueue_scripts_admin()
    {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', false, '5.1.3', 'all');
        wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css', false, '1.7.0', 'all');
        wp_enqueue_style('posts-by-date', plugin_dir_url(__FILE__) . 'assets/css/posts-by-date.css', false, time(), 'all');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array('jquery'), '5.1.3', true);
        wp_enqueue_script('posts-by-date', plugin_dir_url(__FILE__) . 'assets/js/posts-by-date-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-spinner'), time(), true);
    }
    public function posts_by_date_enqueue_scripts()
    {
        wp_enqueue_script('posts-by-date', plugin_dir_url(__FILE__) . 'assets/js/posts-by-date.js', array('jquery'), time(), true);
    }

    public function posts_by_date_shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'category' => '',
                'date' => '',
                'limit' => '',
            ),
            $atts,
            'posts_by_date'
        );
        $db_options = get_option('posts_by_date_option_name');

        // Variables to work with
        $category = ($atts['category']) ? $atts['category'] : $db_options['category_0'];
        $date = ($atts['date']) ? $atts['date'] : $db_options['date_1'];
        $limit = ($atts['limit']) ? $atts['limit'] : $db_options['limit_2'];
        $orderby = $db_options['orderby_3'];
        $order = $db_options['order_4'];

        if (empty($category)) {
            return 'You should select at least one category.';
        }
        $date_query = (!empty($date)) ? array(
            'year' => date("Y", strtotime($date)),
            'month' => date("m", strtotime($date)),
            'day' => date("d", strtotime($date))
            ) : array();

        $args = array(
            'post_type' => 'post',
            'category_name' => $category,
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'paged' => 1,
            'orderby' => $orderby,
            'order' => $order,
            'date_query' => $date_query,
        );
        $posts = new WP_Query($args);
        if($posts->have_posts()){
            $o = '<ol>';
            while($posts->have_posts()){
                $posts->the_post();
                $o .= '<li><ul>';
                $o .= "<li>".get_the_title()."</li>";
                $o .= "<li>".get_the_excerpt()."</li>";
                $o .= "<li>".get_the_date("Y-m-d")."</li>";
                $o .= '</ul></li>';
            }
            $o .= '</ol>';
            wp_reset_postdata();
            // if($posts->found_posts > $limit){
            //     $o .= '<button id="load-more" data-page="1">Load More!</button>';
            // }
        }else{
            return 'There is no post with your settings in our Database.';
        }
        return $o;
    }
}
require_once plugin_dir_path(__FILE__) . 'includes' . DIRECTORY_SEPARATOR . 'admin-menu.php';
$pbd = new PostsByDate();
