(function($) {

  /**
   * Hides the ckeditor.
   */
  Drupal.behaviors.osWysiwygInlineCKEDITOR = {
    attach: function (context) {
      CKEDITOR.on("instanceReady", function(event) {
        var editor = event.editor;
        var instance = $('#cke_' + editor.name);
        var height = $(window).height();

        // Adjusting the height of the wysiwyg according to the window height.
        var editor_height = height / 2;

        if (editor_height > 550) {
          // The height can be more than 550.
          editor_height = 550;
        }

        instance.find('#cke_1_contents').height(editor_height);
      });
    }
  };

})(jQuery);
