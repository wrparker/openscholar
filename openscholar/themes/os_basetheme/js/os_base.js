
// Fixes tab behavior after using the skip link in IE or Chrome
Drupal.behaviors.osBase_skipLinkFocus = {
  attach: function (ctx) {
    var $ = jQuery,
        $skip = $('#skip-link a', ctx);
    
    $skip.click(function (e) {
      var target = $skip[0].hash.replace('#', '');

      setTimeout (function () {
        $('a[name="'+target+'"]').attr('tabindex', -1).focus();
      }, 100);
    });
  }
};



