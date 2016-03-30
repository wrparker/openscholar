(function ($) {

  Drupal.behaviors.osEventsSignupDescription = {

    attach: function () {
      var repeat = $('#edit-field-date-und-0-show-repeat-settings');
      var signup = $('#field-event-registration-add-more-wrapper');

      if (repeat.is(':checked')) {
        signup.find('.description').html(Drupal.t('If checked, users will be able to signup for every <b>future</b> date in this repeating event. Past repeating events are removed.'));
      }
      
      repeat.change(function() {
        if ($(this).is(':checked')) {
          signup.find('.description').html(Drupal.t('If checked, users will be able to signup for every <b>future</b> date in this repeating event. Past repeating events are removed.'));
        }
        else {
          signup.find('.description').text(Drupal.t('If checked, users will be able to signup for this event.'));
        }
      });
    }
  };

})(jQuery);

