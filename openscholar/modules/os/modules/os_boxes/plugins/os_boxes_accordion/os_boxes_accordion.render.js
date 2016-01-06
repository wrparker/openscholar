/**
 * Starts up accordion widgets according to their settings
 */
(function ($) {
  Drupal.behaviors.osBoxesAccordion = {
    attach: function (ctx) {
      $.each(Drupal.settings.os_boxes.accordion, function (delta, data) {
        $('#block-boxes-' + data.delta + ' .accordion', ctx).accordion({
          collapsible: true,
          heightStyle: 'content',
          active: data.active
        })
      });
    }
  }
})(jQuery);
