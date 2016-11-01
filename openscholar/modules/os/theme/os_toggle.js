/**
 * Pairs a link and a block of content. The link will toggle the appearance of
 * that block content
 */
 
(function ($) {
  Drupal.behaviors.os_toggle = {
    attach: function (ctx) {
      $('.toggle', ctx).once(function() {
        $(this, ctx).click(function(event) {
          event.preventDefault();

          $(this).toggleClass("expanded");
          var slider = null;

          $(this).add($(this).parents()).each(function () {
            var potentials = $(this).siblings('.os-slider');
            if (potentials.length) {
              slider = $(potentials[0]);
            }
          });

          if (navigator.appName == 'Microsoft Internet Explorer' && navigator.userAgent.match(/msie 6/i)) {
            // IE8 Does not work with the slider.
            if ($(this).hasClass('expanded')) {
              slider.show();
            }
            else {
              slider.hide();
            }
          }
          else {
            slider.slideToggle("fast");
          }
        });
      });
    }
  };

  Drupal.behaviors.os_showterms = {
  attach: function (ctx) {
  // Configure/customize these variables.
  var moretext = "And more...";
  var lesstext = "Hide terms";

    $('.more').each(function() {
      var content = $(this).html();
      var html = content + '&nbsp;&nbsp;<a  class="morelink">' + moretext + '</a>';
      $(this).html(html);
    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        //$(this).parent().prev(".more").toggle();
        $(this).prev(".morecontent").children("span").toggle();
        return false;
      });
    }
  };
})(jQuery);