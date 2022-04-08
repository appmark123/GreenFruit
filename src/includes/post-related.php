<?php
global $get_meta , $post;

if( ( cmp_get_option('related') && empty( $get_meta["cmp_hide_related"][0] ) ) || ( isset( $get_meta["cmp_hide_related"][0] ) && $get_meta["cmp_hide_related"][0] == 'no' ) ):
	$related_no = cmp_get_option('related_number') ? cmp_get_option('related_number') : 4;

global $post;
$orig_post = $post;

$query_type = cmp_get_option('related_query') ;
if( $query_type == 'author' ){
	$args=array('post__not_in' => array($post->ID),'ignore_sticky_posts' => 1,'posts_per_page'=> $related_no , 'author'=> get_the_author_meta( 'ID' ));
}elseif( $query_type == 'tag' ){
	$tags = wp_get_post_tags($post->ID);
	$tags_ids = array();
	foreach($tags as $individual_tag) $tags_ids[] = $individual_tag->term_id;
	$args=array('post__not_in' => array($post->ID),'ignore_sticky_posts' => 1,'posts_per_page'=> $related_no , 'tag__in'=> $tags_ids );
}
else{
	$categories = get_the_category($post->ID);
	$category_ids = array();
	foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
	$args=array('post__not_in' => array($post->ID),'posts_per_page'=> $related_no , 'category__in'=> $category_ids );
}
$related_query = new wp_query( $args );
if( !$related_query->have_posts() ){ ?>
<section id="related-posts" class="widget-box">
		<h3><?php _e( 'Random Articles' , 'cmp' ); ?></h3>
	<div class="widget-content">
		<?php
		$args = array('post__not_in' => array($post->ID),'ignore_sticky_posts' => 1,'posts_per_page'=> $related_no ,'orderby' => 'rand');
		query_posts($args);
		while( have_posts() ) : the_post(); ?>
		<div class="related-item">
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<?php cmp_post_thumbnail(148,100) ?>
				</a>
			</div>
			<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<p class="post-meta"><?php cmp_get_time() ?></p>
		</div>
	<?php endwhile; wp_reset_query();
}elseif( $related_query->have_posts() ) { $count=0;?>
<section id="related-posts" class="widget-box">
		<h3><?php _e( 'Related Articles' , 'cmp' ); ?></h3>
	<div class="widget-content">
		<?php while ( $related_query->have_posts() ) : $related_query->the_post()?>
			<div class="related-item">
				<div class="post-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
						<?php cmp_post_thumbnail(160,100) ?>
					</a>
				</div><!-- post-thumbnail /-->
				<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				<p class="post-meta"><?php cmp_get_time() ?></p>
			</div>
		<?php endwhile;?>
		<?php }
		$post = $orig_post;
		wp_reset_query();?>
		<div class="clear"></div>
	</div>
</section>
<?php endif; ?>