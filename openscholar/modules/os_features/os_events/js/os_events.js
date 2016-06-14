(function ($) {

  /**
   * Fit multiple events to single day in the calendar.
   */
  Drupal.behaviors.osEventsSideBySideEvents = {

    attach: function () {
      var eventsContainer = $(".calendar-agenda-items.single-day .calendar.item-wrapper .inner");

      // Loop over the containers of the events.
      eventsContainer.each(function () {

        $(this).each(function() {
          var eventsNumber = $(this).children().length;

          if (eventsNumber <= 1) {
            // No need for the side by side events when there is a single event.
            return;
          }

          var widthPerEvent = 100 / eventsNumber;

          $(this).children().each(function() {
            // Set a responsive width per event.
            var eventItem = $(this).find('.view-item-os_events');
            eventItem.css('width', widthPerEvent + '%');

            // Hiding the date of the event.
            var eventDescription = eventItem.find('.views-field-field-date').hide();

            // Attaching the date of the event to the title of the link.
            eventItem.find('.views-field-colorbox a').attr('title', $.trim(eventDescription.text()));
          });
        });
      });
    }
  };

  /**
   * Update the End Date field according to the Start Date field.
   */
  Drupal.behaviors.osEventsUpdateEndDate = {
    attach: function () {

      $('div.start-date-wrapper:not(.start-date-processed)').addClass('start-date-processed').find('input[id*=datepicker]').change(function() {
        $(this).parents('div.fieldset-wrapper').find('div.end-date-wrapper').find('input[id*=datepicker]').val($(this).val());
      });

    }
  };

  /**
   * Populate the end time of the scheduling close date.
   */
  Drupal.behaviors.osPopulateEndDate = {
    attach: function () {
      $("#edit-scheduling-open-datepicker-popup-0").change(function() {
        if ($("#edit-scheduling-open-timeEntry-popup-1").val() == "") {
          $("#edit-scheduling-open-timeEntry-popup-1").val('12:01 AM');
        }
      });

      $("#edit-scheduling-close-datepicker-popup-0").change(function() {
        if ($("#edit-scheduling-close-timeEntry-popup-1").val() == "") {
          $("#edit-scheduling-close-timeEntry-popup-1").val('11:59 PM');
        }
      });
    }
  }

  /**
   * Display warning message under repeat checkbox if any field other than location / date is changed for repeating events.
   */
  Drupal.behaviors.osRepeatingEventChange = {
    attach: function () {
      // Warning will be hidden if repeat checkbox is unchecked.
      $('#edit-field-date-und-0-show-repeat-settings').change(function(){
        if (!$('#edit-field-date-und-0-show-repeat-settings:checked').length) {
          $('#event-change-notify').addClass('element-hidden');
        }
      });
      // Any changes other than start date time and location field will display the warning message for repeating event edit page
      $('.field-name-field-date input[type="radio"], .field-name-field-date input[type="text"], .field-name-field-date input[type="checkbox"], .field-name-field-date select, #edit-field-date-und-0-rrule-until-child-datetime-datepicker-popup-0').not('#edit-field-date-und-0-show-repeat-settings, #edit-field-date-und-0-value-datepicker-popup-0, #edit-field-date-und-0-value-timeEntry-popup-1').change(function(){
        if ($('#edit-field-date-und-0-show-repeat-settings:checked').length && !$('body').hasClass('page-node-add-event')) {
          $('#event-change-notify').removeClass('element-hidden');
        }
      });
      $('.field-name-field-date input[type="text"]').not('#edit-field-date-und-0-value-datepicker-popup-0, #edit-field-date-und-0-value-timeEntry-popup-1').keydown(function(){
        if ($('#edit-field-date-und-0-show-repeat-settings:checked').length && !$('body').hasClass('page-node-add-event')) {
          $('#event-change-notify').removeClass('element-hidden');
        }
      });
    }
  }

})(jQuery);
