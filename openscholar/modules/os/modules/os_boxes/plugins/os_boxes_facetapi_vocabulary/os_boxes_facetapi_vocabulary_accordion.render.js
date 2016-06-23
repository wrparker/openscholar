/**
 * Starts up accordion UI for faceted taxonomy vocabulary items
 */
(function ($) {
  Drupal.behaviors.osBoxesFacetedTaxonomyAccordion = {
    attach: function (ctx) {
      $.each(Drupal.settings.os_boxes.faceted_taxonomy, function (delta, data) {
        $('#boxes-box-' + data.delta + ' > .boxes-box-content > .accordion > .item-list').accordion({
          collapsible: true,
          heightStyle: 'content',
          active: true
        });
      });
    }
  }
})(jQuery);