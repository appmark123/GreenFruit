<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
		?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'cmp' ); ?></p>
		<?php
		return;
	}
}
/* This variable is for alternating comment background */
$oddcomment = '';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<div id="comments">
		<h3><?php comments_number(__('No comments','cmp'), __('One comment','cmp'), '% '.__('comments','cmp') );?></h3>
	</div>
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=cm_comment&end-callback=cm_end_comment&max_depth=23'); ?>
	</ol>
		<div class="page-nav"><?php paginate_comments_links(); ?></div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div id="comments">
			<h3><?php _e('Leave a Reply','cmp') ?></h3>
		</div>
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e( 'Comments are closed.', 'cmp' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
	<div id="respond_box">
		<div id="respond">
			<div class="cancel-comment-reply">
				<?php cancel_comment_reply_link(); ?>
			</div>
			<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p><?php print ( __( 'You must be' , 'cmp' )); ?><a rel="nofollow" href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('Logged in </a>to post a comment.','cmp'); ?></p>
			<?php else : ?>
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
					<?php if ( $user_ID ) : ?>
						<p><?php print (__( 'Logged in as '  , 'cmp' )); ?><a rel="nofollow" href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>&nbsp;&nbsp;<a rel="nofollow" href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out"><?php print (__('Log out','cmp')); ?></a>
						<?php elseif ( '' != $comment_author ): ?>
							<div class="author"><?php printf(__('Welcome back <strong>%s</strong>','cmp'), $comment_author); ?>
								<a rel="nofollow" href="javascript:toggleCommentAuthorInfo();" id="toggle-comment-author-info"><?php _e(' Change','cmp'); ?></a></div>
							<?php
							echo '
							<script type="text/javascript" charset="utf-8">';
							if (get_locale()=='zh_TW' || get_locale()=='zh_HK' || get_locale()=='zh_CN'){
								echo 'var changeMsg = "[更改]";
								var closeMsg = "[隐藏]";';
							}else{
								echo 'var changeMsg = "[Change]";
								var closeMsg = "[Hide]";';
							}
							echo '
								function toggleCommentAuthorInfo() {
									jQuery("#comment-author-info").slideToggle("slow", function(){
										if ( jQuery("#comment-author-info").css("display") == "none" ) {
											jQuery("#toggle-comment-author-info").text(changeMsg);
										} else {
											jQuery("#toggle-comment-author-info").text(closeMsg);
										}
									});
								}
								jQuery(document).ready(function(){
									jQuery("#comment-author-info").hide();
								});
							</script>';
							?>
							</p>
						<?php endif; ?>
						<?php if ( ! $user_ID ): ?>
							<div id="comment-author-info">
								<p>
									<input type="text" name="author" id="author" class="commenttext" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
									<label for="author"><?php _e('Name','cmp'); ?><?php if ($req) echo " *"; ?></label>
								</p>
								<p>
									<input type="text" name="email" id="email" class="commenttext" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
									<label for="email"><?php _e('Email','cmp'); ?><?php if ($req) echo " *"; ?></label>
								</p>
								<p>
									<input type="text" name="url" id="url" class="commenttext" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
									<label for="url"><?php _e('Website','cmp'); ?></label>
								</p>
							</div>
						<?php endif; ?>
						<div class="clear"></div>
						<p>
							<?php if(cmp_get_option('smilies')) get_template_part( "includes/smilies");?>
						</p>
						<p><textarea name="comment" id="comment" tabindex="4"></textarea></p>
						<p>
							<input class="submit" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit','cmp'); ?>" />
							<input class="reset" name="reset" type="reset" id="reset" tabindex="6" value="<?php esc_attr_e( __('Rewrite','cmp') ); ?>" />
							<?php comment_id_fields(); ?>
						</p>
						<?php do_action('comment_form', $post->ID); ?>
					</form>
					<div class="clear"></div>
				<?php endif; // If registration required and not logged in ?>
			</div>
		</div>
	<?php endif; // if you delete this the sky will fall on your head ?>