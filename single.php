<?php get_header(); ?>
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
			<div class="span8">
				<?php if ( ! have_posts() ) : ?>
					<div class="widget-box">
						<article id="post-<?php the_ID(); ?>" class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
							<header class="entry-header">
								<h1 class="post-title" itemprop="headline"><?php _e( 'Not Found', 'cmp' ); ?></h1>
							</header>
							<div class="entry">
								<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'cmp' ); ?></p>
								<?php get_search_form(); ?>
							</div>
						</article>
					</div>
				<?php endif; ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="widget-box">
						<article id="post-<?php the_ID(); ?>" class="widget-content single-post" itemscope itemtype="http://schema.org/Article">
							<header id="post-header">
              <?php if (function_exists('wpfp_link')) { wpfp_link(); } ?>
								<h1 class="post-title" itemprop="headline"><?php the_title(); ?></h1>
                <div class="clear"></div>
								<?php cmp_include( 'post-meta' ); ?>
							</header>
							<div class="entry" itemprop="articleBody">
                <?php //Above Post Banner
                if(  empty( $get_meta["cmp_hide_above"][0] ) ){
                	if( !empty( $get_meta["cmp_banner_above"][0] ) ) echo '<div class="ggpost-above">' .htmlspecialchars_decode($get_meta["cmp_banner_above"][0]) .'</div>';
                	else cmp_banner('banner_above' , '<div class="ggpost-above">' , '</div>' );
                }
                ?>
                <?php the_content(); ?>
                <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'cmp' ), 'after' => '</div>' ) ); ?>
            </div>
              <footer class="entry-meta">
              <?php if (function_exists('wpfp_link')) { wpfp_link(); } ?>
                <?php if( cmp_get_option('share_post')) cmp_include( 'single-post-share' ); ?>
                <?php //Below Post Banner
                if( empty( $get_meta["cmp_hide_below"][0] ) ){
                  if( !empty( $get_meta["cmp_banner_below"][0] ) ) echo '<div class="ggpost-below">' .htmlspecialchars_decode($get_meta["cmp_banner_below"][0]) .'</div>';
                  else cmp_banner('banner_below' , '<div class="ggpost-below">' , '</div>' );
                }
                ?>
              	<?php if( cmp_get_option( 'post_tags' ) ) the_tags( '<p class="post-tag">'.__( 'Tagged with: ', 'cmp' )  ,' ', '</p>'); ?>

              		<?php if( ( cmp_get_option( 'post_authorbio' ) && empty( $get_meta["cmp_hide_author"][0] ) ) || ( isset( $get_meta["cmp_hide_related"][0] ) && $get_meta["cmp_hide_author"][0] == 'no' ) ): ?>

              			<div id="author-box">
              				<h3><span><?php _e( 'Last edited: ', 'cmp' ); echo get_the_modified_time('Y/n/j')?></span><?php _e( 'Post By', 'cmp' ) ?> <?php the_author() ?></h3>
              				<div class="author-info">
              					<?php cmp_author_box() ?>
              				</div>
              			</div>
              		<?php endif; ?>
              	</footer>
              	<?php if( cmp_get_option( 'post_nav' ) ): ?>
              		<div class="post-navigation">
              			<div class="post-previous"><?php previous_post_link( '%link', '<span>'. __( 'Previous:', 'cmp' ).'</span> %title' ); ?></div>
              			<div class="post-next"><?php next_post_link( '%link', '<span>'. __( 'Next:', 'cmp' ).'</span> %title' ); ?></div>
              		</div>
              	<?php endif; ?>
              </article>
          </div>
          <?php cmp_include( 'post-related' ); ?>

      <?php endwhile;?>


  </div>
  <?php get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>