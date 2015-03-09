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
    }
    if (loadingAll) {
      setTimeout($.autopager.load, 1);
    }
  }

  function loadAllClickHandler(e) {
    e.preventDefault();
    loadingAll = true;
    $.autopager.load(e);

    var img_path = Drupal.settings.views_infinite_scroll[0].img_path,
        img = '<div id="views_infinite_scroll-ajax-loader"><img src="' + img_path + '" alt="loading..."/></div>';

    $('#main-content-header .autopager-load-all').replaceWith(img);
    $('.autopager-load-all').remove();
  }

  Drupal.behaviors.osInfiniteScrollGeneral = {
    attach: function (ctx) {
      if (behaviorRun) return;
      behaviorRun = true;

      // do nothing if there's no pager link to the next page
      if ($($.autopager.option('link')).length == 0) {
        return;
      }

      if (load != $.autopager.option('load')) {
        old_load = $.autopager.option('load');
        $.autopager.option('load', load);
      }

      if (!$('.autopager-load-all').length) {
        $('<div class="autopager-load-all"><a>Load All</a></div>').appendTo('#main-content-header');
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
