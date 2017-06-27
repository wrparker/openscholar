
(function ($) {

  // Make sure our objects are defined.
  Drupal.CTools = Drupal.CTools || {};
  Drupal.CTools.Modal = Drupal.CTools.Modal || {};

  /**
   * Hide the modal. Overriding CTOOLS original functionality.
   */
  Drupal.CTools.Modal.dismiss = function() {
    var reload = false;
    if ($("#modal-content #nodeorder").html() != null) {
      // Setting a flag so we know that we need to reload the page.
      reload = true;
    }

    if (Drupal.CTools.Modal.modal) {
      Drupal.CTools.Modal.unmodalContent(Drupal.CTools.Modal.modal);
    }

    if (reload) {
      // Reloading the page after closing the sorting nodes modal.
      location.reload();
    }
  };


})(jQuery);
