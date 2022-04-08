<?php
add_action( 'widgets_init', 'social_widget_box' );
function social_widget_box() {
	register_widget( 'social_widget' );
}
class social_widget extends WP_Widget {
	function social_widget() {
		$widget_ops = array( 'classname' => 'social-icons-widget' ,'description' => __('Display your social and subscribe information.','cmp'));
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'social' );
		$this->WP_Widget( 'social',theme_name .__( ' - Follow & Subscribe', 'cmp' ), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$hide_title = $instance['hide_title'];
		if( !$hide_title ){
			echo $before_widget;
			echo $before_title;
			echo $title ;
			echo $after_title;
		}else{
			echo '<div class="widget side-box mt10 social-icons-widget">';
			echo '<div class="m15 widget-container">';
		}
		?>
		<div class="social-icons">
			<?php if(cmp_get_option('display_social_icon')): ?>
			<ul class="follows">
				<?php if(cmp_get_option('qq')): ?>
					<li class="qq"><a href="http://wpa.qq.com/msgrd?V=1&Menu=yes&Uin=<?php echo stripslashes(cmp_get_option('qq')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Contact me by QQ','cmp') ?>">QQ</a></li>
				<?php endif ?>
				<?php if(cmp_get_option('send_email')): ?>
					<li class="email"><a href="<?php echo stripslashes(cmp_get_option('send_email')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Send email','cmp') ?>"><?php _e('Send email','cmp') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('sina_weibo')): ?>
					<li class="sina_weibo"><a href="<?php echo stripslashes(cmp_get_option('sina_weibo')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Sina Weibo','cmp') ?>"><?php _e('Sina Weibo','cmp') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('qq_weibo')): ?>
					<li class="qq_weibo"><a href="<?php echo stripslashes(cmp_get_option('qq_weibo')); ?>" target="_blank" rel="external nofollow" title="<?php _e('QQ Weibo','cmp') ?>"><?php _e('QQ Weibo','cmp') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('twitter')): ?>
					<li class="twitter"><a href="<?php echo stripslashes(cmp_get_option('twitter')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Twitter','cmp') ?>"><?php _e('Twitter','cmp') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('google_plus')): ?>
					<li class="google_plus"><a href="<?php echo stripslashes(cmp_get_option('google_plus')); ?>" target="_blank" rel="external nofollow"  title="<?php _e('Google+','cmp') ?>"><?php _e('Google+','cmp') ?></a></li>
				<?php endif ?>
				<?php if(cmp_get_option('rss_url')): ?>
					<li class="rss"><a href="<?php echo stripslashes(cmp_get_option('rss_url')); ?>" target="_blank" rel="external nofollow" title="<?php _e('Feed Subscription','cmp') ?>"><?php _e('Feed Subscription','cmp') ?></a></li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>
			<div class="popup-follow-feed">
				<p class="feed-to"><?php _e('Subscribe To:','cmp') ?>
					<a rel="external nofollow" target="_blank" href="http://www.xianguo.com/subscribe.php?url=<?php echo stripslashes(cmp_get_option('rss_url')); ?>"><?php _e('XianGuo','cmp') ?></a>
					<a rel="external nofollow" target="_blank" href="http://reader.youdao.com/b.do?keyfrom=bookmarklet&url=<?php echo rawurlencode(cmp_get_option('rss_url')); ?>"><?php _e('Youdao','cmp') ?></a>
					<a rel="external nofollow" target="_blank" href="http://feedly.com/index.html#subscription%2Ffeed%2F<?php echo stripslashes(cmp_get_option('rss_url')); ?>"><?php _e('Feedly','cmp') ?></a>
				</p>
				<p><?php _e('Subscribe URL:','cmp') ?>
					<input class="ipt" type="text" readonly value="<?php echo stripslashes(cmp_get_option('rss_url')); ?>">
				</p>
			</div>
			<div class="popup-follow-mail">
				<form action="http://list.qq.com/cgi-bin/qf_compose_send" target="_blank" method="post">
					<input type="hidden" name="t" value="qf_booked_feedback">
					<input type="hidden" name="id" value="<?php echo stripslashes(cmp_get_option('qq_email_list')); ?>">
					<input id="to" placeholder="<?php _e('Enter your E-mail','cmp') ?>" name="to" type="text" class="ipt"><input class="btn btn-primary" type="submit" value="<?php _ex('Subscribe','Subscribe to Email','cmp') ?>">
				</form>
			</div>
		</div>
		<?php
		if( !$hide_title ){
			echo $after_widget;
		}else{
			echo '</div>';
			echo '</div>';
		}
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' =>__('Follow & Subscribe' , 'cmp') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Widget Title :', 'cmp' )?></label>
			<input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="true" <?php if( isset($instance['hide_title']) && $instance['hide_title'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		<?php
	}
}
?>