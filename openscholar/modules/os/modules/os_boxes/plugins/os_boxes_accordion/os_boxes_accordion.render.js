/**
 * Starts up accordion widgets according to their settings
 */
(function ($) {
  Drupal.behaviors.osBoxesAccordion = {
    attach: function (ctx) {
      $.each(Drupal.settings.os_boxes.accordion, function (delta, data) {
        data.delta = delta > 0 ? data.delta + '--' + (parseInt(delta) + 1) : data.delta;
        $('#block-boxes-' + data.delta + ' .accordion', ctx).accordion({
          collapsible: true,
          heightStyle: 'content',
          active: data.active
        })
      });
    }
  }
})(jQuery);
