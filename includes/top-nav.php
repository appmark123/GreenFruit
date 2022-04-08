<div id="user-nav" class="navbar navbar-inverse">
	<ul class="nav">
		<?php
		$url_this = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		if( cmp_get_option('show_login')):
			if(is_user_logged_in()){
				if(class_exists("cartpaujPM")){
					global $cartpaujPMS;
					@$numNew = $cartpaujPMS->getNewMsgs();
					$pmtip = '<span class="label label-important tip-bottom" title="'.$numNew.__('unread messages','cmp').'">'.$numNew.'</span>';
				}
			?>
				<li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-user"></i> <span class="text"><?php _e('Manage','cmp'); ?></span><?php if(@$numNew && @$numNew != 0) echo $pmtip; ?><b class="caret"></b></a>
					<ul class="dropdown-menu user-dashboard">
						<?php if(current_user_can( 'manage_options' )) {
							if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'admin-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker()));
						}
						if(function_exists('wp_nav_menu') && cmp_get_option('user_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'user-menu', 'fallback_cb' => 'cmp_nav_fallback','walker' => new wp_bootstrap_navwalker()));
						?>
					</ul>
				</li>
				<li class="user-btn"><a href="<?php echo wp_logout_url($url_this); ?>" title="<?php _e('Logout','cmp'); ?>" rel="nofollow"><i class="icon-signout"></i><span class="text"><?php _e('Logout','cmp'); ?></span></a></li>
			<?php
			} else {
				if(cmp_get_option('password_url')){
					$password_url = htmlspecialchars_decode(cmp_get_option('password_url')) ;
				}else{
					$password_url = home_url().'/wp-login.php?action=lostpassword';
				}
				?>
				<li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-signin"></i>  <span class="text"><?php _e('Login','cmp'); ?></span><b class="caret"></b></a>
					<ul class="dropdown-menu">
						<form class="user-login" name="loginform" action="<?php echo wp_login_url($url_this); ?>" method="post">
							<li><i class="icon-user"></i><input class="ipt" type="text" name="log" value="" size="18"></li>
							<li><i class="icon-lock"></i><input class="ipt" type="password" name="pwd" value="" size="18"></li>
							<li class="btn"><input class="login-btn" type="submit" name="submit" value="<?php _e('Login','cmp'); ?>"><a class="pw-reset" rel="nofollow" href="<?php echo $password_url; ?>"><?php _e('Lost password','cmp'); ?></a></li>
						</form>
					</ul>
				</li>
				<?php
				if(cmp_get_option('register_url')){
					$register_url = htmlspecialchars_decode(cmp_get_option('register_url')) ;
				}else{
					$register_url = home_url().'/wp-login.php?action=register';
				}
				if(get_option('users_can_register') == '1' ) :?>
				<li class="user-btn user-reg"><a href="<?php echo $register_url; ?>" title="<?php _e('Register','cmp'); ?>" rel="nofollow"><i class="icon-key"></i><span class="text"><?php _e('Register','cmp'); ?></span></a></li>
				<?php endif; ?>
		<?php }
		endif; ?>
		<?php
		if( cmp_get_option('show_qqqun')){
			echo '<li id="qqqun" class="other-nav"><a target="_blank" title="'.cmp_get_option('qqqun_title').'" href="'.cmp_get_option('qqqun_url').'"><i class="icon-group"></i> '.__('Join QQ group','cmp').'</a></li>';
		}
		if( cmp_get_option('show_qq')){
			echo '<li id="qq" class="other-nav">'.htmlspecialchars_decode( cmp_get_option('qq_code') ).'</li>';
		}
		if( cmp_get_option('show_weibo')){
			echo '<li id="swb" class="other-nav"><wb:follow-button uid="'.cmp_get_option('weibo_uid').'" type="'.cmp_get_option('weibo_type').'" height="24"></wb:follow-button></li>';
		}
		if( cmp_get_option('show_qqweibo')){
			echo'<li id="qwb" class="other-nav"><iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name='.cmp_get_option('qqweibo_name').'&style=5&t='.cmp_get_option('qqweibo_t').'&f='.cmp_get_option('qqweibo_f').'" frameborder="0" scrolling="auto" width="150" height="24" marginwidth="0" marginheight="0" allowtransparency="true"></iframe></li>';
		}
		?>
	</ul>
</div>
