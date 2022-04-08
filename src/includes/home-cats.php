<?php
function get_home_cats( $cat_data ){ ?>
<?php
global $count2 ;
$Cat_ID = $cat_data['id'];
$Posts = $cat_data['number'];
$order = $cat_data['order'];
$thumb = $cat_data['thumb'];
if(isset($cat_data['icon'])){
	$icon = $cat_data['icon'];
}else{
	$icon = "icon-list";
}
if( $order == 'rand') { $rand ="&orderby=rand";
$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts.$rand);
} else {
	$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts);
}
$cat_title = get_the_category_by_ID($Cat_ID);
$count = 0;
$home_layout = $cat_data['style'];
?>
<?php if( $home_layout == '2c'):  //************** 2C ****************************************************** ?>
	<?php $count2++; ?>
	<section class="span6 column2 <?php if($count2 == 1 || $count2 == 3 ) { echo 'first-column'; $count2=1; } ?>">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon"> <i class="<?php echo $icon ?>"></i> </span>
				<h2><a href="<?php echo get_category_link( $Cat_ID ); ?>"><?php echo $cat_title ; ?></a></h2>
			</div>
			<div class="widget-content">
				<?php if($cat_query->have_posts()): ?>
					<ul>
						<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
							<?php if($count == 1) : ?>
								<li class="first-posts">
									<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php cmp_post_thumbnail(180,120) ?></a>
									<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
									<p class="summary">
											<?php cmp_excerpt_home() ?>
									</p>
								</li>
								<div class="clear"></div>
							<?php else: ?>
								<?php if ( $thumb == 'true') : ?>
									<li class="other-posts">
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
									<li class="other-news"><span><?php the_time('m-d') ?></span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></li>
								<?php endif; ?>
							<?php endif; ?>
						<?php endwhile;?>
					</ul>
					<div class="clear"></div>
				<?php endif; wp_reset_query();?>
			</div><!-- .cat-box-content /-->
		</div>
	</section> <!-- Two Columns -->
<?php elseif( $home_layout == '1c' ):   ?>
	<section class="span12 two-row">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon"> <i class="<?php echo $icon ?>"></i> </span>
				<h2><a href="<?php echo get_category_link( $Cat_ID ); ?>"><?php echo $cat_title ; ?></a></h2>
			</div>
			<div class="widget-content">
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
						<?php else: ?>
							<?php if($count == 3) echo '<div class="clear"></div>' ;?>
							<?php if ( $thumb == 'true') : ?>
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
			<?php endif; wp_reset_query();?>
			</div>
		</div><!-- .cat-box-content /-->
	</section><!-- Wide Box -->
<?php else :   //************** list **********************************************************************************  ?>
	<section class="span12 three-row">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon"> <i class="<?php echo $icon ?>"></i> </span>
				<h2><a href="<?php echo get_category_link( $Cat_ID ); ?>"><?php echo $cat_title ; ?></a></h2>
			</div>
			<div class="widget-content">
			<?php if($cat_query->have_posts()): ?>
				<ul>
					<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
						<?php if($count == 1 || $count == 2 || $count == 3) : ?>
							<li class="span4 first-posts">
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
										<?php cmp_post_thumbnail(180,120) ?>
								</a>
								<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
								<p class="summary">
									<?php cmp_excerpt_home() ?>
								</p>
								<p class="post-meta">
									<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
									<span><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
									<span><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
								</p>
								<div class="clear"></div>
							</li><!-- .first-posts -->
							<?php if($count == 3) echo '<div class="clear"></div>' ;?>
						<?php else: ?>
							<?php if ( $thumb == 'true') : ?>
							<li class="span4 other-posts">
								<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php cmp_post_thumbnail(45,45) ?>
								</a>
								<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
								<p class="post-meta">
									<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
									<span><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
									<span><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
								</p>
							</li>
							<?php else: ?>
								<li class="span4 other-news"><span><?php the_time('m-d') ?></span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endwhile;?>
				</ul>
				<div class="clear"></div>
			<?php endif; wp_reset_query();?>
			</div>
		</div><!-- .cat-box-content /-->
	</section><!-- List Box -->
<?php endif; ?>
<?php } ?>