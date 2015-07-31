(function() {

  var m = angular.module('media-gallery-public', ['mediaBrowser', 'FileEditorModal']);

  m.run(function () {
    var fileEditLinks = angular.element('.media-gallery-media-item-thumbnail.contextual-links-region .contextual-links  .link-count-file-edit a');

    for (var i = 0; i < fileEditLinks.length; i++) {
      var elem = fileEditLinks[i],
        urlBits = elem.href.match(/file\/([\d]*)\/edit/);

      angular.element(elem).attr({
        href: '#',
        'file-editor-modal': '',
        fid: urlBits[1]
      });
    }
  });
})();