/**
 * Adds help text when selecting the apply/remove VBO action in cp/content.
 */
(function ($) {
  Drupal.behaviors.cpContentShowHelp = {
    attach: function () {
      var operation_box = $("#edit-operation");
      var execute_button = $("input[value='Execute']");

      execute_button.after('<span id="terms-op-help">' + Drupal.t('Select the content below to which you would like to apply or remove a taxonomy term from.') + '</span>');

      var help_text = $("#terms-op-help");
      help_text.css('visibility', 'hidden');

      operation_box.on("change", function(e) {
        if (e.target.value == Drupal.t('action::cp_content_assign_taxonomy') || e.target.value == Drupal.t('action::cp_content_remove_taxonomy')) {
          help_text.css('visibility', 'visible');
        }
        else {
          help_text.css('visibility', 'hidden');
        }
      });
    }
  };
})(jQuery);
