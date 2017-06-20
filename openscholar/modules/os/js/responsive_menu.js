
(function ($) {

  /**
   * When the user is on a mobile device we need to adjust the UX menu.
   */
  Drupal.behaviors.RepsponsiveMenu = {
    attach: function (context) {

      // see https://stackoverflow.com/a/13819253/847651.
      var isMobile = {
        Android: function() {
          return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
          return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
          return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
          return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
          return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
      };

      // Keep track on the clicked links.
      var clicked = {};

      if (!isMobile.any()) {
        return;
      }

      // $('ul.nice-menu.nice-menu-down .menuparent a').bind('click dblclick mousedown mouseenter mouseleave', function(e){
      //   var event_id = e.type + "-" + this.toString();
      //   if (clicked[event_id] != undefined) {
      //     return;
      //   }
      //   clicked[event_id] = true;
      //   event.preventDefault();
      // });

      // works only on IOS, Android have some issues.
      $('ul.nice-menu.nice-menu-down .menuparent a').click(function(event) {
        if (clicked[this] != undefined) {
          return;
        }

        clicked[this] = true;
        event.preventDefault();
      });
    }
  };

})(jQuery);
