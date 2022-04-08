<?php
function cmp_home_tabs(){
	$home_tabs_active = cmp_get_option('home_tabs_box');
	$home_tabs = cmp_get_option('home_tabs');
	$thumb = cmp_get_option('home_tabs_thumb');
	$Posts = cmp_get_option('home_tabs_number');
	if( $home_tabs_active && $home_tabs ): ?>
	<section class="span12 widget-box home-tabs">
		<div class="widget-title">
		<span class="icon"> <i class="icon-list"></i> </span>
			<ul class="nav nav-tabs">
				<?php $i = 1;
				foreach ($home_tabs as $cat ) {
					?>
					<li <?php if($i == 1) echo 'class="active"'; ?>><a data-toggle="tab" href="#tab<?php echo $i; ?>" title="<?php echo get_the_category_by_ID($cat) ?>"><?php echo get_the_category_by_ID($cat) ?></a></li>
					<?php $i++; } ?>
				</ul>
			</div>
			<div class="widget-content tab-content">
				<?php $a = 1;
				foreach ($home_tabs as $cat ) {
					$count = 0;
					$cat_query = new WP_Query('cat='.$cat.'&posts_per_page='.$Posts); ?>
					<div id="tab<?php echo $a; ?>" class="tab-pane <?php if($a == 1) echo 'active'; ?>">
						<?php if($cat_query->have_posts()): ?>
							<ul>
								<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
									<?php if($count == 1 || $count == 2) : ?>
										<li class="span6 first-posts">
								<div class="inner-content">
									<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
										<?php cmp_post_thumbnail(180,120) ?>
									</a>
									<div class="first-content">
										<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
										<p class="summary">
											<?php cmp_excerpt_home() ?>
										</p>
										<p class="post-meta">
											<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
											<span><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
											<span><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
										</p>
									</div>
									<div class="clear"></div>
								</div>
							</li><!-- .first-posts -->
							<?php if($count == 2) echo '<div class="clear"></div>' ;?>
									<?php else: ?>
							<?php if ( $thumb ) : ?>
							<li class="span6 other-posts">
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php cmp_post_thumbnail(45,45) ?>
								</a>
								<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
								<p class="post-meta">
									<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
									<span><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
									<span><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
								</p>
								<div class="clear"></div>
							</li>
							<?php else: ?>
								<li class="span6 other-news"><span><?php the_time('m-d') ?></span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></li>
							<?php endif; ?>
						<?php endif; ?>
								<?php endwhile;?>
							</ul>
							<div class="clear"></div>
						<?php endif; ?>
					</div>
					<?php $a++; } ?>
				</div>
			</section>
		<?php endif;
	}
	?>