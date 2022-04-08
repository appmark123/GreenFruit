<?php
add_action('widgets_init', 'statistics_init');
function statistics_init() {
    register_widget('statistics');
}
class statistics extends WP_Widget {
    function statistics() {
        $widget_ops = array('classname' => 'widget-statistics','description' => __( 'Display Statistics of posts, comments, pages and so on.', 'cmp' ));
        $this->WP_Widget('statistics',theme_name .__( ' - Statistics', 'cmp' ), $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title'] );
        $start_date = $instance['start_date'];
        $last_date = $instance['last_date'];
        $count_days = $instance['count_days'];
        $count_users = $instance['count_users'];
        $count_posts = $instance['count_posts'];
        $count_comments = $instance['count_comments'];
        $count_pages = $instance['count_pages'];
        $count_categories = $instance['count_categories'];
        $count_tags = $instance['count_tags'];
        echo $before_widget;
        echo $before_title;
        echo $title ;
        echo $after_title;
?>
        <ul class="statistics clear">
            <?php
                if ($start_date) echo "<li><span>".sprintf( __( 'Start Date : %s ','cmp'), $start_date)."</span></li>";
                global $wpdb;
                $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");
                $last = date('Y-n-j', strtotime($last[0]->MAX_m));
                if ($last_date) echo "<li><span>".sprintf( __( 'Last Updated : %s ','cmp'), $last)."</span></li>";
                $online = floor((time()-strtotime($start_date))/86400);
                if ($count_days && $start_date) echo "<li><span>".sprintf( __( 'Online Days : %s ','cmp'), $online)."</span></li>";
                $users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
                if ($count_users) echo "<li><span>".sprintf( __( 'Users Number : %s ','cmp'), $users)."</span></li>";
                $posts_number = wp_count_posts();
                $published_posts = $posts_number->publish;
                if ($count_posts) echo "<li><span>".sprintf( __( 'Posts Number : %s ', 'cmp' ), $published_posts)."</span></li>";
                $comments_number = get_comment_count();
                if ($count_comments) echo "<li><span>".sprintf( __( 'Comments Number : %s ','cmp'), $comments_number['approved'])."</span></li>";
                $pages_number = wp_count_posts('page');
                $page_posts = $pages_number->publish;
                if ($count_pages) echo "<li><span>".sprintf( __( 'Pages Number : %s ','cmp'), $page_posts)."</span></li>";
                $categories_number = wp_count_terms('category');
                if ($count_categories) echo "<li><span>".sprintf( __( 'Categories Number : %s ','cmp'), $categories_number)."</span></li>";
                $tags_number = wp_count_terms('post_tag');
                if ($count_tags) echo "<li><span>".sprintf( __( 'Tags Number : %s ','cmp'), $tags_number)."</span></li>";
            ?>
        </ul>
<?php
      echo $after_widget;
   }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['start_date'] = strip_tags($new_instance['start_date']);
        $instance['last_date'] = strip_tags($new_instance['last_date']);
        $instance['count_days'] = strip_tags($new_instance['count_days']);
        $instance['count_users'] = strip_tags($new_instance['count_users']);
        $instance['count_posts'] = strip_tags($new_instance['count_posts']);
        $instance['count_comments'] = strip_tags($new_instance['count_comments']);
        $instance['count_pages'] = strip_tags($new_instance['count_pages']);
        $instance['count_categories'] = strip_tags($new_instance['count_categories']);
        $instance['count_tags'] = strip_tags($new_instance['count_tags']);
        return $instance;
    }
    function form($instance) {
        $defaults = array( 'start_date' => get_first_post_date() );
        $instance = wp_parse_args( (array) $instance, $defaults );
        global $wpdb;
?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(isset($instance['title']) && $instance['title']) echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'start_date' ); ?>"><?php _e('Start Date (e.g.2013-05-01) : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" value="<?php if( isset($instance['start_date']) &&  $instance['start_date']) echo $instance['start_date']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'last_date' ); ?>"><?php _e('Display Last Updated : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'last_date' ); ?>" name="<?php echo $this->get_field_name( 'last_date' ); ?>" value="true" <?php if( isset($instance['last_date']) && $instance['last_date']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_days' ); ?>"><?php _e('Display Online Days : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_days' ); ?>" name="<?php echo $this->get_field_name( 'count_days' ); ?>" value="true" <?php if( isset($instance['count_days']) && $instance['count_days']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_users' ); ?>"><?php _e('Display Users Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_users' ); ?>" name="<?php echo $this->get_field_name( 'count_users' ); ?>" value="true" <?php if( isset($instance['count_users']) && $instance['count_users']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_posts' ); ?>"><?php _e('Display Posts Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_posts' ); ?>" name="<?php echo $this->get_field_name( 'count_posts' ); ?>" value="true" <?php if( isset($instance['count_posts']) && $instance['count_posts']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_comments' ); ?>"><?php _e('Display Comments Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_comments' ); ?>" name="<?php echo $this->get_field_name( 'count_comments' ); ?>" value="true" <?php if( isset($instance['count_comments']) && $instance['count_comments']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_pages' ); ?>"><?php _e('Display Pages Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_pages' ); ?>" name="<?php echo $this->get_field_name( 'count_pages' ); ?>" value="true" <?php if( isset($instance['count_pages']) && $instance['count_pages']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_categories' ); ?>"><?php _e('Display Categories Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_categories' ); ?>" name="<?php echo $this->get_field_name( 'count_categories' ); ?>" value="true" <?php if( isset($instance['count_categories']) && $instance['count_categories']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count_tags' ); ?>"><?php _e('Display Tags Number : ', 'cmp' ) ?></label>
            <input id="<?php echo $this->get_field_id( 'count_tags' ); ?>" name="<?php echo $this->get_field_name( 'count_tags' ); ?>" value="true" <?php if( isset($instance['count_tags']) && $instance['count_tags']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
<?php
    }
}
?>