(function($) {

  Drupal.behaviors.triggerEventPopup = {
    attach: function (ctx) {
      var dialogs = $('[id^=event-popover]');

      // Initialize and hide all dialogs.
      dialogs.dialog();
      dialogs.dialog('close');

      $('.view-item-os_events .views-field-title a').on('click', function(e) {
        e.preventDefault();
        var itemId = $(this).closest('div[data-item-id]').data('item-id');
        var popOver = $('#event-popover-' + itemId);
        var isOpen = popOver.dialog("isOpen");

        // Toggle dialog.
        if (isOpen) {
          popOver.dialog('close');
        }
        else {
          popOver.dialog({
            position: {my: 'bottom-10', at: 'center', of: e},
            draggable: false,
            closeOnEscape: true

          });
        }
      });
    }
  };
})(jQuery);
