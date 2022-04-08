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
			<section class="span8 archive-list">
				<div class="widget-box" role="main" itemscope itemtype="http://schema.org/CollectionPage">
					<header id="archive-head">
						<h1 itemprop="headline">
							<?php echo __('Archive: ','cmp') .trim(wp_title('',0)); ?>
						</h1>
					</header>
					<?php
					query_posts($query_string . "&posts_per_page=".cmp_get_option('default_number'));
					get_template_part( 'loop', 'category' );  ?>
				</div>
			</section>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>