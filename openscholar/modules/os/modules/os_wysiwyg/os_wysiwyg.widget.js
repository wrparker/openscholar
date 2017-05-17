(function($) {

  /**
   * Hides the ckeditor.
   */
  Drupal.behaviors.osWysiwygInlineCKEDITOR = {
    attach: function (context) {
      CKEDITOR.on("instanceReady", function(event) {
        var editor = event.editor;
        var instance = $('#cke_' + editor.name);
        var editor_height = $('iframe').contents().find('body.cke_editable').height() + 25;

        // Adjusting the height of the wysiwyg according to the window height.
        // var editor_height = height / 2;

        if (editor_height < 100) {
          editor_height = 100;
        }

        if (editor_height > 550) {
          // The height can be more than 550.
          editor_height = 550;
        }

        instance.find('#cke_1_contents').height(editor_height);

        editor.on('doubleclick', function(event)  {
          if (event.data.element.is('a')) {
            event.data.dialog = null;
          }
        }, null, null, 100);

        editor.on('paste', function (event) {
          // Create a dummy element from scratch.
          var div = document.createElement('div');
          // Assign the pasted value as the text. Ths will help us search the font-*
          // attributes we want to remove.
          div.innerHTML = event.data.dataValue;

          var allTags = div.getElementsByTagName("*");

          // Start iterate over all the tags.
          // We are iterating over the tags and just find and replace using
          // regex because we might have html inside the body as a code example.
          for (var i = 0, len = allTags.length; i < len; i++) {
            var tag = allTags[i];

            for (var attributes_i = 0; attributes_i < tag.attributes.length; attributes_i++) {
              if (tag.attributes[attributes_i].nodeName != 'style') {
                // This is not a style attribute. Skipping.
                continue;
              }

              // Take the value of the style attributes and tare them apart.
              var attribute_values = tag.attributes[attributes_i].value.split(" ");
              for (var attribute_values_i = 0; attribute_values_i < attribute_values.length; attribute_values_i++) {

                if (
                  attribute_values[attribute_values_i].indexOf('font-size') != -1 ||
                  attribute_values[attribute_values_i].indexOf('font-family') != -1 ||
                  attribute_values[attribute_values_i].indexOf('font-weight') != -1 ||
                  attribute_values[attribute_values_i].indexOf('font-style') != -1
                ) {
                  // Not a value we want, remove.
                  delete attribute_values[attribute_values_i];
                }
              }

              if (attribute_values.join() == "") {
                tag.removeAttribute("style");
              }
              else {
                tag.setAttribute("style", attribute_values.join(''));
              }

            }
          }

          event.data.dataValue = div.innerHTML;
        });

        editor.on('selectionChange', function(event) {
          // Verify the selected content is a link or not. In case it's a link
          // replace the text the user selected with a jQuery selector.
          if (jQuery(this.getSelectedHtml().$).is('a')) {
            jQuery.selectLink = jQuery(this.getSelectedHtml().$);
          }
        });

        event.editor.document.on('mouseup', function()  {
          // Get the text that the user selected.
          jQuery.selectLink = this.getSelection().getSelectedText();
        });

        // Improve formatting issues for HTML code.
        var dtd = CKEDITOR.dtd;
        for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent)) {
          editor.dataProcessor.writer.setRules( e, {
            indent: true,
            breakBeforeOpen: true,
            breakAfterOpen: true,
            breakBeforeClose: true,
            breakAfterClose: true
          });
        }
      });
    }
  };

})(jQuery);
