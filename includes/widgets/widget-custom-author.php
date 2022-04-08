<?php
add_action( 'widgets_init', 'Author_Bio_widget' );
function Author_Bio_widget() {
	register_widget( 'Author_Bio' );
}
class Author_Bio extends WP_Widget {
	function Author_Bio() {
		$widget_ops = array( 'classname' => 'Author-Bio','description' => __('Custom author\'s information.','cmp') );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'author-bio-widget' );
		$this->WP_Widget( 'author-bio-widget',theme_name .__( ' - Custom Author Bio', 'cmp' ), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$img = $instance['img'];
		$text_code = $instance['text_code'];
		echo $before_widget;
		echo $before_title;
		echo $title ;
		echo $after_title; ?>
		<div class="author-avatar">
			<img alt="" src="<?php echo $img; ?>">
		</div>
		<div class="author-description">
			<?php
			echo do_shortcode( $text_code ); ?>
		</div><div class="clear"></div>
		<?php
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['img'] = $new_instance['img'] ;
		$instance['text_code'] = $new_instance['text_code'] ;
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' =>__( 'About Author' , 'cmp') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img' ); ?>"><?php _e('Avatar : ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" value="<?php if(isset($instance['img'])) echo $instance['img']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text_code' ); ?>"><?php _e('About : <i>You can use Shortcodes</i>', 'cmp' ) ?></label>
			<textarea rows="15" id="<?php echo $this->get_field_id( 'text_code' ); ?>" name="<?php echo $this->get_field_name( 'text_code' ); ?>" class="widefat" ><?php if(isset($instance['text_code']) && $instance['text_code']) echo $instance['text_code']; ?></textarea>
		</p>
		<?php
	}
}
?>