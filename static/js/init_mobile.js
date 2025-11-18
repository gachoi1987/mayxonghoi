
$(document).ready(function() {
	$('body').append('<div id="loading_box">' + lang.process_request + '</div>');
	$('#loading_box').ajaxStart(function(){
		var loadingbox = $(this);
		var left = -(loadingbox.outerWidth() / 2);
		loadingbox.css({'marginRight': left + 'px'});
		loadingbox.delay(3000).fadeIn(400);
	});
	$('#loading_box').ajaxSuccess(function(){
		$(this).stop().stop().fadeOut(400);
	});

	$('.tip').tipsy({gravity:'s',fade:true,html:true});

	if ($('#page_goods').length){

        if ($('.properties').length) {
            $('.properties').Formiy();
            $('.properties dl').tipsy({gravity: 'e',fade: true,html:true});
            $('.properties label').tipsy({gravity: 's',fade: true,html:true});
        }
    }

    $('.gototop').click(function(){
        $('body,html').animate({scrollTop:0},300);
    });
    $('#_menu').click(function(){
        $('#_subnav, div.over').toggleClass('show');
        $('#_menu').toggleClass('actmenu');
    });
     $('#_menunews').click(function(){
        $('#_subnews, div.over').toggleClass('show');
        $('#_menunews').toggleClass('actmenu');
    });
    $('#_infoother').click(function(){
        $('#_subother').toggleClass('show');
    });

    $(".title_address").click(function(){
      $(".title_address").removeClass("current");
      $(this).addClass("current");

      $(".content_address").hide();
      $($(this).attr("title")).show();
    });

});

 function fixNav() {
    var $cache = $('header');
    if ($(window).scrollTop() > 180){
        $cache.css({'position': 'fixed', 'width': '100%', 'top': '22px','z-index': '10', 'left': '50%','transform': 'translate(-50%, -50%)';});
    }
    else{
        $cache.css({'position': 'relative','top': 'auto','left': '0','transform': 'translate(0, 0)'});
    }
}
$(window).scroll(fixNav);
fixNav();



$.fn.delayKeyup = function(callback, ms){
    var timer = 0;
    var el = $(this);
    $(this).keyup(function(){
    clearTimeout (timer);
    timer = setTimeout(function(){
        callback(el)
        }, ms);
    });
    return $(this);
};

$('#search-keyword').delayKeyup(function(el){
    var keywords = el.val();
    var show = $('#search-site .search-suggest');
    if(keywords.length >2){
        $.post(
            'ajax/search_suggest.php',
            {keywords: keywords},
            function(response){
                var res = $.evalJSON(response);
                show.css('display','block');
                show.html(res.content);
            },
            'text'
        );
    }
},1000);
