/**
 * 
 */
Drupal.behaviors.osPublications = {
  attach: function (ctx) {
    // change the author category to the role when the form is submitted
    var $ = jQuery;
    $('.biblio-contributor-type .form-select', ctx).change(function (i) {
      var tar = $(this).parents('tr').find('.biblio-contributor-category input[type="hidden"]').not('.autocomplete');
      tar.val($(this).val());
    }).change();

    // Handle year fields.
    var codedYear = $("input[name='biblio_year_coded']");
    var yearField = $("#edit-biblio-year");

    // Add validation warning.
    var yearWarning = $("#biblio-year-group-validate");
    if (!yearField.hasClass('error')) {
      yearWarning.css('visibility', 'hidden');
    }
    yearWarning.css('color', 'red');

    // Allowed year input.
    var numbers = /^[0-9]+$/;

    // Check whether month publication field exists
    // It is visible only for Harvard - Chicago Author cititation
    if($("#edit-field-biblio-pub-month-und-0-value").length || $("#edit-field-biblio-pub-day-und-0-value").length) {
      // Handle month fields
      var monthField = $("#edit-field-biblio-pub-month-und-0-value");
      // Add validation warning.
      var monthWarning = $("#biblio-month-group-validate");
      if (!monthField.hasClass('error')) {
        monthWarning.css('visibility', 'hidden');
      }
      monthWarning.css('color', 'red');

      // Handle day fields
      var dayField = $("#edit-field-biblio-pub-day-und-0-value");
      // Add validation warning.
      var dayWarning = $("#biblio-day-group-validate");
      if (!dayField.hasClass('error')) {
        dayWarning.css('visibility', 'hidden');
      }
      dayWarning.css('color', 'red');

      // Publication year can be either given in a numerical value or by a coded
      // value ("in press", "submitted" and so on). If the user fills a numerical
      // value the radio buttons are unchecked and disabled. Clearing the numerical
      // value enables the radio buttons again.
      // Validation is checked for both month and day fields as well
      $("#edit-biblio-year, #edit-field-biblio-pub-month-und-0-value, #edit-field-biblio-pub-day-und-0-value").keyup(function() {
        if (this.value != '') {
          // Uncheck all radio buttons.
          codedYear.each(function () {
            $(this).prop('checked', false);
          });
          codedYear.prop("disabled", true);

          // Validate year input.
          var yearFieldVal = yearField.val();
          var monthFieldVal = monthField.val();
          var dayFieldVal = dayField.val();
          if (yearFieldVal.trim() != '' && (!yearFieldVal.match(numbers) || yearFieldVal.length != 4)) {
            yearWarning.css('visibility', 'visible');
            yearField.addClass("error");
          }
          else if (yearFieldVal.trim() == '' || (yearFieldVal.match(numbers) && yearFieldVal.length == 4)) {
            yearWarning.css('visibility', 'hidden');
            yearField.removeClass("error");
          }
          if (monthFieldVal.trim() != '' && (!monthFieldVal.match(numbers) || (monthFieldVal <=0 || monthFieldVal > 12))) {
            monthWarning.css('visibility', 'visible');
            monthField.addClass("error");
          }
          else if (monthFieldVal.trim() == '' || monthFieldVal.match(numbers) || (monthFieldVal > 0 && monthFieldVal <= 12)) {
            monthWarning.css('visibility', 'hidden');
            monthField.removeClass("error");
          }
          if (dayFieldVal.trim() != '' && (!dayFieldVal.match(numbers) || (dayFieldVal <=0 || dayFieldVal > 31))) {
            dayWarning.css('visibility', 'visible');
            dayField.addClass("error");
          }
          else if (dayFieldVal.trim() == '' || dayFieldVal.match(numbers) || (dayFieldVal > 0 && dayFieldVal <= 31)){
            dayWarning.css('visibility', 'hidden');
            dayField.removeClass("error");
          }
        }
        else {
          codedYear.prop("disabled", false);
          yearWarning.css('visibility', 'hidden');
          yearField.removeClass("error");
          monthWarning.css('visibility', 'hidden');
          monthField.removeClass("error");
          dayWarning.css('visibility', 'hidden');
          dayField.removeClass("error");
        }
      }).focus(function() {
        if ((yearFieldVal == '' || yearFieldVal == undefined || monthFieldVal == '' || monthFieldVal == undefined || dayFieldVal == '' || dayFieldVal == undefined) && (!yearField.hasClass('error') || !monthField.hasClass('error') || !dayField.hasClass('error'))) {
          codedYear.prop("disabled", false);
        }
        else {
          codedYear.prop("disabled", true);
        }
      });
      codedYear.change(function() {
        if (this.value != '') {
          // Empty year field.
          yearField[0].value = '';
          monthField[0].value = '';
          dayField[0].value = '';
        }
      });
    } else {
      // Publication year can be either given in a numerical value or by a coded
      // value ("in press", "submitted" and so on). If the user fills a numerical
      // value the radio buttons are unchecked and disabled. Clearing the numerical
      // value enables the radio buttons again.
      yearField.keyup(function() {
        if (this.value != '') {
          // Uncheck all radio buttons.
          codedYear.each(function () {
            $(this).prop('checked', false);
          });
          codedYear.prop("disabled", true);

          // Validate year input.
          userInput = this.value;
          if ((userInput.length != 4 && userInput.match(numbers)) || !userInput.match(numbers)) {
            yearWarning.css('visibility', 'visible');
            yearField.addClass("error");
          }
          else if (userInput.length == 4 && userInput.match(numbers)){
            yearWarning.css('visibility', 'hidden');
            yearField.removeClass("error");
          }
        }
        else {
          codedYear.prop("disabled", false);
          yearWarning.css('visibility', 'hidden');
          yearField.removeClass("error");
        }
      }).focus(function() {
        if ((yearField.value == '' || yearField.value == undefined) && !yearField.hasClass('error') ) {
          codedYear.prop("disabled", false);
        }
        else {
          codedYear.prop("disabled", true);
        }
      });
      codedYear.change(function() {
        if (this.value != '') {
          // Empty year field.
          yearField[0].value = '';
        }
      });
    }
  }
};

(function ($) {
  // Override pathauto.js implementation to change summaries of the path field.
  Drupal.behaviors.pathFieldsetSummaries = {
    attach: function (context) {
      $('fieldset.path-form', context).drupalSetSummary(function (context) {
        var path = $('.form-item-path-alias input').val();
        var automatic = $('.form-item-path-pathauto input').attr('checked');

        if (automatic) {
          return Drupal.t('Automatic URL');
        }
        if (path) {
          return Drupal.t('URL: @alias', { '@alias': path });
        }
        else {
          return Drupal.t('No URL');
        }
      });
    }
  };

  /**
   * Override pathauto.js implementation to change summaries of the path field.
   */
  Drupal.behaviors.stipTagsFromTitleOnPaste = {
    attach: function () {
      Drupal.textPasted = false;

      tinyMCE.onAddEditor.add(function(mgr, editor) {

        if (editor.id != 'edit-title-field-und-0-value') {
          return;
        }

        editor.onKeyDown.add(function(editor, event) {

          if (!(event.keyCode == 86 && event.metaKey)) {
            return;
          }

          // A text was pasted to the wysiwyg. Notify other events.
          Drupal.textPasted = true;
        });

        editor.onChange.add(function(editor, content) {
          if (!Drupal.textPasted) {
            return;
          }

          // A text was pasted. Trim non-allowed tags from the editor.
          editor.setContent(strip_tags(content, '<i><sub><sup>'));

          // Set back the false.
          Drupal.textPasted = false;
        });
      });

      // Stripping text from tags. Taken from phpjs library.
      function strip_tags(input, allowed) {
        allowed = (((allowed || '') + '')
          .toLowerCase()
          .match(/<[a-z][a-z0-9]*>/g) || [])
          .join('');
        // making sure the allowed arg is a string containing only tags in
        // lowercase (<a><b><c>).
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return input.content.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
          return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
      }
    }
  };

  /**
   * Behavior for Year of Publication radio button field.
   */
  Drupal.behaviors.yearFieldDisplay = {
    attach: function () {
      var target_extrayear = $('.form-item-biblio-year-coded-extrayear');
      var target_date_published = $('.form-item-biblio-date');
      // In edit mode, if one radio button is selected.
      if ($('input[name="biblio_year_coded"]:checked').length > 0) {
        var selectedOption = $.trim($('input[name="biblio_year_coded"]:checked').next().html());
        if (selectedOption == 'Forthcoming' || selectedOption == 'Submitted') {
          target_extrayear.hide();
          target_date_published.show();
        } else {
          target_extrayear.css({position:'relative', left: $('input[name="biblio_year_coded"]:checked').position().left + 'px', top:'-20px'});
          target_extrayear.show();
          target_date_published.hide();
        }
      } else {
        target_extrayear.hide();
      }
      // The onchange event handling for 'biblio_year_coded' radio buttons.
      $('input[name="biblio_year_coded"]').change(function(){
        var selectedOption = $.trim($(this).next().html());
        if (selectedOption == 'Forthcoming' || selectedOption == 'Submitted') {
          target_extrayear.hide();
          target_date_published.show();
        } else {
          target_extrayear.css({position:'relative', left:$(this).position().left + 'px', top:'-20px'});
          target_extrayear.show();
          target_date_published.hide();
        }
      });
    }
  };

})(jQuery);
