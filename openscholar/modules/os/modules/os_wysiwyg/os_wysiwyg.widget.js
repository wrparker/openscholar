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
        $('#cke_' + editor.name).find('.cke_reset_all').hide();

        editor.on('focus', function() {
          var instance = $('#cke_' + editor.name);
          instance.find('.cke_reset_all').show();

          if (!context.changed) {
            // Changing the size when focusing.
            instance.find('#cke_1_contents').height('550');
            context.changed = true;
          }
        });

        editor.on('insertElement', function(editor) {
        });

        editor.on('blur', function() {
          $('#cke_' + editor.name).find('.cke_reset_all').hide();
        });
      });
    }
  };

})(jQuery);
