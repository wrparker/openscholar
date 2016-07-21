(function ($) {
  Drupal.behaviors.osSessionTimeout = {
    attach: function (context) {
      // On page load, saving the localStorage key values, according to these values, other tabs will update their clocks.
      localStorage.setItem('last_hit_timestamp', Drupal.settings.os.current_timestamp);
      localStorage.setItem('session_expire_timestamp', Drupal.settings.os.session_expire_timestamp);
      localStorage.setItem('warning_display_timestamp', Drupal.settings.os.warning_display_timestamp);
      // Starting the timer to determine when to display warning message and refresh the the after session timeout.
      // Every 1 sec interval, values of the above variables will be compared so that timing in all tabs are synced +/-3 secs 
      setInterval(checkSessionStatus, 1000);
    }
  }

  // Every 1 sec of interval, this function is called to determine the eligibilty of displaying timeout warning message and redirect user after session timeout.
  function checkSessionStatus() {
    // Obtaining values from browser local storage.
    last_hit_timestamp = localStorage.getItem('last_hit_timestamp');
    session_expire_timestamp = localStorage.getItem('session_expire_timestamp');
    warning_display_timestamp = localStorage.getItem('warning_display_timestamp');
    // Checking if any other tabs were opened/refreshed after the current page was opened, if yes then syncing current timestamp with the newly opened tab's load timestamp 
    if (last_hit_timestamp > Drupal.settings.os.current_timestamp) {
      Drupal.settings.os.current_timestamp = last_hit_timestamp;
    }
    // Incrementing timestamp counter by 1 sec.
    Drupal.settings.os.current_timestamp++;
    // Checking if current timestamp value meets the criteria to display warning message or not.
    if (Drupal.settings.os.current_timestamp == warning_display_timestamp) {
      displayTimeoutWarning();
    }
    // Hiding the warning message, if any other tabs are opened/refreshed/extend session link clicked on other tabs.
    if (Drupal.settings.os.current_timestamp < warning_display_timestamp) {
      // After displaying the warning, if another tab is refreshed, then hiding the warning msg.
      jQuery('#timeout-warning-wrapper').slideUp('slow', function(){jQuery('#timeout-warning-wrapper').remove();});
    }
    // If current timestamp reaches session expire timestamp, triggering ajax callback for session destroy and reloading the page.
    if (Drupal.settings.os.current_timestamp == session_expire_timestamp) {
      expireCurrentSession();
    }
  }

  // Callback to display session timeout warning message.
  function displayTimeoutWarning(){ 
    jQuery.ajax({
      url: Drupal.settings.basePath + 'check_os_session_status',
      type: 'get',
      dataType:'json',
      success: function(jData) {
        if (jData.show_warning == 1) {
          jQuery('#page').prepend('<div id="timeout-warning-wrapper" class="messages warning element-hidden"><div class="message-inner"><div class="message-wrapper">Warning: Your logged-in session will expire in <span id="session-timeout-timer" data-rooster-seconds=' + Drupal.settings.os.warning_interval_before_timeout + '></span> minutes. <a href="javascript:extend_os_session();" class="session-extend-link">Extend your session here.</div></div></div>');
          jQuery('#timeout-warning-wrapper').slideDown('slow');
          jQuery('#session-timeout-timer').rooster('start');
        }
      }
    });
  }

  // Callback to destroy drupal session via ajax and reloading the current page.
  function expireCurrentSession(){
    jQuery.get(Drupal.settings.basePath + 'os_session_destroy', function(data) {
      location.reload(true);
    });
  }

})(jQuery);

// Ajax callback to regenerate Drupal session and extend session timeout.
function extend_os_session() {
  jQuery.ajax({
    url: Drupal.settings.basePath + 'extend_os_session',
    type: 'get',
    dataType:'json',
    success: function(jData) {
      // Hiding warning message div.
      jQuery('#timeout-warning-wrapper').slideUp('slow', function(){jQuery('#timeout-warning-wrapper').remove();});
      localStorage.setItem('last_hit_timestamp', jData.current_timestamp);
      localStorage.setItem('session_expire_timestamp', jData.session_expire_timestamp);
      localStorage.setItem('warning_display_timestamp', jData.warning_display_timestamp);
    }
  });
}