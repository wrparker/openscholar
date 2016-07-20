(function ($) {
  Drupal.behaviors.osSessionTimeout = {
    attach: function (context) {
      // Number of seconds from session timeout to display warning message.
      var display_warning_interval_before_timeout = 30;
      // Starting the timers to display warning message before 5 mins of session timeout and page refresh on session timeout.      
      Drupal.settings.session_timeout_warning_timer = setTimeout(displayTimeoutWarning, (Drupal.settings.os.session_expire_age - display_warning_interval_before_timeout) * 1000);
      Drupal.settings.session_timeout_refresh_timer = setTimeout(function(){window.location.reload(true);}, Drupal.settings.os.session_expire_age * 1000);
    }
  }
  // Callback to display session timeout warning message.
  function displayTimeoutWarning(){ 
    jQuery('#page').prepend('<div id="timeout-warning-wrapper" class="messages warning element-hidden"><div class="message-inner"><div class="message-wrapper">Warning: Your logged-in session will expire in <span id="session-timeout-timer" data-rooster-seconds=30></span> minutes. <a href="javascript:extend_os_session();" class="session-extend-link">Extend your session here.</div></div></div>');
    jQuery('#timeout-warning-wrapper').slideDown('slow');
    jQuery('#session-timeout-timer').rooster('start');
  }

})(jQuery);

// Ajax callback to regenerate Drupal session and extend session timeout.
function extend_os_session() {
  jQuery.get(Drupal.settings.basePath + 'extend_os_session', function(data) {
    // Hiding warning message div.
    jQuery('#timeout-warning-wrapper').slideUp('slow', function(){jQuery('#timeout-warning-wrapper').remove();});
    // Reseting the timers for warning message display and page refresh.
    clearTimeout(Drupal.settings.session_timeout_warning_timer);
    clearTimeout(Drupal.settings.session_timeout_refresh_timer);
    jQuery('#session-timeout-timer').rooster('stop');
    // Re-initializing the timers for another timeout cycle alter clicking on extend link.
    Drupal.behaviors.osSessionTimeout.attach();
  });
}