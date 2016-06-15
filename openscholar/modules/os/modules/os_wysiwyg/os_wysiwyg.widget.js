(function($) {

  /**
   * Hides the ckeditor.
   */
  Drupal.behaviors.osWysiwygInlineCKEDITOR = {
    attach: function (context) {
      // Marking if the wysywig got bigger when we focused on the editor for the
      // first time.
      context.changed = false;

      CKEDITOR.on("instanceReady", function(event) {
        var editor = event.editor;

        editor.on('focus', function() {
          var instance = $('#cke_' + editor.name);

          if (!context.changed) {
            // Changing the size when focusing.
            instance.find('#cke_1_contents').height('550');
            context.changed = true;
          }
        });

      });
    }
  };

})(jQuery);
