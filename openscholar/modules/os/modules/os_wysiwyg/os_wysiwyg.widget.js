(function($) {

  /**
   * Hides the ckeditor.
   */
  Drupal.behaviors.osWysiwygInlineCKEDITOR = {
    attach: function (context) {

      CKEDITOR.on("instanceReady", function(event) {
        var editor = event.editor;
        $('#cke_' + editor.name).find('.cke_reset_all').hide();

        editor.on('focus', function() {
          $('#cke_' + editor.name).find('.cke_reset_all').show();
        });

        editor.on('blur', function() {
          $('#cke_' + editor.name).find('.cke_reset_all').hide();
        });

      });

    }
  };

})(jQuery);
