/**
 * General handlers for all autopagers.
 */
(function ($) {

  var loadingAll = false,
    old_load,
    behaviorRun = false;

  function load (current, next) {
    if (old_load) {
      old_load.call(this, current, next);
    }
    if (!next.page || next.page == NaN) {
      loadingAll = false;
      $('.autopager-load-all').remove();
    }
    if (loadingAll) {
      setTimeout($.autopager.load, 1);
    }
  }

  function loadAllClickHandler(e) {
    e.preventDefault();
    loadingAll = true;
    $.autopager.load(e);
  }

  Drupal.behaviors.osInfiniteScrollGeneral = {
    attach: function (ctx) {
      if (behaviorRun) return;

      if (load != $.autopager.option('load')) {
        old_load = $.autopager.option('load');
        $.autopager.option('load', load);
      }

      if (!$('.autopager-load-all').length) {
        $('<div class="autopager-load-all"><a>Load All</a></div>').appendTo('#main-content-header');
        $().appendTo('#main-content .view-content');
        $('#main-content .autopager-load-all', ctx).live('click', loadAllClickHandler);
      }

      $(window).scroll(function(e) {
        if (!$('#autopager-load-more + .autopager-load-all').length) {
          $('<div class="autopager-load-all"><a>Load All</a></div>').insertAfter('#autopager-load-more');
        }
      });
    }
  }

})(jQuery);
