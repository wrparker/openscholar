(function ($) {

  /**
   * Format repeating events.
   */
  Drupal.behaviors.OsEventsRepeatingDatesFormatting = {
    attach: function () {
      var days = [];
      var up;
      $('#main-content .field-type-datetime .field-items .field-item').each(function(index) {
        if (index == 0) {
          up = $(this).text();
          return -1;
        }

        var text = $(this).text();
        var split = text.split(', ');
        var day = split[0];

        if (days[day] === undefined) {
          days[day] = [];
        }

        days[day].push(text.replace(day + ', ', ''));
      });
      var html;

      html = up;

      for (var day in days) {
        html += '<div class="days-' + day + '-wrapper" instance_delta=0><strong>' + day + '</strong>: ';
        //html += '<a href="#" class="days-' + days[day] + '-prev hide"> << </a>';
        for (var instance in days[day]) {
          if (instance == 0) {
            html += days[day][instance];
          }
        }
        html += '<a href="#" class="days-' + day + '-next"> >> </a>';
        html += '</div>';
      }

      $('#main-content .field-type-datetime .field-items').html(html);
    }
  };

})(jQuery);
