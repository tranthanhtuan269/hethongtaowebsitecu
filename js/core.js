/*Fixed header*/
( function ( document, window, index )
{
  'use strict';
  var previousScroll = 0, headerOrgOffset = $('#header').height();
  $(window).scroll(function () {
    if($('.header-fixed').length){
      var currentScroll = $(this).scrollTop(),
          dheight = $(document).height(),
          able = dheight - bshow,
          bshow = 100;
        if($('#statistics').offset())
          bshow = $('#statistics').offset().top;
        if (currentScroll > headerOrgOffset) {
            if (currentScroll > previousScroll) {
              if(dheight - currentScroll < able)
                  $('#header').slideDown('fast');
              else
                  $('#header').css('position','fixed','background', 'linear-gradient(#a10000 50%,#780000 50%)');
                  $('#header').slideUp('fast');

            } else {
                $('#header').slideDown('fast');
            }
        } else {
                $('#header').slideDown('fast');
                $('#header').css('position','relative')
        }
        previousScroll = currentScroll;
    }
      
  });
}( document, window, 0 ));
/*Back to top*/
 $(function() {
    $(document).on('scroll', function() {
        if ($(window).scrollTop() > 50) {
            $('.back-to-top').addClass('show');
        } else {
            $('.back-to-top').removeClass('show');
        }
    });
    $('.back-to-top').on('click', scrollToTop);
});
function scrollToTop() {
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $('body');
    offset = element.offset();
    offsetTop = offset.top;
    $('html, body').animate({
        scrollTop: offsetTop
    }, 500, 'linear');
}
$('[data-toggle="tooltip"]').tooltip()

$('.bar').click(function() {
    $('body').toggleClass('nav-open');
    $('#wrapper').click(function() {
        $('body').removeClass('nav-open');
    });
});
$('section.background-image.full-height').each(function() {
    $(this).css('height', $(window).height());
});
$(window).resize(function() {
    $('section.background-image.full-height').each(function() {
        $(this).css('height', $(window).height());
    });
});
$('nav .dropdown > a').click(function() {
    return false;
});
$('nav .dropdown-submenu > a').click(function() {
    return false;
});
$('nav .dropdown').hover(function() {
    $(this).toggleClass('open');
});
$('nav .dropdown-submenu').hover(function() {
    $(this).toggleClass('open');
});

function fSelectChange(objValue) {
    //alert(objValue);
    if (objValue != '') {
        this.document.location.href = objValue;
    }
};
