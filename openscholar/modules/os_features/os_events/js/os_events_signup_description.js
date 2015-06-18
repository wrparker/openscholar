(function ($) {

  Drupal.behaviors.osEventsSignupDescription = {

    attach: function () {
      var repeat = $('#edit-field-date-und-0-show-repeat-settings');
      var signup = $('#field-event-registration-add-more-wrapper');

      if (repeat.is(':checked')) {
        signup.find('.description').text(Drupal.t('If checked, users will be able to signup for every date in this repeating event.'));
      }
      
      repeat.change(function() {
        if ($(this).is(':checked')) {
          signup.find('.description').text(Drupal.t('If checked, users will be able to signup for every date in this repeating event.'));
        }
        else {
          signup.find('.description').text(Drupal.t('If checked, users will be able to signup for this event.'));
        }
      });
    }
  };

})(jQuery);

