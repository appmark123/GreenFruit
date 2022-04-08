<footer id="footer" class="row-fluid" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
  <?php if(is_home() && cmp_get_option('footer_links')): ?>
    <div class="span12 footer-link" >
     <p>友情链接</p>
     <ul>
       <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'foot-link', 'fallback_cb' => 'cmp_nav_fallback')); ?>
     </ul>
   </div>
 <?php endif; ?>
 <div class="span12 footer-nav">
   <ul>
     <?php if(function_exists('wp_nav_menu')) wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'foot-menu', 'fallback_cb' => 'cmp_nav_fallback')); ?>
   </ul>
 </div>

 </footer>
<?php wp_footer(); ?>
<div class="returnTop" title="返回顶部">
  <span class="s"></span>
  <span class="b"></span>
  返回顶部
</div>
</body>
</html>