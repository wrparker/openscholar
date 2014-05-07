(function ($) {

  Drupal.behaviors.submitOnChange = {
    attach: function () {
      $('.terms-list').change(function(e) {
        $(this).closest('form').trigger('submit')
      })
    }
  };

  /**
   * Expand Tree term reference widget.
   */
  Drupal.behaviors.treeTaxonomyReference = {
    attach: function () {
      // Expand link.
      $('.field-name-og-vocabulary .toggle-wrapper a.expand').click(function(event) {
        $(this).parents('.item-list').next('.form-type-checkbox-tree').find('.term-reference-tree-button.term-reference-tree-collapsed').trigger('click');
        event.preventDefault();
      });

      // Collapse link.
      $('.field-name-og-vocabulary .toggle-wrapper a.collapse').click(function(event) {
        $(this).parents('.item-list').next('.form-type-checkbox-tree').find('.term-reference-tree-button:not(.term-reference-tree-collapsed)').trigger('click');
        event.preventDefault();
      })
    }
  };

})(jQuery);
