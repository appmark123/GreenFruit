<?php
add_action( 'widgets_init', 'wp_recently_viewed' );
// Register widget
function wp_recently_viewed() {
	register_widget( 'recently_viewed' );
}
class recently_viewed extends WP_Widget {
	function recently_viewed() {
		/* Widget settings */
		$widget_ops = array( 'classname' => 'wp-recently-viewed', 'description' => __( 'Show posts of visitors recently viewed' , 'cmp') );
		/* Create the widget */
		$this->WP_Widget( 'recently_viewed', theme_name .__( ' - Recently Viewed' , 'cmp'), $widget_ops );
	}
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;
?>
	<div id="recently_viewed"></div>
<?php
	  echo $after_widget;
   }
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __( 'You recently viewed','cmp') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php
	}
}
function wp_recently_viewed_js() {
	$script_html = '<script src="'.get_template_directory_uri().'/js/view-history.js"></script>';
	if( is_single() ) {
	  $script_html .= "\n" .'<script src="'.get_template_directory_uri().'/js/add-history.js"></script>';
	}
	echo "\n" . $script_html. "\n";
}
if (!is_admin()){
	add_action("wp_footer", "wp_recently_viewed_js");
}
?>