(function ($) {
  /**
   * Display warning message when user goes to create new revision when node already has
   * the max number for that content type
   */
  Drupal.behaviors.osRepeatingEventChange = {
    attach: function () {
      if ($("input[name='max_revisions']").val()) {
          $max = $("input[name='max_revisions']").val();
          $current = $("input[name='revisions']").val();
          $("input#edit-revision").click(function () {
              if ($max && $current && $(this).prop('checked')) {
                  if ($current > $max) {
                      if (!confirm('This content already has the maximum number of revisions saved (' + $max + '). By creating a new revision, you will be permanently deleting the oldest revision.\n\nAre you sure you want to create a new revision?')) {
                          $(this).removeAttr('checked');
                      }
                  }
              }
          });
      }
    }
  }
})(jQuery);
