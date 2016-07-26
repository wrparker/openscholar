/**
 * Hides the text format help when the wysiwyg is disabled
 */
Drupal.behaviors.osWysiwygHideTips = {
  attach: function (ctx) {
    // don't do this for every thing
    var $ = jQuery;
    function toggle (e) {
      $(e.currentTarget).parents('.text-format-wrapper').find('.filter-wrapper').toggle();
    }
    $('.filter-wrapper', ctx).hide();
    $('.wysiwyg-toggle-wrapper a', ctx).off('click', toggle).on('click', toggle);
  }
};

Drupal.behaviors.osWysiwygBrowserAutoSubmit = {
  attach: function (ctx) {
    var $ = jQuery;
    
    Drupal.media.popups.mediaStyleSelector.mediaBrowserOnLoad = function (e) {
      var doc = $(e.currentTarget.contentDocument);
      if ($('#edit-format option', doc).length == 1) {
        $('#edit-format', doc).parent('.form-item').hide();
      }
      
      if ($('#media-format-form fieldset#edit-options .fieldset-wrapper *:visible', doc).length == 0) {
        e.currentTarget.contentWindow.Drupal.media.formatForm.submit.apply($('#media-format-form a.fake-ok', doc)[0]);
      }
    };
  }
};
// Setting the url of paste from word plugin of tinymce editor to use site's base url instead of static asset domain.
(function($) {
  jQuery.each(tinymce.editors, function(key, editor) {
    editor.plugins.paste.url = Drupal.settings.paths.vsite_home + 'profiles/openscholar/libraries/tinymce/jscripts/tiny_mce/plugins/paste';
  });
})(jQuery);