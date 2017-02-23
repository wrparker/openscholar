/**
 *
 */
Drupal.wysiwyg.plugins['os_link'] = {
  url: '',
  iframeWindow: {},
  getWindow: function () {
    var aid = Drupal.wysiwyg.activeId;
    if (this.iframeWindow[aid] == undefined) {
      this.iframeWindow[aid] = document.querySelector('#'+aid+' + .cke iframe').contentWindow;
    }

    return this.iframeWindow[aid];
  },
  getSelection: function () {
    var w = this.getWindow();
    var selection = w.getSelection();
    var current, text;

    if (selection.type == "Caret" || selection.isCollapsed == true) {
      current = selection.anchorNode;
      while (current.nodeType != Node.ELEMENT_NODE) {
        current = current.parentNode;
      }
      text = "";
    }
    else if (selection.type == "Range" || selection.isCollapsed == false) {
      var range = document.createRange();
      range.setStart(selection.anchorNode, selection.anchorOffset);
      range.setEnd(selection.focusNode, selection.focusOffset);
      current = range.commonAncestorContainer;
      text = range.toString();
    }
    else {
      return;
    }

    var output = {
      content: text,
      format: "html",
      node: current
    };
    return output;
  },

  /**
   * Determines if element belongs to this plugin or not
   * Returning true will cause the button to be 'down' when an element is selected
   */
  isNode: function (node) {
    if (node == null || node == undefined) {
      var selection = this.getSelection();
      if (selection.node) {
        node = selection.node;
      }
      else {
        return false;
      }
    }
    while (node.nodeName != 'A' && node.nodeName != 'BODY') {
      node = node.parentNode;
    }
    return node.nodeName == 'A';
  },

  invoke: function (selection, settings, editorId) {
    if (selection.node == null) {
      selection = this.getSelection();
    }
    if (this.isNode(selection.node)) {
      var link = jQuery(selection.node);
      if (link[0].nodeName != 'A') {
        link = link.find('a');
      }
      if (link.length == 0) {
        link = jQuery(selection.node).parents('a');
      }

      this.openModal(this.parseAnchor(link));
    }
    else {
      var selectedLink = jQuery.selectLink != null && typeof(jQuery.selectLink) == 'object';
      if (selectedLink) {
        this.openModal(this.parseAnchor(jquery.selectLink[0]));
      }
    }
  },

  openModal: function (info) {
    var self = this;

    settings['global'].active = info.type;
    settings['global'].url = info.url;

    Drupal.media.popups.mediaBrowserOld(function (insert) {
      self.insertLink();
    }, settings['global'], {}, {
      src: Drupal.settings.osWysiwygLink.browserUrl, // because media is dumb about its query args
      onLoad: function (e) { self.popupOnLoad(e, selection, editorId); }
    });

    // adjust size of modal
    jQuery('iframe.media-modal-frame').attr('width', '').css('width', '100%')
      .parent('.media-wrapper').css({
        width: '905px',
        left: '50%',
        marginLeft: '-452.5px'
      });
  },

  insertLink: function (editorId, body, target, attributes) {
    var html = '<a href="'+target+'">'+(body?body:target)+'</a>';

    if (attributes) {
      var $html = jQuery(html);
      $html.attr(attributes);
      html = typeof $html[0].outerHTML != 'undefined'
              ? $html[0].outerHTML
              : $html.wrap('<div>').parent().html();
    }

    Drupal.wysiwyg.instances[editorId].insert(html);
    delete jQuery.selectLink;
  },

  tryRestoreFakeAnchor: function(editor, dom) {
    CKEDITOR.plugins.original_link.tryRestoreFakeAnchor(editor, dom);
  },

  getSelectedLink: function(editor) {
    // Calling the original function.
    CKEDITOR.plugins.original_link.getSelectedLink(editor);
  },

  popupOnLoad: function (e, selection, editorId) {
    // bind handlers to the insert button
    // each 'form' should have a little script to generate an anchor tag or do something else with the data
    // this scripts should put the generated tag somewhere consistent
    // this function will bind a handler to take the tag and have it inserted into the wysiwyg
    var $ = jQuery,
      self = this,
      iframe = e.currentTarget,
      doc = $(iframe.contentDocument),
      window = iframe.contentWindow,
      selected = '[Rich content. Click here to overwrite.]';

    // The user selected a link and not double clicked on a link.
    var selectedLink = jQuery.selectLink != null && typeof(jQuery.selectLink) == 'object';

    if (this.selectLink(selection.node) && selection.content == '') {
      selection.content = selection.node.innerHTML;
    }

    if (selectedLink) {
      $('.form-item-external input', doc).val(jQuery.selectLink.attr('href'));
    }

    if (selection.node != null && selection.node.text != null) {
      $('.form-item-link-text input', doc).val(selection.node.text);
    }
    else {
      if (selectedLink) {
        $('.form-item-link-text input', doc).val(jQuery.selectLink.text());
      }
      else if(typeof(jQuery.selectLink) == 'string') {
        $('.form-item-link-text input', doc).val(jQuery.selectLink);
      }
    }

    // If the link is set to be opened in a new window, then the checkbox will be in checked state.
    if (selection.node && selection.node.nodeType == Node.ELEMENT_NODE && (selection.node.getAttribute('target') == '_blank')) {
      $('#edit-target-option', doc).prop('checked', 'checked');
    }
    else {
      if (selectedLink && jQuery.selectLink.attr('target') == "_blank") {
        $('#edit-target-option', doc).prop('checked', 'checked');
      }
    }

    // If the link has a title attribute.
    if (selection.node && selection.node.nodeType == Node.ELEMENT_NODE && selection.node.getAttribute('title') != '') {
      $('#edit-link-title', doc).val(selection.node.getAttribute('title'));
    }
    else {
      if (selectedLink) {
        var title = jQuery.selectLink.attr('title');

        if (title != null) {
          $('#edit-link-title', doc).val(title);
        }
      }
    }

    $('.insert-buttons input[value="Insert"]', doc).click(function (e) {
      $('.vertical-tabs .vertical-tabs-pane:visible .form-actions input[value="Insert"]', doc).click();

      if (window.Drupal.settings.osWysiwygLinkResult) {
        var attrs = typeof window.Drupal.settings.osWysiwygLinkAttributes != 'undefined'
              ? window.Drupal.settings.osWysiwygLinkAttributes
              : false,
            text = $('.form-item-link-text input', doc).val();

        if (text == selected) {
          text = selection.content;
        }
        else if (text == '') {
          text = window.Drupal.settings.osWysiwygLinkResult;
        }


        self.insertLink(editorId, text, window.Drupal.settings.osWysiwygLinkResult, attrs);
        $(iframe).dialog('destroy');
        $(iframe).remove();
        window.Drupal.settings.osWysiwygLinkResult = null;
      }
    });

    $('.insert-buttons input[value="Cancel"]', doc).click(function (e) {
      $(iframe).dialog('destroy');
      $(iframe).remove();
    });
  },

  /**
   * Reads an anchor tag to determine whether it's internal, external, an e-mail or a link to a file
   * @param a
   * @return {link text, link url, link type}
   */
  parseAnchor: function (a) {
    var ret = {
      text: a.innerHTML,
      url: '',
      type: ''
    };
    if (a.hasAttribute('data-fid')) {
      ret.url = a.getAttribute('data-fid');
      ret.type = 'file';
    }
    else if (a.origin == 'mailto://' || a.protocol == 'mailto:') {
      ret.url = a.pathname || a.href.replace('mailto:', '');
      ret.type = 'email';
    }
    else {
      var home = Drupal.settings.basePath + (typeof Drupal.settings.pathPrefix != 'undefined'?Drupal.settings.pathPrefix:''),
          dummy = document.createElement('a');
      dummy.href = home;
   // TODO: Remove the 0 when internal is implemented
      if (0 && dummy.hostname == a.hostname && a.pathname.indexOf(dummy.pathname) != -1) {
        // internal link
        ret.url = a.pathname.replace(home, '');
        ret.type = 'internal';
      }
      else if (a.hasAttribute('data-url')) {
        ret.url = a.getAttribute('data-url');
        ret.type = 'external';
      }
      else {
        ret.url = a.href.replace(home, '');
        ret.type = 'external';
      }

    }
    return ret;
  },

  selectLink: function (node) {
    if (this.isNode(node)) {
      var target = jQuery(node).closest('a'),
          doc = node.ownerDocument;

      if (typeof doc.getSelection == 'function') {
        var selection = doc.getSelection(),
           range = selection.getRangeAt(0);
        range.selectNode(target[0]);
        selection.removeAllRanges();
        selection.addRange(range);
      }
      else {
        // IE
        doc.selection.empty();
        var range = doc.body.createTextRange();
        range.moveToElementText(target[0]);
        range.select();
      }
      return true;
    }
    return false;
  },

  /**
   * Converts link media tags into anchor tags
   */
  attach: function (content, settings, instanceId) {
    return content;
  },

  /**
   * Converts links to files into media tags
   */
  detach: function (content, settings, instanceId) {
    return content;
  }
};
