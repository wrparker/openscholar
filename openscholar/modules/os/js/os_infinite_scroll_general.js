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

  Drupal.behaviors.osInfiniteScrollGeneral = {
    attach: function (ctx) {
      old_load = $.autopager.option('load');
      $.autopager.option('load', load);

      if (!$('.autopager-load-all').length) {
        $('<div class="autopager-load-all"><a>Load All</a></div>').appendTo('#main-content-header');
        $('<div class="autopager-load-all"><a>Load All</a></div>').insertBefore('#main-content .item-list');
        $('.autopager-load-all').click(function (e) {
          e.preventDefault();
          loadingAll = true;
          $.autopager.load();
        });
      }
    }
  }

})(jQuery);
