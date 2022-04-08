jQuery(document).ready(function($) {
	if($.browser.msie&&($.browser.version==6.0)&&!$.support.style){
		var ie_6 = '';
	}else if($('.sidebar-right .widget').length > 0){
		var rightWidth = $('.sidebar-right').width();
		var rightRollbox = $('.sidebar-right .widget'), rightRolllen = rightRollbox.length;
		if( 0<right_1<=rightRolllen && 0<right_2<=rightRolllen ){
			$(window).scroll(function(){
				var roll = document.documentElement.scrollTop+document.body.scrollTop;
				if( roll>rightRollbox.eq(rightRolllen-1).offset().top+rightRollbox.eq(rightRolllen-1).height() ){
					if( $('.rightRoller').length==0 ){
						rightRollbox.parent().append( '<div class="rightRoller"></div>' );
						rightRollbox.eq(right_1-1).clone().appendTo('.rightRoller');
						if( right_1!==right_2 )
							rightRollbox.eq(right_2-1).clone().appendTo('.rightRoller')
						if($(window).height() < 600 ){
							$('.rightRoller').css({position:'fixed',top:10,zIndex:0});
						}else{
							$('.rightRoller').css({position:'fixed',top:40,zIndex:0});
						}

						$('.rightRoller').width(rightWidth);
					}else{
						$('.rightRoller').fadeIn(300);
					}
				}else{
					$('.rightRoller').hide();
				}
			})
		};
	}
});