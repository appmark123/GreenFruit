<?php
global $post;
$orig_post = $post;
$number = cmp_get_option( 'slider_number' );
$slider_query = cmp_get_option( 'slider_query' );
if( $slider_query == 'custom' ){
	$custom_slider_args = array( 'post_type' => 'cmp_slider', 'p' => cmp_get_option( 'slider_custom' ) );
	$custom_slider = new WP_Query( $custom_slider_args );
}else{
	if( $slider_query  == 'tag'){
		$tags = explode (' , ' , cmp_get_option('slider_tag'));
		foreach ($tags as $tag){
			$theTagId = get_term_by( 'name', $tag, 'post_tag' );
			if($fea_tags) $sep = ' , ';
			$fea_tags .=  $sep . $theTagId->slug;
		}
		$args= array('posts_per_page'=> $number , 'tag' => $fea_tags);
	}
	elseif( $slider_query  == 'category'){
		$args= array('posts_per_page'=> $number , 'category__in' => cmp_get_option('slider_cat'));
	}
	elseif( $slider_query  == 'post'){
		$posts = explode (',' , cmp_get_option('slider_posts'));
		$args= array('posts_per_page'=> $number , 'post_type' => 'post', 'post__in' => $posts,'ignore_sticky_posts' => 1,  );
	}
	elseif( $slider_query  == 'page'){
		$pages = explode (',' , cmp_get_option('slider_pages'));
		$args= array('posts_per_page'=> $number , 'post_type' => 'page', 'post__in' => $pages  );
	}
	elseif( $slider_query  == 'sticky'){
		$args= array('posts_per_page'=> $number ,'post__in'  => get_option( 'sticky_posts' ),'ignore_sticky_posts' => 1 );
	}
	$featured_query = new wp_query( $args );
}
if( cmp_get_option('images_number')  == '1'){
	$width = 930 ;
	$height = 330 ;
}elseif (cmp_get_option('images_number')  == '2') {
	$width = 455 ;
	$height = 330 ;
	$slideWidth = 455 ;
}else{
	$width = 296 ;
	$height = 330 ;
	$slideWidth = 296 ;
}
$imgs = cmp_get_option('images_number');
$mode = cmp_get_option( 'slider_mode' );
$auto = cmp_get_option( 'slider_auto' );
$autoHover = cmp_get_option( 'slider_autoHover' );
$pause = cmp_get_option( 'slider_pause' );
$captions = cmp_get_option( 'slider_captions' );
$controls = cmp_get_option( 'slider_controls' );
$pager = cmp_get_option( 'slider_pager' );
if( !$pause || $pause == ' ' || !is_numeric($pause))	$pause = 6000 ;
if( $mode == 'horizontal' ) $mode = 'horizontal';
elseif ($mode == 'vertical') $mode = 'vertical';
else $mode = 'fade';
if( $auto ) $auto = 'true';
else $auto = 'false';
if( $autoHover ) $autoHover= 'true';
else $autoHover = 'false';
if( $captions ) $captions= 'true';
else $captions = 'false';
if( $controls ) $controls= 'true';
else $controls = 'false';
if( $pager ) $pager= 'true';
else $pager = 'false';
?>
<?php if( $slider_query != 'custom' ): ?>
	<?php if( $featured_query->have_posts() ) : ?>
		<div class="row-fluid">
			<div class="span8">
				<div id="home-slider" class="widget-box">
					<div class="widget-content">
						<ul class="bxslider">
							<?php $i= 0;
							while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++; ?>
							<li>
								<a href="<?php the_permalink(); ?>">
									<?php if (cmp_get_option('timthumb') ) { ?>
									<img src="<?php echo get_template_directory_uri(); ?>/timthumb.php?src=<?php echo post_thumbnail_src(); ?>&h=<?php echo stripslashes($height); ?>&w=<?php echo stripslashes($width); ?>&zc=<?php echo stripslashes(cmp_get_option('thumb_zc')); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>" />
									<?php } else { ?>
									<img src="<?php echo post_thumbnail_src(); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>"/>
									<?php } ?>
								</a>
							</li>
						<?php endwhile;?>
					</ul>
				</div>
			</div>
		</div>
		<div class="span4 home-adpic">
			<div class="widget-box">
				<div class="widget-content">
					<?php //Right Banner
					if(  empty( $get_meta["cmp_hide_right"][0] ) ){
						if( !empty( $get_meta["cmp_banner_right"][0] ) ) echo '<div class="ggright">' .htmlspecialchars_decode($get_meta["cmp_banner_right"][0]) .'</div>';
						else cmp_banner('banner_right' , '<div class="ggright">' , '</div>' );
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif; wp_reset_query();?>
<?php else: ?>
	<div class="row-fluid">
		<div class="span8">
			<div id="home-slider" class="widget-box">
				<div class="widget-content">
					<ul class="bxslider">
						<?php $i= 0;
						while ( $custom_slider->have_posts() ) : $custom_slider->the_post(); $i++;
						$custom = get_post_custom($post->ID);
						$slider = unserialize( $custom["custom_slider"][0] );
						$number = count($slider);
						if( $slider ){
							foreach( $slider as $slide ): ?>
							<li>
								<?php if( !empty( $slide['link'] ) ):?><a href="<?php  echo stripslashes( $slide['link'] )  ?>"><?php endif; ?>
								<img src="<?php echo cmp_slider_img_src( $slide['id'] , $width , $height ) ?>" alt="thumb<?php echo $i; ?>"
								<?php if( !empty( $slide['title'] ) ):?>
									title="<?php  echo stripslashes( $slide['title'] )  ?>"
								<?php endif; ?>
								width="<?php echo stripslashes($width); ?>" height="<?php echo stripslashes($height); ?>"
								/>
								<?php if( !empty( $slide['link'] ) ):?></a><?php endif; ?>
							</li>
						<?php endforeach; ?>
						<?php
					}?>
				<?php endwhile; wp_reset_query();?>
			</ul>
		</div>
	</div>
</div>
<div class="span4 home-adpic">
	<div class="widget-box">
		<div class="widget-content">
			<?php //Right Banner
			if(  empty( $get_meta["cmp_hide_right"][0] ) ){
				if( !empty( $get_meta["cmp_banner_right"][0] ) ) echo '<div class="ggright">' .htmlspecialchars_decode($get_meta["cmp_banner_right"][0]) .'</div>';
				else cmp_banner('banner_right' , '<div class="ggright">' , '</div>' );
			}
			?>
		</div>
	</div>
</div>
</div>
<?php endif; ?>
<?php if( cmp_get_option('images_number')  == '1' ){ ?>
<script>
	jQuery(function() {
		jQuery('.bxslider').bxSlider({
			mode: '<?php echo $mode ?>',
			auto: <?php echo $auto ?>,
			autoHover: <?php echo $autoHover ?>,
			pause: <?php echo $pause ?>,
			pager: <?php echo $pager ?>,
			controls: <?php echo $controls ?>,
			captions: <?php echo $captions ?>
		});
	});
</script>
<?php } else { ?>
<script>
	jQuery(function() {
		jQuery('.bxslider').bxSlider({
			minSlides: <?php echo $imgs ?>,
			maxSlides: <?php echo $imgs ?>,
			slideWidth: <?php echo $slideWidth ?>,
			slideMargin: 20,
			auto: <?php echo $auto ?>,
			autoHover: <?php echo $autoHover ?>,
			pause: <?php echo $pause?>,
			captions: <?php echo $captions ?>,
			controls: <?php echo $controls ?>,
			pager: <?php echo $pager ?>
		});
	});
</script>
<?php } ?>