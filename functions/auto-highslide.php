<?php
/* Add HighSlide Image Code */
add_filter('the_content', 'addhighslideclass_replace');
function addhighslideclass_replace ($content)
{   global $post;
    $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 class="highslide-image" onclick="return hs.expand(this);"$6>$7</a>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
/* Add HighSlide */
add_action( 'wp_enqueue_scripts', 'cmp_highslide' );
function cmp_highslide() {
  wp_register_script( 'highslide', get_template_directory_uri() . '/js/highslide.js', array(), '1.1' );
  wp_register_style( 'highslide', get_template_directory_uri() . '/css/highslide.css','','1.1' );
  if(is_single() || is_page()){
    wp_enqueue_script( 'highslide' );
    wp_enqueue_style( 'highslide' );
  }
}
function highslide_head() {
    if(is_single() || is_page()){
    print('
<script>
jQuery(document).ready(function($) {
    $(".entry img").each(function(i){
        _self = $(this);
        if (! this.parentNode.href) {
            imgsrc = "";
            if (_self.attr("data-original")) {
                imgsrc = _self.attr("data-original");
            } else {
                imgsrc = _self.attr("src");
            }
            $(this).wrap("<a href=\'"+imgsrc+"\' class=\'highslide-image\' onclick=\'return hs.expand(this);\'></a>");
        }
    });
    hs.graphicsDir = "'.get_template_directory_uri().'/images/highslide/";
    hs.outlineType = "rounded-white";
    hs.dimmingOpacity = 0.8;
    hs.outlineWhileAnimating = true;
    hs.showCredits = false;
    hs.captionEval = "this.thumb.alt";
    hs.numberPosition = "caption";
    hs.align = "center";
    hs.transitions = ["expand", "crossfade"];
    hs.addSlideshow({
        interval: 5000,
        repeat: true,
        useControls: true,
        fixedControls: "fit",
        overlayOptions: {
            opacity: 0.75,
            position: "bottom center",
            hideOnMouseOut: true
        }
    });
});
</script>
    ');
    }
}
add_action('wp_head', 'highslide_head');
?>