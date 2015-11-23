
(function ($) {

  Drupal.behaviors.OsReaderOverlayRefresh = {
    attach: function (context, settings) {

      $(document).ajaxComplete(function() {
        window.localStorage.setItem('refresh_page', true);
      });
    }
  };

})(jQuery);
