<?php
add_action('widgets_init', 'qr_init');
function qr_init() {
    register_widget('qr_code');
}
class qr_code extends WP_Widget {
    function qr_code() {
        $widget_ops = array('classname' => 'qr-code' ,'description' => __( 'QR Code for homepage and single post.', 'cmp' ));
        $this->WP_Widget('qr_code',theme_name .__( ' - QR Code', 'cmp' ), $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title'] );
        $imgsize = empty($instance['imgsize'])? 150 : $instance['imgsize'];
        $error_level = empty($instance['error_level'])? L : $instance['error_level'];
        $margin = empty($instance['margin'])? 1 : $instance['margin'];
        $hide_title = $instance['hide_title'];
        $center = $instance['center'];
        $qr_cache = $instance['qr_cache'];
        if ($center)
            $center = 'align-center';
        else
            $center = '';
        if(is_single() || is_home() || is_front_page()) :
            if (is_single()) {
                $imgname = get_the_id();
                $current_url = wp_get_shortlink();
            }elseif (is_home() || is_front_page()) {
                $imgname = 'home';
                $current_url = home_url();
            }
            $localqr =  ABSPATH .'qrcode/'.$imgname.'-'.$imgsize.'.jpg';
            if ($qr_cache && function_exists('get_qr') && !file_exists($localqr)) {
                get_qr( "http://chart.googleapis.com/chart?cht=qr&chs=".$imgsize."x".$imgsize."&choe=UTF-8&chld=".$error_level."|".$margin."&chl=".$current_url,"qrcode", $imgname."-".$imgsize.".jpg");
            }
            echo $before_widget;
            if( !$hide_title ){
                echo $before_title;
                echo $title ;
                echo $after_title;
            }
            ?>
            <div class="qr-code <?php echo $center ?>">
                <?php
                if( $qr_cache && function_exists('get_qr') ){  ?>
                <img src="<?php echo home_url().'/qrcode/'.$imgname.'-'.$imgsize.'.jpg' ?>" width="<?php echo $imgsize ?>" height="<?php echo $imgsize ?>" alt="QR Code"/>
                <?php }else{ ?>
                <img src="<?php echo 'http://chart.googleapis.com/chart?cht=qr&chs='.$imgsize.'x'.$imgsize.'&choe=UTF-8&chld='.$error_level.'|'.$margin.'&chl='.$current_url ;?>" width="<?php echo $imgsize ?>" height="<?php echo $imgsize ?>" alt="QR Code"/>
                <?php
            }?>
        </div>
        <?php
        if( !$hide_title ) echo $after_widget;
        else echo '</div>';
    endif;//end of output!
}
function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['imgsize'] = strip_tags( $new_instance['imgsize'] );
    $instance['error_level'] = strip_tags( $new_instance['error_level'] );
    $instance['margin'] = strip_tags( $new_instance['margin'] );
    $instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
    $instance['center'] = strip_tags( $new_instance['center'] );
    $instance['qr_cache'] = strip_tags( $new_instance['qr_cache'] );
    return $instance;
}
function form($instance) {
    $defaults = array( 'title' =>__('QR Code' , 'cmp') , 'imgsize' => 150 );
    $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    <p><?php _e('This widget only display on homepage and single posts','cmp') ?>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title : ', 'cmp' ) ?></label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'imgsize' ); ?>"><?php _e('QR Code Size (default 150) : ', 'cmp' ) ?></label>
        <input id="<?php echo $this->get_field_id( 'imgsize' ); ?>" name="<?php echo $this->get_field_name( 'imgsize' ); ?>" value="<?php echo $instance['imgsize']; ?>" class="widefat" type="text" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'error_level' ); ?>"><?php _e('Error Correction Level (default L) : ', 'cmp' ) ?></label>
        <select  class="widefat" id="<?php echo $this->get_field_id('error_level'); ?>" name="<?php echo $this->get_field_name('error_level'); ?>">
            <option <?php if ( isset($instance['error_level']) && $instance['error_level'] == 'L') echo 'selected="SELECTED"'; else echo ''; ?>  value="L"><?php echo 'L' ;?></option>
            <option <?php if ( isset($instance['error_level']) && $instance['error_level'] == 'M') echo 'selected="SELECTED"'; else echo ''; ?>  value="M"><?php echo 'M' ;?></option>
            <option <?php if ( isset($instance['error_level']) && $instance['error_level'] == 'Q') echo 'selected="SELECTED"'; else echo ''; ?>  value="Q"><?php echo 'Q' ;?></option>
            <option <?php if ( isset($instance['error_level']) && $instance['error_level'] == 'H') echo 'selected="SELECTED"'; else echo ''; ?>  value="H"><?php echo 'H' ;?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'margin' ); ?>"><?php _e('Margin (default Version 1) : ', 'cmp' ) ?></label>
        <select  class="widefat" id="<?php echo $this->get_field_id('margin'); ?>" name="<?php echo $this->get_field_name('margin'); ?>">
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '1') echo 'selected="SELECTED"'; else echo ''; ?>  value="1"><?php echo '1' ;?></option>
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '2') echo 'selected="SELECTED"'; else echo ''; ?>  value="2"><?php echo '2' ;?></option>
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '3') echo 'selected="SELECTED"'; else echo ''; ?>  value="3"><?php echo '3' ;?></option>
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '4') echo 'selected="SELECTED"'; else echo ''; ?>  value="4"><?php echo '4' ;?></option>
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '10') echo 'selected="SELECTED"'; else echo ''; ?>  value="10"><?php echo '10' ;?></option>
            <option <?php if ( isset($instance['margin']) && $instance['margin'] == '40') echo 'selected="SELECTED"'; else echo ''; ?>  value="40"><?php echo '40' ;?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Widget Title :', 'cmp' ) ?></label>
        <input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" value="true" <?php if( @$instance['hide_title'] ) echo 'checked="checked"'; ?> type="checkbox" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'center' ); ?>"><?php _e('Center content :', 'cmp' ) ?></label>
        <input id="<?php echo $this->get_field_id( 'center' ); ?>" name="<?php echo $this->get_field_name( 'center' ); ?>" value="true" <?php if( @$instance['center'] ) echo 'checked="checked"'; ?> type="checkbox" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'qr_cache' ); ?>"><?php _e('Cache QR Code :', 'cmp' ) ?></label>
        <input id="<?php echo $this->get_field_id( 'qr_cache' ); ?>" name="<?php echo $this->get_field_name( 'qr_cache' ); ?>" value="true" <?php if( @$instance['qr_cache'] ) echo 'checked="checked"'; ?> type="checkbox" /><br/>
        <small><?php _e('To cache QR code, you must create a new folder named "qrcode" in the site\'s root directory, and make sure it is writable (0755).<br /><br />You can get more details from <a href="http://www.wpdaxue.com/add-qr-code-for-wordpress.html" target="_blank"> wpdaxue.com </a> & <a href="https://developers.google.com/chart/infographics/docs/qr_codes" target="_blank"> Google QR Code API </a>','cmp') ?>
        </small>
    </p>
    <?php
}
}
//???????????????
function get_qr($url,$path,$qrpic){
    set_time_limit (10); //??????????????????
    $destination_folder = $path?$path.'/':'';
    $localname = $destination_folder .$qrpic;
    $file = fopen ($url, "rb"); //fopen?????????r+??????: ?????????????????? ???????????????????????????
    if ($file) {
        $newf = fopen ($localname, "wb"); // w+,?????????????????? ??????????????????????????? ???????????????????????????????????????
        if ($newf)
            while(!feof($file)) {
                fwrite( $newf, fread($file, 1024 * 2 ), 1024 * 2 ); //????????????,fread???????????????????????????,?????????2M
            }
        }
        if ($file) {
        fclose($file);  //??????fopen???????????????
    }
    if ($newf) {
        fclose($newf);
    }
}
?>