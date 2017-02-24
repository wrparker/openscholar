/**
 * Enhances node path alias entry with automatic preview on forms.
 */
(function ($) {

// Stores behaviors as a property of Drupal.behaviors.
Drupal.behaviors.os_pages_css = {

attach: function (context, settings) {
  $(document).ready(function () {
  	console.log("Hello");
    var sourceSel = '#edit-field-os-css';
    var targetSel = '.field-os-css.os-javascript-load .fieldset-wrapper';
    $(sourceSel).appendTo(targetSel);
  });
}

}

}(jQuery));