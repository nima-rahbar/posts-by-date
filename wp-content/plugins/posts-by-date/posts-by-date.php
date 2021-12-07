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
    public $plugin_dir = WP_PLUGIN_DIR.'/posts-by-date';
    public function __construct()
    {
        add_action('admin_menu', array($this, 'posts_by_date_add_plugin_page'));
        add_action('admin_init', array($this, 'posts_by_date_page_init'));
        add_action('init', array($this, 'posts_by_date_enqueue_scripts'));
    }

    public function posts_by_date_enqueue_scripts()
    {
        if (is_admin()) {
            wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',false, '5.1.3', 'all');
            wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css',false, '1.7.0', 'all');
            wp_enqueue_style('posts-by-date', plugin_dir_url( __FILE__ ) .'assets/css/posts-by-date.css',false, time(), 'all');
            wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array('jquery'), '5.1.3', true);
            wp_enqueue_script('posts-by-date', plugin_dir_url( __FILE__ ) .'assets/js/posts-by-date.js', array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-spinner'), time(), true);
        }
    }

    public function posts_by_date_add_plugin_page()
    {
        add_posts_page(
            'Posts By Date',
            'Posts By Date',
            'manage_options',
            'posts-by-date', 
            array($this, 'posts_by_date_create_admin_page')
        );
    }

    public function posts_by_date_create_admin_page()
    {
        $this->posts_by_date_options = get_option('posts_by_date_option_name'); ?>
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-12 mb-3">
                    <h2><?= __('Posts By Date', 'posts-by-date') ?></h2>
                    <?php settings_errors(); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('posts_by_date_option_group');
                        do_settings_sections('posts-by-date-admin');
                        echo '<div class="d-grid gap-2 d-md-flex justify-content-md-end">';
                        submit_button( __( 'Save Settings', 'posts-by-date' ), 'btn btn-primary', 'posts-by-date-save-settings' );
                        echo '</div>';
                        ?>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card bg-dark text-light w-100 mt-5 p-0" style="max-width: 100%;">
                        <h4 class="card-header h2 p-3"><?= __('Posts Settings', 'posts-by-date') ?></h4>
                        <div class="card-body p-5">
                            <h5 class="card-title h4 mb-4"><?= __('You will see your posts with these settings', 'posts-by-date') ?>:</h5>
                            <table class="table table-borderless">
                                <tr class="card-text placeholder-glow">
                                    <th class="align-middle text-light w-50"><?= __('Category', 'posts-by-date') ?></th>
                                    <td id="category_0_value" class="align-middle py-3"><span class="placeholder col-12 bg-light"></span></td>
                                </tr>
                                <tr class="card-text placeholder-glow">
                                    <th class="align-middle text-light w-50"><?= __('Date', 'posts-by-date') ?></th>
                                    <td id="date_1_value" class="align-middle py-3"><span class="placeholder col-12 bg-light"></span></td>
                                </tr>
                                <tr class="card-text placeholder-glow">
                                    <th class="align-middle text-light w-50"><?= __('Limit', 'posts-by-date') ?></th>
                                    <td id="limit_2_value" class="align-middle py-3"><span class="placeholder col-12 bg-light"></span></td>
                                </tr>
                                <tr class="card-text placeholder-glow">
                                    <th class="align-middle text-light w-50"><?= __('Order Element', 'posts-by-date') ?></th>
                                    <td id="orderby_3_value" class="align-middle py-3"><span class="placeholder col-12 bg-light"></span></td>
                                </tr>
                                <tr class="card-text placeholder-glow">
                                    <th class="align-middle text-light w-50"><?= __('Sort Order', 'posts-by-date') ?></th>
                                    <td id="order_4_value" class="align-middle py-3"><span class="placeholder col-12 bg-light"></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php }

    public function posts_by_date_page_init()
    {
        register_setting(
            'posts_by_date_option_group',
            'posts_by_date_option_name',
            array($this, 'posts_by_date_sanitize')
        );

        add_settings_section(
            'posts_by_date_setting_section',
            '<span class="d-none">'.__('Settings', 'posts-by-date').'</span>',
            array($this, 'posts_by_date_section_info'),
            'posts-by-date-admin'
        );

        add_settings_field(
            'category_0',
            __('Category', 'posts-by-date'),
            array($this, 'category_0_callback'),
            'posts-by-date-admin',
            'posts_by_date_setting_section',
            array(
                'label_for' => 'category_0',
                'class' => 'form-label'
            ),
        );

        add_settings_field(
            'date_1',
            __('Date', 'posts-by-date'),
            array($this, 'date_1_callback'),
            'posts-by-date-admin',
            'posts_by_date_setting_section',
            array(
                'label_for' => 'date_1',
                'class' => 'form-label'
            ),
        );

        add_settings_field(
            'limit_2',
            __('Limit', 'posts-by-date'),
            array($this, 'limit_2_callback'),
            'posts-by-date-admin',
            'posts_by_date_setting_section',
            array(
                'label_for' => 'limit_2',
                'class' => 'form-label'
            ),
        );
        add_settings_field(
            'orderby_3',
            __('Order By', 'posts-by-date'),
            array($this, 'orderby_3_callback'),
            'posts-by-date-admin',
            'posts_by_date_setting_section',
            array(
                'label_for' => 'orderby_3',
                'class' => 'form-label'
            ),
        );
        add_settings_field(
            'order_4',
            __('Order', 'posts-by-date'),
            array($this, 'order_4_callback'),
            'posts-by-date-admin',
            'posts_by_date_setting_section',
            array(
                'label_for' => 'order_4',
                'class' => 'form-label'
            ),
        );
    }

    public function posts_by_date_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['category_0'])) {
            $sanitary_values['category_0'] = $input['category_0'];
        }

        if (isset($input['date_1'])) {
            $sanitary_values['date_1'] = sanitize_text_field($input['date_1']);
        }

        if (isset($input['limit_2'])) {
            $sanitary_values['limit_2'] = sanitize_text_field($input['limit_2']);
        }
        
        if (isset($input['orderby_3'])) {
            $sanitary_values['orderby_3'] = sanitize_text_field($input['orderby_3']);
        }
        
        if (isset($input['order_4'])) {
            $sanitary_values['order_4'] = sanitize_text_field($input['order_4']);
        }

        return $sanitary_values;
    }

    public function posts_by_date_section_info()
    {
        echo  '<h4 class="text-muted mb-4">'.__('Choose Your shortcode settings here', 'posts-by-date').':</h4>';
    }

    public function category_0_callback()
    {
        $categories = get_categories();
    ?>
    <div class="mb-2">
        <select name="posts_by_date_option_name[category_0]" id="category_0" class="form-select" aria-label="Category Selector">
            <option value=""></option>
            <?php foreach ($categories as $category) : ?>
                <?php $selected = (isset($this->posts_by_date_options['category_0']) && $this->posts_by_date_options['category_0'] === $category->slug) ? 'selected' : ''; ?>
                <option value="<?= $category->slug ?>" <?php echo $selected; ?>><?= $category->name ?></option>
                <?php endforeach; ?>
            </select>
            <p class="small"><?= __('Posts Specific category.', 'posts-by-date') ?></p>
    </div>
    <?php
    }

    public function date_1_callback()
    {
        echo '<div class="mb-3">';
        printf(
            '<input class="form-control" type="date" name="posts_by_date_option_name[date_1]" id="date_1" value="%s">',
            isset($this->posts_by_date_options['date_1']) ? esc_attr($this->posts_by_date_options['date_1']) : ''
        );
        echo '<p class="small">'. __('Posts Specific Date.', 'posts-by-date').'</p>';
        echo '</div>';
    }

    public function limit_2_callback()
    {
        echo '<div class="mb-3">';
        printf(
            '<input class="form-control" type="number" name="posts_by_date_option_name[limit_2]" id="limit_2" value="%s" min="-1">',
            isset($this->posts_by_date_options['limit_2']) ? esc_attr($this->posts_by_date_options['limit_2']) : ''
        );
        echo '<p class="small">'.__('Post\'s Counts (Choose "-1" for unlimited posts).', 'posts-by-date').'</p>';
        echo '</div>';
    }
    public function orderby_3_callback()
    {
    ?>
    <div class="mb-2">
        <select name="posts_by_date_option_name[orderby_3]" id="orderby_3" class="form-select" aria-label="Order By Selector">
            <option value=""></option>
            <?php $selected = (isset($this->posts_by_date_options['orderby_3']) && $this->posts_by_date_options['orderby_3'] === 'none') ? 'selected' : ''; ?>
            <option value="none" <?php echo $selected; ?>><?= __('No Ordering', 'posts-by-date') ?></option>
            <?php $selected = (isset($this->posts_by_date_options['orderby_3']) && $this->posts_by_date_options['orderby_3'] === 'title') ? 'selected' : ''; ?>
            <option value="title" <?php echo $selected; ?>><?= __('Order By Post Title', 'posts-by-date') ?></option>
            <?php $selected = (isset($this->posts_by_date_options['orderby_3']) && $this->posts_by_date_options['orderby_3'] === 'date') ? 'selected' : ''; ?>
            <option value="date" <?php echo $selected; ?>><?= __('Order By Post Date', 'posts-by-date') ?></option>
            <?php $selected = (isset($this->posts_by_date_options['orderby_3']) && $this->posts_by_date_options['orderby_3'] === 'rand') ? 'selected' : ''; ?>
            <option value="rand" <?php echo $selected; ?>><?= __('Random Order', 'posts-by-date') ?></option>
        </select>
        <p class="small"><?= __('Posts Order Pattern.', 'posts-by-date') ?></p>
    </div>
        <?php
    }
    public function order_4_callback()
    {
    ?>
    <div class="mb-2">
        <select name="posts_by_date_option_name[order_4]" id="order_4" class="form-select" aria-label="Order Sorting Selector">
            <option value=""></option>
            <?php $selected = (isset($this->posts_by_date_options['order_4']) && $this->posts_by_date_options['order_4'] === 'asc') ? 'selected' : ''; ?>
            <option value="asc" <?php echo $selected; ?>><?= __('Ascending', 'posts-by-date') ?></option>
            <?php $selected = (isset($this->posts_by_date_options['order_4']) && $this->posts_by_date_options['order_4'] === 'desc') ? 'selected' : ''; ?>
            <option value="desc" <?php echo $selected; ?>><?= __('Descending', 'posts-by-date') ?></option>
        </select>
        <p class="small"><?= __('Posts Sorting Pattern.', 'posts-by-date') ?></p>
    </div>
        <?php
    }
}
$pbd = new PostsByDate();
