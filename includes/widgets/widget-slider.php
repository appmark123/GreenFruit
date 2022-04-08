<?php
add_action( 'widgets_init', 'slider_widget' );
function slider_widget() {
	register_widget( 'my_slider' );
}
class my_slider extends WP_Widget {
	function my_slider() {
		$widget_ops = array( 'classname' => 'widget-slider','description' => __( 'Display posts using a slide.', 'cmp' ) );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'slider-widget' );
		$this->WP_Widget( 'slider-widget',theme_name .__( ' - Slider', 'cmp' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$hide_title = $instance['hide_title'];
		$number = $instance['number'];

		$width = $instance['width'];
		$height = $instance['height'];
		$mode = $instance['mode'];
		$auto = $instance['auto'];
		$autoHover = $instance['autoHover'];
		$pause = $instance['pause'];
		$captions = $instance['captions'];
		$controls = $instance['controls'];
		$pager = $instance['pager'];
		$slider_query = $instance['slider_query'];
		$slider_custom = $instance['slider_custom'];
		$slider_cat = $instance['slider_cat'];
		$slider_tag = $instance['slider_tag'];
		$slider_posts = $instance['slider_posts'];
		$slider_pages = $instance['slider_pages'];
		global $post;
		$orig_post = $post;
		if( !$pause || $pause == ' ' || !is_numeric($pause))	$pause = 6000 ;

		if( $mode == 'horizontal' ) $mode = 'horizontal';
		elseif ($mode == 'vertical') $mode = 'vertical';
		else $mode = 'fade';
		if( $auto ) $auto = 'true';
		else $auto = 'false';
		if( $autoHover ) $autoHover= 'true';
		else $autoHover = 'false';
		if( $captions ) $captions= 'true';
		else $captions = 'false';
		if( $controls ) $controls= 'true';
		else $controls = 'false';
		if( $pager ) $pager= 'true';
		else $pager = 'false';
		if( $slider_query == 'custom' ){
			$custom_slider_args = array( 'post_type' => 'cmp_slider', 'p' => $slider_custom );
			$custom_slider = new WP_Query( $custom_slider_args );
		}else{
			if( $slider_query  == 'tag'){
				$tags = explode (',' , $slider_tag);
				foreach ($tags as $tag){
					$theTagId = get_term_by( 'name', $tag, 'post_tag' );
					if($fea_tags) $sep = ' , ';
					$fea_tags .=  $sep . $theTagId->slug;
				}
				$args= array('posts_per_page'=> $number , 'tag' => $fea_tags);
			}
			elseif( $slider_query  == 'category'){
				$args= array('posts_per_page'=> $number , 'category__in' => $slider_cat);
			}
			elseif( $slider_query  == 'post'){
				$posts = explode (',' , $slider_posts);
				$args= array('posts_per_page'=> $number , 'ignore_sticky_posts' => 1,'post_type' => 'post', 'post__in' => $posts  );
			}
			elseif( $slider_query  == 'page'){
				$pages = explode (',' , $slider_pages);
				$args= array('posts_per_page'=> $number , 'post_type' => 'page', 'post__in' => $pages  );
			}
			elseif( $slider_query  == 'sticky'){
				$sticky = get_option( 'sticky_posts' );
				rsort( $sticky );
				$sticky = array_slice( $sticky, 0, $number );
				$args= array('ignore_sticky_posts' => 1, 'post__in' => $sticky );
			}
			$featured_query = new wp_query( $args );
		}
		if( !$hide_title ){
			echo $before_widget;
			echo $before_title;
			echo $title ;
			echo $after_title;
		}else echo '<div class="widget widget-slider m15">';
		if( $slider_query != 'custom' ):
			if( $featured_query->have_posts() ) : ?>
		<ul class="side-slider">
			<?php while ( $featured_query->have_posts() ) : $featured_query->the_post()?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<?php if (cmp_get_option('timthumb') ) { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=<?php echo stripslashes($height); ?>&w=<?php echo stripslashes($width); ?>&zc=<?php echo stripslashes(cmp_get_option('thumb_zc')); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>" />
						<?php } else { ?>
						<img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>"/>
						<?php } ?>
					</a>
				</li>
			<?php endwhile;?>
		</ul>
	<?php endif; wp_reset_query();?>
<?php else: ?>
	<ul class="side-slider">
		<?php $i= 0;

		while ( $custom_slider->have_posts() ) : $custom_slider->the_post(); $i++;
		$custom = get_post_custom($post->ID);
		$slider = unserialize( $custom["custom_slider"][0] );
		$number = count($slider);

		if( $slider ){
			foreach( $slider as $slide ): ?>
			<li>
				<?php if( !empty( $slide['link'] ) ):?><a href="<?php  echo stripslashes( $slide['link'] )  ?>"><?php endif; ?>
				<img src="<?php echo cmp_slider_img_src( $slide['id'] , $width , $height ) ?>" alt="thumb<?php echo $i; ?>"
				<?php if( !empty( $slide['title'] ) ):?>
					title="<?php  echo stripslashes( $slide['title'] )  ?>"
				<?php endif; ?>
				width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>"
				/>
				<?php if( !empty( $slide['link'] ) ):?></a><?php endif; ?>
			</li>
		<?php endforeach; ?>
		<?php } ?>
	</ul>
<?php endwhile; wp_reset_query();?>
<?php endif; ?>
<script>
	jQuery(function() {
		jQuery('.side-slider').bxSlider({
			mode: '<?php echo $mode ?>',
			auto: <?php echo $auto ?>,
			autoHover: <?php echo $autoHover ?>,
			pause: <?php echo $pause ?>,
			pager: <?php echo $pager ?>,
			controls: <?php echo $controls ?>,
			captions: <?php echo $captions ?>
		});
	});
</script>
<?php
if( !$hide_title ){echo $after_widget;}
else echo '</div>';
}
function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
	$instance['number'] = strip_tags( $new_instance['number'] );
	$instance['width'] = strip_tags( $new_instance['width'] );
	$instance['height'] = strip_tags( $new_instance['height'] );
	$instance['mode'] = strip_tags( $new_instance['mode'] );
	$instance['auto'] = strip_tags( $new_instance['auto'] );
	$instance['autoHover'] = strip_tags( $new_instance['autoHover'] );
	$instance['pause'] = strip_tags( $new_instance['pause'] );
	$instance['captions'] = strip_tags( $new_instance['captions'] );
	$instance['controls'] = strip_tags( $new_instance['controls'] );
	$instance['pager'] = strip_tags( $new_instance['pager'] );
	$instance['slider_query'] = strip_tags( $new_instance['slider_query'] );
	$instance['slider_cat'] = implode(',' , $new_instance['slider_cat']  );
	$instance['slider_tag'] = strip_tags( $new_instance['slider_tag'] );
	$instance['slider_posts'] = strip_tags( $new_instance['slider_posts'] );
	$instance['slider_pages'] = strip_tags( $new_instance['slider_pages'] );
	$instance['slider_custom'] = strip_tags( $new_instance['slider_custom'] );
	return $instance;
}
function form( $instance ) {
	$defaults = array( 'number' => '4' ,'width' => '300','height' => '250' ,'mode' => 'fade','auto' => true,'autoHover' => true,'pause' => '6000','captions' => true,'controls' => false,'pager' => true,'slider_query' => 'category', 'slider_cat' => '1');
	$instance = wp_parse_args( (array) $instance, $defaults );

	$categories_obj = get_categories('hide_empty=0');
	$categories = array();
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
	$sliders = array();
	$custom_slider = new WP_Query( array( 'post_type' => 'cmp_slider', 'posts_per_page' => -1 ) );
	while ( $custom_slider->have_posts() ) {
		$custom_slider->the_post();
		$sliders[get_the_ID()] = get_the_title();
	}
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo @$instance['title']; ?>" class="widefat" type="text" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Widget Title :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="true" <?php if( isset($instance['hide_title']) && $instance['hide_title']) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of posts to show: ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" type="text" size="5" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width of images: ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" type="text" size="5" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height of images: ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" type="text" size="5" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'mode' ); ?>"><?php _e('Transition mode : ', 'cmp' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'mode' ); ?>" name="<?php echo $this->get_field_name( 'mode' ); ?>" >
			<option value="fade" <?php if( $instance['mode'] == 'fade' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('fade', 'cmp' ) ?></option>
			<option value="horizontal" <?php if( $instance['mode'] == 'horizontal' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('horizontal', 'cmp' ) ?></option>
			<option value="vertical" <?php if( $instance['mode'] == 'vertical' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('vertical', 'cmp' ) ?></option>
		</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'pause' ); ?>"><?php _e('Pause : ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'pause' ); ?>" name="<?php echo $this->get_field_name( 'pause' ); ?>" value="<?php echo $instance['pause']; ?>" type="text" size="5" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'captions' ); ?>"><?php _e('Display post\'s title :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'captions' ); ?>" name="<?php echo $this->get_field_name( 'captions' ); ?>" value="true" <?php if( $instance['captions'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'autoHover' ); ?>"><?php _e('Hover pause :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'autoHover' ); ?>" name="<?php echo $this->get_field_name( 'autoHover' ); ?>" value="true" <?php if( $instance['autoHover'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'auto' ); ?>"><?php _e('Auto play :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'auto' ); ?>" name="<?php echo $this->get_field_name( 'auto' ); ?>" value="true" <?php if( $instance['auto'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'controls' ); ?>"><?php _e('Controls :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'controls' ); ?>" name="<?php echo $this->get_field_name( 'controls' ); ?>" value="true" <?php if( $instance['controls'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'pager' ); ?>"><?php _e('Pager :', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'pager' ); ?>" name="<?php echo $this->get_field_name( 'pager' ); ?>" value="true" <?php if( $instance['pager'] ) echo 'checked="checked"'; ?> type="checkbox" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'slider_query' ); ?>"><?php _e('Slider query : ', 'cmp' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'slider_query' ); ?>" name="<?php echo $this->get_field_name( 'slider_query' ); ?>" >
			<option value="category" <?php if( $instance['slider_query'] == 'category' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('category', 'cmp' ) ?></option>
			<option value="tag" <?php if( $instance['slider_query'] == 'tag' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('tag', 'cmp' ) ?></option>
			<option value="post" <?php if( $instance['slider_query'] == 'post' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('post', 'cmp' ) ?></option>
			<option value="page" <?php if( $instance['slider_query'] == 'page' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('page', 'cmp' ) ?></option>
			<option value="sticky" <?php if( $instance['slider_query'] == 'sticky' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('sticky', 'cmp' ) ?></option>
			<option value="custom" <?php if( $instance['slider_query'] == 'custom' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('custom', 'cmp' ) ?></option>
		</select>
	</p>
	<p style="color:red;"><?php _e('The following options are based on the above "Slider query" settings : ', 'cmp' ) ?></p>
	<p>
		<?php $slider_cat = explode ( ',' , $instance['slider_cat'] ) ; ?>
		<label for="<?php echo $this->get_field_id( 'slider_cat' ); ?>"><?php _e('Category : ', 'cmp' ) ?></label>
		<select multiple="multiple" id="<?php echo $this->get_field_id( 'slider_cat' ); ?>[]" name="<?php echo $this->get_field_name( 'slider_cat' ); ?>[]">
			<?php foreach ($categories as $key => $option) { ?>
			<option value="<?php echo $key ?>" <?php if ( in_array( $key , $slider_cat ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
			<?php } ?>
		</select>
		<br /><?php _e('Select categories, use the Ctrl key combination to select multiple categories.', 'cmp' ) ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'slider_tag' ); ?>"><?php _e('Tags : ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'slider_tag' ); ?>" name="<?php echo $this->get_field_name( 'slider_tag' ); ?>" value="<?php if(isset($instance['slider_tag']) && $instance['slider_tag']) echo $instance['slider_tag']; ?>" class="widefat" type="text" />
		<br /><?php _e('Enter the tag name, use commas to separate names.', 'cmp' ) ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'slider_posts' ); ?>"><?php _e('Posts : ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'slider_posts' ); ?>" name="<?php echo $this->get_field_name( 'slider_posts' ); ?>" value="<?php if(isset($instance['slider_posts']) && $instance['slider_posts']) echo $instance['slider_posts']; ?>" class="widefat" type="text" />
		<br /><?php _e('Enter the post\'s ID, use commas to separate IDs.', 'cmp' ) ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'slider_pages' ); ?>"><?php _e('Pages : ', 'cmp' ) ?></label>
		<input id="<?php echo $this->get_field_id( 'slider_pages' ); ?>" name="<?php echo $this->get_field_name( 'slider_pages' ); ?>" value="<?php if(isset($instance['slider_pages'] ) && $instance['slider_pages']) echo $instance['slider_pages']; ?>" class="widefat" type="text" />
		<br /><?php _e('Enter the page\'s ID, use commas to separate IDs.', 'cmp' ) ?>
	</p>
	<p>
		<?php if(isset($instance['slider_custom'] )) $slider_custom = explode ( ',' , $instance['slider_custom'] ) ; ?>
		<label for="<?php echo $this->get_field_id( 'slider_custom' ); ?>"><?php _e('Custom : ', 'cmp' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'slider_custom' ); ?>[]" name="<?php echo $this->get_field_name( 'slider_custom' ); ?>[]">
			<?php foreach ($sliders as $key => $option) { ?>
			<option value="<?php echo $key ?>" <?php if ( @in_array( $key , $slider_custom ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
			<?php } ?>
		</select>
		<br /><?php _e('Select a custom slider. Please make sure that you have created a custom slider at least, if not, <a  href="/wp-admin/post-new.php?post_type=cmp_slider" target="_blank">create one</a> now.', 'cmp' ) ?>
	</p>
	<?php
}
}
?>