<?php
function get_home_recent( $cat_data ){

	$post_type = $cat_data['post_type'];
	@$mode = implode('',$post_type);
	$exclude = @$cat_data['exclude'];
	$Posts = $cat_data['number'];
	$Box_Title = $cat_data['title'];
	$order = $cat_data['order'];
	$days = $cat_data['days'];

	if( $order == 'random') {
		$cat_query = new WP_Query(array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'orderby' => 'rand','category__not_in' => $exclude));
	}elseif( $order == 'latest'){
		$cat_query = new WP_Query(array ( 'ignore_sticky_posts' => 1, 'posts_per_page' => $Posts , 'post_type' => $post_type ,'category__not_in' => $exclude));
	}

	?>

	<section class="span4 home-recent">
		<div class="widget-box">
			<div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
				<h2><?php echo $Box_Title ; ?></h2>
			</div>
			<div class="widget-content">
				<ul class="news-list">

					<?php
					if( $order == 'most_comment'){
						if (function_exists('most_comm_posts')) most_comm_posts($mode, $Posts , $days , true);
					} elseif ($order == 'most_viewed') {
						if (function_exists('most_viewed_posts')) most_viewed_posts($mode, $Posts , $days , true);
					} else {

						if(@$cat_query->have_posts()): ?>

						<?php while ( $cat_query->have_posts() ) : $cat_query->the_post()?>

							<li><span><?php the_time('m-d') ?></span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><i class="icon-angle-right"></i><?php the_title(); ?></a></li>
						<?php endwhile;?>
					<?php endif;
				} ?>

			</ul>
		</div><!-- .widget-content /-->
	</div>
</section>
<?php
}
?>