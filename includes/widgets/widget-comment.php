<?php
//widget new_comments
add_action('widgets_init', create_function('', 'return register_widget("new_comments");'));
class new_comments extends WP_Widget {
	function new_comments() {
		$widget_ops = array( 'classname' => 'widget-new-comments','description' => __('Display vistor\'s recently comments(including avatar, name and comment text)','cmp') );
		$this->WP_Widget( 'newcomments',theme_name .__( ' - Recently Comments', 'cmp' ), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		$limit = $instance['limit'];
		$outer = $instance['outer'];
		$outpost = $instance['outpost'];
		echo $before_title.$title.$after_title;
		echo '<ul>';
		echo mod_newcomments( $limit,$outpost,$outer );
		echo '</ul>';
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		$instance['outer'] = strip_tags($new_instance['outer']);
		$instance['outpost'] = strip_tags($new_instance['outpost']);
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => __('Recently Comments','cmp'),
			'limit' => '5',
			'outer' => '1',
			'outpost' => '1'
			)
		);
		$title = strip_tags($instance['title']);
		$limit = strip_tags($instance['limit']);
		$outer = strip_tags($instance['outer']);
		$outpost = strip_tags($instance['outpost']);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Number: ', 'cmp' ) ?>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'outer' ); ?>"><?php _e('Exclude user(ID): ', 'cmp' ) ?>
				<input class="widefat" id="<?php echo $this->get_field_id('outer'); ?>" name="<?php echo $this->get_field_name('outer'); ?>" type="number" value="<?php echo $instance['outer']; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'outpost' ); ?>"><?php _e('Exclude post(ID): ', 'cmp' ) ?>
				<input class="widefat" id="<?php echo $this->get_field_id('outpost'); ?>" name="<?php echo $this->get_field_name('outpost'); ?>" type="number" value="<?php echo $instance['outpost']; ?>" />
			</label>
		</p>
<?php
	}
}
function mod_newcomments( $limit,$outpost,$outer ){
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved,comment_author_email, comment_type,comment_author_url, SUBSTRING(comment_content,1,40) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_post_ID!='".$outpost."' AND user_id!='".$outer."' AND comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $limit";
	$comments = $wpdb->get_results($sql);
	foreach ( $comments as $comment ) {
		if(cmp_get_option( 'lazyload' )){
		@$output .= '<li><img class="avatar lazy" alt="'.strip_tags($comment->comment_author).'" src="'.get_template_directory_uri().'/images/grey.gif" data-original="'.cmp_avatar_url($comment->comment_author_email).'" width="45" height="45" /><noscript><img class="avatar" alt="'.strip_tags($comment->comment_author).'" src="'.cmp_avatar_url($comment->comment_author_email).'" width="45" height="45" /></noscript><a rel="nofollow" href="'.get_permalink($comment->ID).'#comment-'.$comment->comment_ID.'" title="'.sprintf( __( 'Comments on %s', 'cmp' ), $comment->post_title ).'"><span>'.strip_tags($comment->comment_author).'</span>：'.strip_tags($comment->com_excerpt).'</a></li>';
		}else{
		@$output .= '<li><img class="avatar" alt="'.strip_tags($comment->comment_author).'" src="'.cmp_avatar_url($comment->comment_author_email).'" width="45" height="45" /><a rel="nofollow" href="'.get_permalink($comment->ID).'#comment-'.$comment->comment_ID.'" title="'.sprintf( __( 'Comments on %s', 'cmp' ), $comment->post_title ).'"><span>'.strip_tags($comment->comment_author).'</span>：'.strip_tags($comment->com_excerpt).'</a></li>';
		}
	}
	echo $output;
};
?>