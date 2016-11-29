(function($) {
  /**
   * Provides events to change display of select current user/create new user in
   * vsite registration form.
   */
  Drupal.behaviors.os_help = { 

    /**
     * Attaches a click event to call toggle_user_forms.  Tries to do this only once by inspecting $elem.data.  Ajax calls
     * will trigger attach again, causing multiple instances of the same click event to be registered if this isn't done.
     */
    attach: function(context, settings) {
      // Ensures that focus is not on the "domain" element to the avoid AJAX errors.
      if (typeof GSFN !== "undefined") { GSFN.loadWidget($id,{"containerId":"getsat-widget-" . $id}); }
      // Avoids setting up the hidden element on every page load.
    }
  };
})(jQuery);