<?php
//如果使用了WPUF，并且当前用户ID和作者ID一致，跳转到用户中心
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$current_user = wp_get_current_user();
if( function_exists('wpuf_autoload') && $current_user->ID == $author->ID){
	wp_redirect( home_url().'/user/posts' ); exit;
}
get_header(); ?>
<div id="main-content">
	<div id="content-header">
		<?php cmp_breadcrumbs();?>
	</div>
	<div class="container-fluid">
<?php //Top Banner
if(  empty( $get_meta["cmp_hide_top"][0] ) ){
	if( !empty( $get_meta["cmp_banner_top"][0] ) ) echo '<div class="row-fluid ggtop">' .htmlspecialchars_decode($get_meta["cmp_banner_top"][0]) .'</div>';
	else cmp_banner('banner_top' , '<div class="row-fluid ggtop">' , '</div>' );
}
?>
<div class="row-fluid">
	<div class="span12">
		<div class="widget-box user-center">
			<div id="user-left">
				<div class="user-avatar">
					<?php echo get_avatar( $author->user_email, 100 ).'<p>'.$author->nickname.'</p>'; ?>
					<?php if(class_exists("cartpaujPM")){
						echo '<p class="user-pm"><a href="'.get_home_url().'/user/pm?pmaction=newmessage&to='.$author->ID.'" ><i class="icon-pencil"></i> '.__('Send a message','cmp').'</a></p>';
					}
					?>
				</div>
				<ul id="user-menu">
					<li class="current-menu-item"><a href="<?php echo get_author_link( '', $author->ID ); ?>"><i class="icon-book"></i><?php _e('His/Her Posts','cmp') ?></a></li>
				</ul>
			</div>
			<div class="widget-content" id="user-right">
				<header id="archive-head">
					<h1 itemprop="headline">
						<?php echo sprintf(__("%s's Posts","cmp"), $author->nickname); ?>
						<?php if( cmp_get_option( 'author_rss' ) ): ?>
							<a class="rss-cat-icon" title="<?php _e( 'Subscribe to this author', 'cmp' ); ?>"  href="<?php echo get_author_feed_link( $author->ID ); ?>"><i class="icon-rss"></i></a>
						<?php endif; ?>
					</h1>
				</header>
				<?php //endif; ?>
				<?php
				if(count_user_posts( $author->ID ) != '0'){
					printf(__('<div class="post-count"> %s has published %s posts :</div>','cmp'), $author->nickname , count_user_posts( $author->ID ) ) ;
				}else{
					printf(__('<div class="post-count"> %s has not yet published any post, you can read the following wonderful posts : </div>','cmp'), $author->nickname ) ;
				}
				?>
				<ul>
					<?php if(have_posts()) : while ( have_posts() ) : the_post(); ?>
						<li class="archive-simple">
							<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></h2>
							<p class="post-meta">
								<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
								<?php if(function_exists('the_views')) : ?>
									<span><i class="icon-eye-open"></i><?php the_views(); ?></span>
								<?php endif; ?>
							</p>
						</li>
					<?php endwhile;
					else:
						$rand_posts = get_posts('numberposts=10&orderby=rand');  foreach( $rand_posts as $post ) :
					?>
					<li class="archive-simple">
						<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></h2>
						<p class="post-meta">
							<span><i class="icon-time"></i><?php if (function_exists('cmp_get_time')) cmp_get_time();?></span>
							<?php if(function_exists('the_views')) : ?>
								<span><i class="icon-eye-open"></i><?php the_views(); ?></span>
							<?php endif; ?>
						</p>
					</li>
				<?php endforeach; endif; ?>
			</ul>
			<?php if ($wp_query->max_num_pages > 1) cmp_pagenavi(); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>