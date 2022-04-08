<?php
add_action( 'widgets_init', 'Tag_Cloud_Widget' );
function Tag_Cloud_Widget() {
	register_widget( 'cm_Tag_Cloud_Widget' );
}
class cm_Tag_Cloud_Widget extends WP_Widget_Tag_Cloud {
	function cm_Tag_Cloud_Widget() {
		$widget_ops = array('classname' => 'widget-tagcloud', 'description' => __( 'Display the most common tags.', 'cmp' ));
		$this->WP_Widget('cm-tagcloud', theme_name .__(' - Tag Cloud','cmp'), $widget_ops);
	}
	function widget( $args, $instance ) {
		extract($args);
		$nums = empty($instance['nums'])? 45 : $instance['nums'];
		$excludetag = $instance['excludetag'];
		$ordertag = empty($instance['ordertag'])? 'ASC' : $instance['ordertag'];
		$orderbytag = empty($instance['orderbytag'])? 'name' : $instance['orderbytag'];
		$tagunit = empty($instance['tagunit'])? 'pt' : $instance['tagunit'];
		$tagbigsize = empty($instance['tagbigsize'])? '22' : $instance['tagbigsize'];
		$tagsmallsize = empty($instance['tagsmallsize'])? '8' : $instance['tagsmallsize'];
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags','cmp');
			} else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( apply_filters('widget_tag_cloud_args', array(
			'smallest' => $tagsmallsize,
			'largest' => $tagbigsize,
			'unit' => $tagunit,
			'number' => $nums,
			'orderby' => $orderbytag,
			'order' => $ordertag,
			'taxonomy' => $current_taxonomy,
			'exclude' => $excludetag
			)));
		echo "</div>\n";
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		$instance['nums'] = stripslashes($new_instance['nums']);
		$instance['excludetag'] = stripslashes($new_instance['excludetag']);
		$instance['ordertag'] = stripslashes($new_instance['ordertag']);
		$instance['orderbytag'] = stripslashes($new_instance['orderbytag']);
		$instance['tagunit'] = stripslashes($new_instance['tagunit']);
		$instance['tagbigsize'] = stripslashes($new_instance['tagbigsize']);
		$instance['tagsmallsize'] = stripslashes($new_instance['tagsmallsize']);
		return $instance;
	}
	function form( $instance ){
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title']) && $instance['title']) echo esc_attr( $instance['title'] ); ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomies :','cmp') ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
					<?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( !$tax->show_tagcloud || empty($tax->labels->name) )
						continue;
					?>
					<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
				<?php endforeach; ?>
			</select></p>
			<p><label for="<?php echo $this->get_field_id('nums'); ?>"><?php _e('Number to show (Default "45"): ', 'cmp' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('nums'); ?>" name="<?php echo $this->get_field_name('nums'); ?>" value="<?php if (isset ( $instance['nums']) && $instance['nums']) echo esc_attr( $instance['nums'] ); ?>" /></p>
				<p><label for="<?php echo $this->get_field_id('orderbytag'); ?>"><?php _e('Tags orderby (Default "name"]): ', 'cmp' ) ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('orderbytag'); ?>" name="<?php echo $this->get_field_name('orderbytag'); ?>">
						<option <?php if ( isset($instance['orderbytag']) && $instance['orderbytag'] == 'name') echo 'selected="SELECTED"'; else echo ''; ?>  value="name"><?php  echo __('name','cmp');?></option>
						<option <?php if ( isset($instance['orderbytag']) && $instance['orderbytag'] == 'count') echo 'selected="SELECTED"'; else echo ''; ?> value="count"><?php echo __('count','cmp');?></option>
					</select>
				</p>
				<p><label for="<?php echo $this->get_field_id('ordertag'); ?>"><?php _e('Tags order (Default "ASC"): ', 'cmp' ) ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('ordertag'); ?>" name="<?php echo $this->get_field_name('ordertag'); ?>">
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'ASC') echo 'selected="SELECTED"'; else echo ''; ?>  value="ASC"><?php  echo __('ASC','cmp');?></option>
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'DESC') echo 'selected="SELECTED"'; else echo ''; ?> value="DESC"><?php echo __('DESC','cmp');?></option>
						<option <?php if ( isset($instance['ordertag']) && $instance['ordertag'] == 'RAND') echo 'selected="SELECTED"'; else echo ''; ?> value="RAND"><?php echo __('RAND','cmp');?></option>
					</select>
				</p>
				<p><label for="<?php echo $this->get_field_id('tagunit'); ?>"><?php _e('Unit (Default "pt"):','cmp') ?></label>
					<select  class="widefat" id="<?php echo $this->get_field_id('tagunit'); ?>" name="<?php echo $this->get_field_name('tagunit'); ?>">
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'pt') echo 'selected="SELECTED"'; else echo ''; ?>  value="pt"><?php  echo __('pt','cmp');?></option>
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'px') echo 'selected="SELECTED"'; else echo ''; ?>  value="px"><?php  echo __('px','cmp');?></option>
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == 'em') echo 'selected="SELECTED"'; else echo ''; ?> value="em"><?php echo __('em','cmp');?></option>
						<option <?php if ( isset($instance['tagunit']) && $instance['tagunit'] == '%') echo 'selected="SELECTED"'; else echo ''; ?> value="%"><?php echo __('%','cmp');?></option>
					</select>
				</p>
				<p><label for="<?php echo $this->get_field_id('tagsmallsize'); ?>"><?php _e('Smallest text size (Default "8"):','cmp') ?></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id('tagsmallsize'); ?>" name="<?php echo $this->get_field_name('tagsmallsize'); ?>" value="<?php if (isset ( $instance['tagsmallsize']) && $instance['tagsmallsize']) echo esc_attr( $instance['tagsmallsize'] ); ?>" /></p>
					<p><label for="<?php echo $this->get_field_id('tagbigsize'); ?>"><?php _e('Largest text size (Default "22"):','cmp') ?></label>
						<input type="text" class="widefat" id="<?php echo $this->get_field_id('tagbigsize'); ?>" name="<?php echo $this->get_field_name('tagbigsize'); ?>" value="<?php if (isset ( $instance['tagbigsize']) && $instance['tagbigsize']) echo esc_attr( $instance['tagbigsize'] ); ?>" /></p>
						<p><label for="<?php echo $this->get_field_id('excludetag'); ?>"><?php _e('Exclude tags (Use commas to separate IDs):','cmp') ?></label>
							<input type="text" class="widefat" id="<?php echo $this->get_field_id('excludetag'); ?>" name="<?php echo $this->get_field_name('excludetag'); ?>" value="<?php if (isset ( $instance['excludetag']) && $instance['excludetag']) echo esc_attr( $instance['excludetag'] ); ?>" /></p>
							<?php
						}
					}
					?>