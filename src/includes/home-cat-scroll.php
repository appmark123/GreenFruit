<?php
function get_home_scroll( $cat_data ){ ?>

<?php

$Cat_ID = $cat_data['id'];
$Posts = $cat_data['number'];
$Box_Title = $cat_data['title'];

$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts);
?>
<section class="span12 scroll-box">
	<div class="widget-box">
		<div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
			<h2><?php echo $Box_Title ; ?></h2>
		</div>
		<div class="widget-content pic-list">
			<?php if($cat_query->have_posts()): ?>
				<ul class="cat-scroll">
					<?php while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
						<li>
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cmp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
								<?php cmp_post_thumbnail(250,330) ?>
							</a>
						</li>
					<?php endwhile;?>
				</ul>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
	</div>
</section>
<script type="text/javascript">
	jQuery(function() {
		jQuery('.cat-scroll').bxSlider({
			minSlides: 5,
			maxSlides: 5,
			slideWidth: 250,
			slideMargin: 30,
			auto: true,
			autoHover: true,
			autoDelay:2,
			pause: 6000,
			captions: false,
			controls: true,
			pager: false
		});
	});
</script>
<?php } ?>