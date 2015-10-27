/**
 * Allows users to add posts to their manual lists without an additional 
 * page load on top of the ajax call
 */
(function ($) {

  Drupal.behaviors.os_manual_list = {
    attach : function (ctx) {
      $('#edit-add').click(function() {
        $('#edit-node-to-add').val();
      });
    }
  };

})(jQuery);
