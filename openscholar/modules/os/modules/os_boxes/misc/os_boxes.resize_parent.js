(function () {
  //if (typeof window.webWidgetsIframeHasRun != 'undefined') return;
  //window.webWidgetsIframeHasRun = true;

  // an exception is most likely caused by the DOM  not being ready
  if (window.addEventListener) {
    window.addEventListener('message', receiveMessage, false);
  }
  else if (window.attachEvent) {
    window.attachEvent('onmessage', receiveMessage);
  }

  function receiveMessage(e) {
    var data = JSON.parse(e.data);

    if (typeof data.url == 'undefined') return;

    var iframes = document.querySelectorAll('iframe[src="'+data.url+'"]');

    for (i=0; i<iframes.length; i++) {
      if (typeof data.width != 'undefined') {
        iframes[i].width = data.width;

        if (jQuery('.boxes-box-content :has(iframe[src="'+data.url+'"])')) {
          iframes[i].width = jQuery('.boxes-box-content :has(iframe[src="' + data.url + '"])').width();
        }

        if (jQuery('iframe[src="' + data.url+ '"]').contents().find('iframe')) {
          jQuery('iframe[src="' + data.url + '"]').contents().find('iframe').attr('width', iframes[i].width);
        }
      }
      if (typeof data.height != 'undefined') {
        iframes[i].height = data.height;
      }
    }
  }

})();