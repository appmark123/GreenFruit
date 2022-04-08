<?php if ( ! have_posts() ) : ?>
	<article class="widget-content archive-simple">
		<h2 class="post-title"><?php _e( 'Not Found', 'cmp' ); ?></h2>
		<div class="entry">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'cmp' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</article>
<?php else : ?>
	<div class="widget-box blog-layout">
	<?php if ( cmp_get_option('list_style') == 'simple_title') {
		echo '<ul class="simple-title">';
	} else {
		echo '<ul>';
	}
		while ( have_posts() ) : the_post();
			if ( cmp_get_option('list_style') == 'big_thumb') { ?>
			<li class="archive-big">
				<article>
					<?php if (function_exists('cmp_post_thumbnail')): ?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<?php cmp_post_thumbnail(930,330) ?>
						</a>
					<?php endif; ?>
					<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
					<?php if (function_exists('loop_blog_meta')) loop_blog_meta(); ?>
				</article>
			</li>
			<?php } elseif ( cmp_get_option('list_style') == 'small_thumb' ) { ?>
			<li class="archive-thumb">
				<article>
					<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<?php if (function_exists('cmp_post_thumbnail')): ?>
						<a class="pic <?php if(cmp_get_option('thumb_left')) echo 'float-left'; ?>" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<?php cmp_post_thumbnail(180,120) ?>
						</a>
					<?php endif; ?>
					<p><?php if (function_exists('cmp_excerpt')) cmp_excerpt() ?></p>
					<?php if (function_exists('loop_blog_meta')) loop_blog_meta(); ?>
					<div class="clear"></div>
				</article>
			</li>
			<?php } else { ?>
			<li class="archive-simple">
				<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></h2>
				<p class="post-meta">
					<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
					<span><i class="icon-eye-open"></i><?php if(function_exists('the_views')) { the_views(); } ?></span>
				</p>
			</li>
			<?php } ?>
		<?php endwhile; ?>
	</ul>
	<?php if ($wp_query->max_num_pages > 1) cmp_pagenavi(); ?>
</div>
<?php endif; ?>
<?php
function loop_blog_meta(){
	if (cmp_get_option('archive_meta')) :?>
	<p class="post-meta">
		<?php if (cmp_get_option('archive_author')):?>
			<span><i class="icon-user"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?>" title="<?php sprintf( esc_attr__( 'View all posts by %s', 'cmp' ), get_the_author() ) ?>"><?php echo get_the_author() ?></a></span>
		<?php endif; ?>
		<?php if (cmp_get_option('archive_date') && function_exists('cmp_get_time')) :?>
			<span><i class="icon-time"></i><?php cmp_get_time();?></span>
		<?php endif; ?>
		<?php if( cmp_get_option('archive_views') && function_exists('the_views')): ?>
			<span><i class="icon-eye-open"></i><?php the_views();  ?></span>
		<?php endif; ?>
		<?php if (cmp_get_option('archive_comments')) :?>
			<span><i class="icon-comment-alt"></i><?php comments_popup_link('0','1','%' ); ?></span>
		<?php endif; ?>
		<?php if (cmp_get_option('archive_tags')) :?>
			<span><i class="icon-tag"></i><?php the_tags('',''); ?></span>
		<?php endif; ?>
	</p>
<?php endif;
}
?>