<?php
//widget readers
add_action('widgets_init', create_function('', 'return register_widget("readers");'));
class readers extends WP_Widget {
	function readers() {
		$this->WP_Widget('readers', theme_name.__( ' - Active readers', 'cmp' ), array( 'description' => __( 'Display avatar of the most active readers.', 'cmp' )));
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title']);
		$limit = $instance['limit'];
		$outer = $instance['outer'];
		$timer = $instance['timer'];
		echo $before_widget;
		echo $before_title.$title.$after_title;
		echo '<ul>';
		echo cmhello_readers( $out=$outer, $tim=$timer, $lim=$limit );;
		echo '</ul>';
		echo '<div class="clear"></div>';
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		$instance['outer'] = strip_tags($new_instance['outer']);
		$instance['timer'] = strip_tags($new_instance['timer']);
		return $instance;
	}
	function form($instance) {
		$defaults = array( 'title' => __( 'Active readers', 'cmp' ),'limit' => '20','outer' => '1','timer' => '60' );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Number: ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" type="text" size="5" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'outer' ); ?>"><?php _e('Exclude someone(ID): ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'outer' ); ?>" name="<?php echo $this->get_field_name( 'outer' ); ?>" value="<?php echo $instance['outer']; ?>" type="text" size="5" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'timer' ); ?>"><?php _e('Time limit(Days): ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'timer' ); ?>" name="<?php echo $this->get_field_name( 'timer' ); ?>" value="<?php echo $instance['timer']; ?>" type="text" size="5" />
		</p>
<?php
	}
}
/*
 * 读者墙
 * cmhello_readers( $outer='name', $timer='3', $limit='14' );
 * $outer 不显示某人
 * $timer 几个月时间内
 * $limit 显示条数
*/
function cmhello_readers($out,$tim,$lim){
	global $wpdb;
	$counts = $wpdb->get_results("select count(comment_author) as cnt, comment_author, comment_author_url, comment_author_email from (select * from $wpdb->comments left outer join $wpdb->posts on ($wpdb->posts.id=$wpdb->comments.comment_post_id) where comment_date > date_sub( now(), interval $tim day ) and user_id='0' and comment_author != '".$out."' and post_password='' and comment_approved='1' and comment_type='') as tempcmt group by comment_author order by cnt desc limit $lim");
	foreach ($counts as $count) {
		$c_url = $count->comment_author_url;
		if ($c_url == '') $c_url = 'javascript:;';
		if(cmp_get_option( 'lazyload' )){
		@$type .= '<li><a rel="nofollow" target="_blank" href="'. $c_url . '" title="' . $count->comment_author . ' + '. $count->cnt . '" ><img class="avatar lazy" src="'.get_template_directory_uri().'/images/grey.gif" data-original="'.cmp_avatar_url($count->comment_author_email).'" alt="'. $count->comment_author .'"/><noscript><img class="avatar" src="'.cmp_avatar_url($count->comment_author_email).'" alt="'. $count->comment_author .'"/></noscript></a></li>';
		}else{
		@$type .= '<li><a rel="nofollow" target="_blank" href="'. $c_url . '" title="' . $count->comment_author . ' + '. $count->cnt . '" ><img class="avatar" src="'.cmp_avatar_url($count->comment_author_email).'" alt="'. $count->comment_author .'"/></a></li>';
		}
	}
	echo $type;
}
?>