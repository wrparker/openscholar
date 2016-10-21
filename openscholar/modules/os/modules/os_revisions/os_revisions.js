(function ($) {
  Drupal.behaviors.osRevisionsConfirms = {
    attach: function () {
      /**
       * Display warning message when user goes to create new revision when node already has
       * the max number for that content type
       */
      if ($("input[name='max_revisions']").val()) {
        $max = $("input[name='max_revisions']").val();
        $current = $("input[name='revisions']").val();
        $("input#edit-revision").click(function () {
          if ($max && $current && $(this).prop('checked')) {
            if ($current - $max >= 0) {
              $('#warning-text').html('<p>Are you sure you want to create a new revision?</p><p>This content already has the maximum number of revisions saved (' + $max + '). By creating a new revision, you will be permanently deleting the oldest revision. Learn how to manage your revisions on our <a href="#" target="_blank" rel="noopener"> documentation site</a>.</p>').show();
            }
          }
          else {
            $('#warning-text').hide();
          }
        });
      }
      /**
       * Display warning message when user clicks link to view revisions before any changes
       * that have been made have been saved
       */
      $editform = 0;
      $('form.node-form input,form.node-form select,form.node-form textarea').change(function() {
        $editform = 1;
      });
      if($('form.node-form').length) {
        CKEDITOR.on('instanceReady', function(readyEvent) {
          readyEvent.editor.on('blur', function(blurEvent) {
            if(blurEvent.editor.checkDirty()) {
              $editform = 1;
            }
          });
        });
      }
      $('fieldset#edit-revision-information a#revisions-link').click(function(clickEvent) {
        if($editform) {
          if (!confirm('Leaving this page will leave your changes unsaved.')) {
            clickEvent.preventDefault();
            return false;
          }
        }
      });
    }
  }
})(jQuery);
