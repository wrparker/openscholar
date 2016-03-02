(function($) {


/**
 * Hides the ckeditor.
 */
Drupal.behaviors.osWysiwygHideTips = {
  attach: function (ctx) {
    localStorage.osWysiwygExpandableTextarea = localStorage.osWysiwygExpandableTextarea || JSON.stringify({});
    var settings = JSON.parse(localStorage.osWysiwygExpandableTextarea);
    //for(name in CKEDITOR.instances) {
    //
    //  CKEDITOR.instances[name].destroy(true);
    //}

    //CKEDITOR.disableAutoInline = true;
    CKEDITOR.inline('edit-body-und-0-value');
  }
};



  ///**
    // * Initialize editor libraries.
    // *
    // * Some editors need to be initialized before the DOM is fully loaded. The
    // * init hook gives them a chance to do so.
    // */
    //Drupal.wysiwygInit = function() {
    //  // This breaks in Konqueror. Prevent it from running.
    //  if (/KDE/.test(navigator.vendor)) {
    //    return;
    //  }
    //  jQuery.each(Drupal.wysiwyg.editor.init, function(editor) {
    //    // Clone, so original settings are not overwritten.
    //    this(jQuery.extend(true, {}, Drupal.settings.wysiwyg.configs[editor]));
    //  });
    //};
    //
    ///**
    // * Attach editors to input formats and target elements (f.e. textareas).
    // *
    // * This behavior searches for input format selectors and formatting guidelines
    // * that have been preprocessed by Wysiwyg API. All CSS classes of those elements
    // * with the prefix 'wysiwyg-' are parsed into input format parameters, defining
    // * the input format, configured editor, target element id, and variable other
    // * properties, which are passed to the attach/detach hooks of the corresponding
    // * editor.
    // *
    // * Furthermore, an "enable/disable rich-text" toggle link is added after the
    // * target element to allow users to alter its contents in plain text.
    // *
    // * This is executed once, while editor attach/detach hooks can be invoked
    // * multiple times.
    // *
    // * @param context
    // *   A DOM element, supplied by Drupal.attachBehaviors().
    // */
    //Drupal.behaviors.attachWysiwyg = {
    //  attach: function (context, settings) {
    //    // This breaks in Konqueror. Prevent it from running.
    //    if (/KDE/.test(navigator.vendor)) {
    //      return;
    //    }
    //
    //    $('.wysiwyg', context).once('wysiwyg', function () {
    //      if (!this.id || typeof Drupal.settings.wysiwyg.triggers[this.id] === 'undefined') {
    //        return;
    //      }
    //      var $this = $(this);
    //      var params = Drupal.settings.wysiwyg.triggers[this.id];
    //      for (var format in params) {
    //        params[format].format = format;
    //        params[format].trigger = this.id;
    //        params[format].field = params.field;
    //      }
    //      var format = 'format' + this.value;
    //      // Directly attach this editor, if the input format is enabled or there is
    //      // only one input format at all.
    //      if ($this.is(':input')) {
    //        Drupal.wysiwygAttach(context, params[format]);
    //      }
    //      // Attach onChange handlers to input format selector elements.
    //      if ($this.is('select')) {
    //        $this.change(function() {
    //          // If not disabled, detach the current and attach a new editor.
    //          Drupal.wysiwygDetach(context, params[format]);
    //          format = 'format' + this.value;
    //          Drupal.wysiwygAttach(context, params[format]);
    //        });
    //      }
    //      // Detach any editor when the containing form is submitted.
    //      $('#' + params.field).parents('form').submit(function (event) {
    //        // Do not detach if the event was cancelled.
    //        if (event.isDefaultPrevented()) {
    //          return;
    //        }
    //        Drupal.wysiwygDetach(context, params[format], 'serialize');
    //      });
    //    });
    //  },
    //
    //  detach: function (context, settings, trigger) {
    //    var wysiwygs;
    //    // The 'serialize' trigger indicates that we should simply update the
    //    // underlying element with the new text, without destroying the editor.
    //    if (trigger == 'serialize') {
    //      // Removing the wysiwyg-processed class guarantees that the editor will
    //      // be reattached. Only do this if we're planning to destroy the editor.
    //      wysiwygs = $('.wysiwyg-processed', context);
    //    }
    //    else {
    //      wysiwygs = $('.wysiwyg', context).removeOnce('wysiwyg');
    //    }
    //    wysiwygs.each(function () {
    //      var params = Drupal.settings.wysiwyg.triggers[this.id];
    //      Drupal.wysiwygDetach(context, params, trigger);
    //    });
    //  }
    //};


  // ########################################################################################


  //localStorage.osWysiwygExpandableTextarea = localStorage.osWysiwygExpandableTextarea || JSON.stringify({});
  //var settings = JSON.parse(localStorage.osWysiwygExpandableTextarea);

  //function wysiwyg_expand(e) {
  //  var parent;
  //  if (typeof e == 'undefined') { //wtf IE
  //    // only happens in IE when we click on the body element
  //    // IE doesn't pass an event object in when the event handler is assigned using onclick attribute
  //    e = this.document.parentWindow.event;
  //    e.currentTarget = e.srcElement;
  //    e.currentTarget.ownerDocument.defaultView = e.currentTarget.ownerDocument.parentWindow;
  //  }
  //  if (e.currentTarget.nodeName == 'BODY') {
  //    if (e.currentTarget.ownerDocument.defaultView.name.indexOf('_ifr') != -1) {
  //      parent = $('#'+e.currentTarget.ownerDocument.defaultView.name.replace('_ifr', '')).parents('.form-item');
  //    }
  //    else {
  //      var id = $(e.currentTarget).attr('onload').match(/'[\w\d-]+'/);
  //      id = id[0].slice(1, -1);
  //      parent = $('#'+id).parents('.form-item');
  //    }
  //  }
  //  else {
  //    parent = $(e.currentTarget).parents('.form-item');
  //  }
  //  var editor = parent.find('.mceEditor table.mceLayout'),
  //    dim = parent.find('[data-maxrows]'),
  //    height = (parseInt(dim.attr('data-maxrows')) * 25);
  //
  //  if (typeof settings[editor.attr('id')] != 'undefined') {
  //    height = settings[editor.attr('id')].height;
  //  }
  //
  //  editor.removeClass('os-wysiwyg-collapsed');
  //  parent.find('.wysiwyg-toggle-wrapper').show();
  //  $('iframe', editor).stop().animate({height: height+'px'}, 600);
  //  editor.children('tbody').children('tr.mceFirst, tr.mceLast').animate({opacity: 1.0}, 600);
  //}

  //function wysiwyg_minimize() {
  //  if (arguments.length) {
  //    var e = arguments[0],
  //      target = e.target || e.srcElement,
  //      target_id = $(target).parents('.mceEditor').find('table.mceLayout').attr('id');
  //  }
  //  $('.mceEditor table.mceLayout').not('.os-wysiwyg-collapsed').each(function () {
  //    var editor = $(this),
  //      parent = editor.parents('.form-item'),
  //      iframe = $('iframe', editor);
  //
  //    if (!editor.hasClass('os-wysiwyg-collapsed')) {
  //      settings[this.id] = {
  //        height: iframe.height()
  //      };
  //      localStorage.osWysiwygExpandableTextarea = JSON.stringify(settings);
  //    }
  //
  //    // prevents listboxes from being out of place
  //    if (this.id == target_id) {
  //      return;
  //    }
  //
  //    editor.css('height', '')
  //      .addClass('os-wysiwyg-collapsed');
  //
  //    parent.find('.wysiwyg-toggle-wrapper').hide();
  //  })
  //}

  //function listboxClickHandler(e) {
  //  // there's no easy way to get the editor this list element is for. I have to muck about with the id string to
  //  // figure out which editor should be expanded.
  //  var id_frags = e.currentTarget.id.split('_'),
  //    dummy = {
  //      currentTarget: document.getElementById(id_frags[1])
  //    };
  //
  //  wysiwyg_expand(dummy);
  //}

  //function bindHandlers(ctx) {
  //  $('.os-wysiwyg-expandable', ctx).each(function () {
  //    var edit_id = $(this).attr('id');
  //    if (typeof tinyMCE.editors[edit_id] !== 'undefined' && typeof tinyMCE.editors[edit_id].contentDocument !== 'undefined') {
  //      tinyMCE.editors[edit_id].contentDocument.getElementsByTagName('body')[0].onclick = wysiwyg_expand;
  //      $('#'+edit_id+'_tbl').click(wysiwyg_expand);
  //      // use mouseup because it fires before click, and can't be prevented by other scripts' click handlers
  //      $('body').mouseup(wysiwyg_minimize);
  //
  //      $('.mceEditor table.mceLayout').not('.os-wysiwyg-collapsed').each(function () {
  //        var editor = $(this),
  //          parent = editor.parents('.form-item'),
  //          dim = parent.find('[data-minrows]'),
  //          height = (parseInt(dim.attr('data-minrows')) * 25),
  //          iframe = $('iframe', editor);
  //
  //
  //        editor.css('height', '')
  //          .addClass('os-wysiwyg-collapsed');
  //        $('iframe', editor).css({height: height+'px'});
  //        parent.find('.wysiwyg-toggle-wrapper').hide();
  //      });
  //
  //      $('.os-wysiwyg-expandable ~ .wysiwyg-toggle-wrapper a').click(toggleHandlers);
  //    }
  //    else {
  //      setTimeout(function () { bindHandlers(ctx); }, 500);
  //    }
  //  });
  //}

  //function toggleHandlers(e) {
  //  bindHandlers($(this).parents('.text-format-wrapper'));
  //}

  //Drupal.behaviors.osWysiwygExpandingTextarea = {
  //  attach: function (ctx) {
  //    setTimeout(function () { bindHandlers(ctx); }, 500);
  //    if (typeof ctx.body != 'undefined') {
  //      $(ctx.body).delegate('.mceListBoxMenu[role="listbox"]', 'click', listboxClickHandler);
  //    }
  //    // reset the data for anyone who's been here before
  //    // we've never had to perform an 'update hook' task on javascript data before
  //    // so I'm making up a convention as I go.
  //    if (typeof localStorage.osWysiwygExpandableTextareaUpdate316 == 'undefined') {
  //      localStorage.osWysiwygExpandableTextareaUpdate316 = 'true';
  //      var data = JSON.parse(localStorage.osWysiwygExpandableTextarea);
  //      for (var i in data) {
  //        if (typeof data[i] == 'object' && data[i].height == 250) {
  //          delete data[i];
  //        }
  //      }
  //      localStorage.osWysiwygExpandableTextarea = JSON.stringify(data);
  //    }
  //  }
  //};

  //// We override some default code of the wysiwyg module.
  //// See @OS custom logic in prepareContent().
  //Drupal.wysiwyg.editor.instance.tinymce.prepareContent = function(content) {
  //  // @OS custom logic. We need to do this since there is a use case for when
  //  // a user enters broken HTML when disabling the rich text feature.
  //  var d = document.createElement('div');
  //  d.innerHTML = content;
  //  content = d.innerHTML;
  //  // End of OS custom logic.
  //
  //  // Certain content elements need to have additional DOM properties applied
  //  // to prevent this editor from highlighting an internal button in addition
  //  // to the button of a Drupal plugin.
  //  var specialProperties = {
  //    img: { 'class': 'mceItem' }
  //  };
  //  var $content = $('<div>' + content + '</div>'); // No .outerHTML() in jQuery :(
  //  // Find all placeholder/replacement content of Drupal plugins.
  //  $content.find('.drupal-content').each(function() {
  //    // Recursively process DOM elements below this element to apply special
  //    // properties.
  //    var $drupalContent = $(this);
  //    $.each(specialProperties, function(element, properties) {
  //      $drupalContent.find(element).andSelf().each(function() {
  //        for (var property in properties) {
  //          if (property == 'class') {
  //            $(this).addClass(properties[property]);
  //          }
  //          else {
  //            $(this).attr(property, properties[property]);
  //          }
  //        }
  //      });
  //    });
  //  });
  //  return $content.html();
  //};

})(jQuery);
