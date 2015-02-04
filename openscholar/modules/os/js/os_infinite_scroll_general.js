/**
 * General handlers for all autopagers.
 */
(function ($) {

  var loadingAll = false,
    old_load;

  function load (current, next) {
    if (old_load) {
      old_load.call(this, current, next);
    }
    if (!next.page) {
      loadingAll = false;
    }
    if (loadingAll) {
      $.autopager.load();
    }
  }

  function loadAllClickHandler(e) {
    e.preventDefault();
    loadingAll = true;
    $.autopager.load(e);
  }

  Drupal.behaviors.osInfiniteScrollGeneral = {
    attach: function (ctx) {
      old_load = $.autopager.option('load');
      $.autopager.option('load', load);

      if (!$('.autopager-load-all').length) {
        $('<div class="autopager-load-all"><a>Load All</a></div>').appendTo('#main-content-header');
        $().appendTo('#main-content .view-content');
        $('#main-content .autopager-load-all').once('load-all-handler').click(loadAllClickHandler);
      }

      $(window).scroll(function(e) {
        if (!$('#autopager-load-more + .autopager-load-all').length) {
          $('<div class="autopager-load-all"><a>Load All</a></div>').insertAfter('#autopager-load-more');
        }
      });
    }
  }

})(jQuery);
