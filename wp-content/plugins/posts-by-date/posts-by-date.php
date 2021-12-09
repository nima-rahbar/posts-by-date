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
        if (!is_admin()) {
            add_action('init', array($this, 'posts_by_date_enqueue_scripts'));
            add_shortcode('posts_by_date', array($this, 'posts_by_date_shortcode'));
        }
        add_action('wp_ajax_load_more', array($this, 'load_more'));
        add_action('wp_ajax_nopriv_load_more', array($this, 'load_more'));
    }

    public function posts_by_date_enqueue_scripts_admin()
    {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', false, '5.1.3', 'all');
        wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css', false, '1.7.0', 'all');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array('jquery'), '5.1.3', true);
        wp_enqueue_script('posts-by-date', plugin_dir_url(__FILE__) . 'assets/js/posts-by-date-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-spinner'), time(), true);
    }
    public function posts_by_date_enqueue_scripts()
    {
        wp_enqueue_style('posts-by-date', plugin_dir_url(__FILE__) . 'assets/css/posts-by-date.css', false, time(), 'all');
        wp_register_script('posts-by-date', plugin_dir_url(__FILE__) . 'assets/js/posts-by-date.js', array('jquery'), time(), true);
        wp_localize_script(
            'posts-by-date',
            'myAjax',
            array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
        wp_enqueue_script('posts-by-date');
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
        if ($posts->have_posts()) {
            $o = '<ol id="posts-by-date">';
            while ($posts->have_posts()) {
                $posts->the_post();
                $o .= '<li><ul>';
                $o .= "<li><b>" . get_the_title() . "</b> - <small><i>" . get_the_date("Y-m-d") . "</i></small></li>";
                $o .= "<li><small>" . wp_trim_words(get_the_excerpt(), 30) . "</small></li>";
                $o .= '</ul></li>';
            }
            wp_reset_postdata();
            if ($posts->found_posts > $limit) {
                $o .= "<button id=\"load-more\" data-category=\"$category\" data-limit=\"$limit\" data-date=\"$date\" data-orderby=\"$orderby\" data-order=\"$order\" data-paged=\"1\">Load More!</button>";
            }
            $o .= '</ol>';
        } else {
            return 'There is no post with your settings in our Database.';
        }
        return $o;
    }

    public function load_more()
    {
        $params = $_POST['args'];

        $date_query = (!empty($params['date'])) ? array(
            'year' => date("Y", strtotime($params['date'])),
            'month' => date("m", strtotime($params['date'])),
            'day' => date("d", strtotime($params['date']))
        ) : array();

        $args = array(
            'post_type' => 'post',
            'category_name' => $params['category'],
            'post_status' => 'publish',
            'posts_per_page' => $params['limit'],
            'paged' => ($params['current'] + 1),
            'orderby' => $params['orderby'],
            'order' => $params['order'],
            'date_query' => $date_query,
        );
        $posts = new WP_Query($args);
        if ($posts->have_posts() && ($posts->found_posts > ($params['current']) * $params['limit'])) {
            $o = '';
            while ($posts->have_posts()) {
                $posts->the_post();
                $o .= '<li><ul>';
                $o .= "<li><b>" . get_the_title() . "</b> - <small><i>" . get_the_date("Y-m-d") . "</i></small></li>";
                $o .= "<li><small>" . wp_trim_words(get_the_excerpt(), 30) . "</small></li>";
                $o .= '</ul></li>';
            }
            wp_reset_postdata();
            $response = array(
                'status'   => 'success',
                'output' => $o,
                'current' => $params['current'] + 1
            );
        } else {
            $response = array(
                'status'   => 'finished',
                'output' => '<div id="no-more-post"><b>There is no more posts to load.</b></div>',
            );
        }
        echo json_encode($response);
        wp_die();
    }

    public function debug_array($array, $is_for_admin_area = false)
    {
        if ($is_for_admin_area) {
            $css = 'text-align: left; margin-left:200px; position:absolute; z-index: 100;';
        } else {
            $css = "text-align: left;";
        }
        echo '<pre style="' . $css . '" >', print_r($array, true), '</pre>';
    }
}
require_once plugin_dir_path(__FILE__) . 'includes' . DIRECTORY_SEPARATOR . 'admin-menu.php';
$pbd = new PostsByDate();
