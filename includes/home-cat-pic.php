<?php
function get_home_news_pic( $cat_data ){ ?>
<?php
$Cat_ID = $cat_data['id'];
$Posts = 5;
$Box_Title = $cat_data['title'];
$Box_style = $cat_data['style'];
if(isset($cat_data['icon'])){
	$icon = $cat_data['icon'];
}else{
	$icon = "icon-list";
}
if($Box_style == 'row') $Posts = 8;
$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts);
?>
<section class="span12 pic-box">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon"> <i class="<?php echo $icon ?>"></i> </span>
			<h2><?php echo $Box_Title ; ?></h2>
		</div>
		<div class="widget-content">
		<?php if($cat_query->have_posts()): $count=0; ?>
			<ul>
				<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
					<?php if($count == 1 && $Box_style != 'row') : ?>
						<li class="first-pic">
							<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
								<?php cmp_post_thumbnail(675,260) ?>
							</a>
							<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						</li><!-- .first-pic -->
					<?php else: ?>
						<li>
							<a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>">
								<?php cmp_post_thumbnail(328,118) ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endwhile;?>
			</ul>
			<div class="clear"></div>
		<?php endif; ?>
		</div>
	</div><!-- .cat-box-content /-->
</section>
<?php } ?>