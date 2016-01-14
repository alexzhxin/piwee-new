<?php

// Creating the widget
class most_shared_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
        // Base ID of your widget
            'most_shared_widget',

            // Widget name will appear in UI
            __('Piwee most shared widget', 'most_shared_widget'),

            // Widget description
            array('description' => __('Provide a widget to display the most shared post', 'most_shared_widget'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];


        $month = date('m.Y');

        $most_shared_post = query_posts(
            array(
                'meta_key' => 'total_share_count',
                'orderby' => 'total_share_count',
                'order' => 'DESC',
                'posts_per_page' => 1,
                'ignore_sticky_posts' => 1
            )
        );

        $most_shared_post_x_next = query_posts(
            array(
                'meta_key' => 'total_share_count',
                'orderby' => 'total_share_count',
                'order' => 'DESC',
                'posts_per_page' => 2,
                'offset' => 1,
                'ignore_sticky_posts' => 1
            )
        );

        $most_shared_post_of_the_month = query_posts(
            array(
                'meta_key' => 'share_count_month_diff_' . $month,
                'orderby' => 'share_count_month_diff_' . $month,
                'order' => 'DESC',
                'posts_per_page' => 1,
                'ignore_sticky_posts' => 1
            )
        );

        $most_shared_post_of_the_month_x_next = query_posts(
            array(
                'meta_key' => 'share_count_month_diff_' . $month,
                'orderby' => 'share_count_month_diff_' . $month,
                'order' => 'DESC',
                'posts_per_page' => 2,
                'offset' => 1,
                'ignore_sticky_posts' => 1
            )
        );

        // This is where you run the code and display the output

        ?>

        <h3 class="most-shared-widget-title">LES PLUS PARTAGES</h3>

        <p class="most-shared-widget-sub-title">La crême de la crême ! Ceux qui vous ont le plus plu</p>

        <div class="row most-shared-widget-featured">
            <div class="col-md-6">
                <h3 class="most-shared-widget-month-title">CE MOIS-CI</h3>

                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($most_shared_post[0]->ID), 'single-post-thumbnail'); ?>

                <div class="article-vignette-inside-image"
                     style="background-image: url('<?php echo $image[0]; ?>')">
                    <a href="<?php echo get_permalink($most_shared_post[0]->ID); ?>"
                       class="home-article-background-link">
                    </a>

                    <div class="sharing-interactive" id="sharing-interactive-<?php echo $most_shared_post[0]->ID; ?>"
                         onmouseover="openSharePanelForID('<?php echo $most_shared_post[0]->ID; ?>')"
                         onmouseout="hideSharePanelForID('<?php echo $most_shared_post[0]->ID; ?>');">
                        <?php if (function_exists("social_shares_button")) social_shares_button($most_shared_post[0]->ID); ?>
                        <div id="post-share-box-<?php echo $most_shared_post[0]->ID; ?>" class="post-share-article">
                            <div class="fb-like" data-href="<?php echo get_permalink($most_shared_post[0]->ID); ?>"
                                 data-layout="button" data-action="like" data-show-faces="false"
                                 data-share="false"></div>
                            <a href="http://twitter.com/share" class="twitter-share-button"
                               {count}>Tweet</a>
                        </div>
                    </div>
                </div>
                <a href="<?php echo get_permalink($most_shared_post[0]->ID); ?>">
                    <div class="article-vignette-inside-text">
                        <h4><?php echo $most_shared_post[0]->post_title; ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <h3 class="most-shared-widget-month-title">TOUS LES TEMPS</h3>

                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($most_shared_post_of_the_month[0]->ID), 'single-post-thumbnail'); ?>

                <div class="article-vignette-inside-image"
                     style="background-image: url('<?php echo $image[0]; ?>')">
                    <a href="<?php echo get_permalink($most_shared_post_of_the_month[0]->ID); ?>"
                       class="home-article-background-link">
                    </a>

                    <div class="sharing-interactive"
                         id="sharing-interactive-<?php echo $most_shared_post_of_the_month[0]->ID; ?>"
                         onmouseover="openSharePanelForID('<?php echo $most_shared_post_of_the_month[0]->ID; ?>')"
                         onmouseout="hideSharePanelForID('<?php echo $most_shared_post_of_the_month[0]->ID; ?>');">
                        <?php if (function_exists("social_shares_button")) social_shares_button($most_shared_post_of_the_month[0]->ID); ?>
                        <div id="post-share-box-<?php echo $most_shared_post_of_the_month[0]->ID; ?>"
                             class="post-share-article">
                            <div class="fb-like"
                                 data-href="<?php echo get_permalink($most_shared_post_of_the_month[0]->ID); ?>"
                                 data-layout="button" data-action="like" data-show-faces="false"
                                 data-share="false"></div>
                            <a href="http://twitter.com/share" class="twitter-share-button"
                               {count}>Tweet</a>
                        </div>
                    </div>
                </div>
                <a href="<?php echo get_permalink($most_shared_post_of_the_month[0]->ID); ?>">
                    <div class="article-vignette-inside-text">
                        <h4><?php echo $most_shared_post_of_the_month[0]->post_title; ?></h4>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php foreach ($most_shared_post_x_next as $post): ?>

                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                    <?php $share_count = get_total_share_count($post->ID) ?>
                    <?php $permalink = get_permalink($post->ID) ?>

                    <div class="row most-shared-widget-row-item">
                        <div class="col-md-4 most-shared-widget-col-item">
                            <a href="<?php echo $permalink ?>">
                                <img src="<?php echo $image[0] ?>" class="column-img">
                            </a>
                        </div>
                        <div class="col-md-8 most-shared-widget-col-item">
                            <a href="<?php echo $permalink ?>">
                                <span class="column-sharecount"><?php echo $share_count['total_share_count'] ?>
                                    Partages</span>
                                <br>
                                <span class="column-post-title"><?php echo $post->post_title ?></span>
                            </a>
                        </div>
                    </div>

                <?php endforeach ?>
            </div>
            <div class="col-md-6">
                <?php foreach ($most_shared_post_of_the_month_x_next as $post): ?>

                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                    <?php $share_count = get_total_share_count($post->ID) ?>
                    <?php $permalink = get_permalink($post->ID) ?>

                    <div class="row most-shared-widget-row-item">
                        <div class="col-md-4 most-shared-widget-col-item">
                            <a href="<?php echo $permalink ?>">
                                <img src="<?php echo $image[0] ?>" class="column-img">
                            </a>
                        </div>
                        <div class="col-md-8 most-shared-widget-col-item">
                            <a href="<?php echo $permalink ?>">
                                <span class="column-sharecount"><?php echo $share_count['total_share_count'] ?>
                                    Partages</span>
                                <br>
                                <span class="column-post-title"><?php echo $post->post_title ?></span>
                            </a>
                        </div>
                    </div>

                <?php endforeach ?>
            </div>
        </div>

        <?php

        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'campain_fb_widget');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

// Register and load the widget
function load_most_shared_widget()
{
    register_widget('most_shared_widget');
}

add_action('widgets_init', 'load_most_shared_widget');


?>