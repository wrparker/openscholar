(function ($) {

  /**
   * Redirect the user to the term page when he select it from the list.
   */
  Drupal.behaviors.submitOnChange = {
    attach: function () {
      $('.terms-list').change(function(e) {
        window.location = $(this).val();
      })
    }
  };

  /**
   * When filtering by term we need to select the term the user selected.
   */
  Drupal.behaviors.CheckSelectedTerm = {
    attach: function() {
      if (typeof Drupal.settings.fbt != 'undefined' && typeof Drupal.settings.fbt['vid'] != 'undefined') {
        $("select[name=terms_" + Drupal.settings.fbt['vid'] + "]").val(Drupal.settings.fbt['url']);
      }
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

  /**
   * Fix for old browser versions to support whitespace inside options tag.
   */
  Drupal.behaviors.taxonomyDropdownWhitespace = {
    attach: function () {
      $("select option").each(function(i,option){
        $option = $(option);
        $option.text($option.text().replace(/&nbsp;/g,'\u00A0'));
      });
    }
  };

  /**
   * Change all bundle checkboxes in Display terms under content is changed.
   */
  Drupal.behaviors.taxonomyShowTerms = {
    attach: function () {
      if ($('#edit-os-taxonomy-display-term-under-content').prop("checked")) {
        $('input:checkbox[id^="edit-os-taxonomy-display-term-under-content-"]').prop('disabled', false);
      } else {
        $('input:checkbox[id^="edit-os-taxonomy-display-term-under-content-"]').prop('disabled', true);
      }
      $('#edit-os-taxonomy-display-term-under-content').change(function () {
        if ($(this).prop("checked")) {
          $('input:checkbox[id^="edit-os-taxonomy-display-term-under-content-"]').prop('disabled', false);
        } else {
          $('input:checkbox[id^="edit-os-taxonomy-display-term-under-content-"]').prop('disabled', true);
          $('input:checkbox[id^="edit-os-taxonomy-display-term-under-content-"]').prop('checked', false);
        }
      });
    }
  };

})(jQuery);
