
(function ($) {

  Drupal.behaviors.OsReaderOverlayRefresh = {
    attach: function (context, settings) {

      $(document).ajaxComplete(function() {
        $('.overlay-close').attr('overlay-close', true);
      });

      $('.overlay-close').click(function() {
        //if ($(this).attr('overlay-close')) {
          debugger;
          window.location.reload(true);
        //}
      });
    }
  };

})(jQuery);
