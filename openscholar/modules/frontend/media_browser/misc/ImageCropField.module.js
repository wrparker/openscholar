(function ($) {

  var m = angular.module("ImageCropField", ['MediaBrowserField', 'EntityService']);

  m.directive("imageCropField", ['EntityService', function (EntityService) {
    return {
      require: '?ngModel',
      scope: {
        element: '=',
        data: '=ngModel'
      },
      template:
      '<div class="imagecrop-wrapper">' +
        '<div>' +
          '<div ng-model="data.fid" media-browser-field cardinality="1" panes="upload,library" droppable-text="Drag and drop your image here" upload-text="Upload" types="image" max-filesize="100kb">' +
        '</div>' +
        '<div class="image-crop-selection-wrapper">' +
          '<div class="jcrop-preview-wrapper">' +
            '<img class="imagecrop-preview" ng-src="{{file.url}}">' +
          '</div>' +
          '<img class="imagecrop-selection" ng-src="{{file.url}}">' +
        '</div>' +
      '</div>',
      link: function (scope, elem, attrs) {
        var filesService = new EntityService('files', 'id');
        var cropbox = $(elem[0].querySelector('.imagecrop-selection')).bind('load', function () {
            imgLoaded = true;
          }),
          preview_wrapper = $(elem[0].querySelector('.jcrop-preview-wrapper')),
          preview = $(elem[0].querySelector('.imagecrop-preview')),
          imgLoaded = false;

        scope.$watch('data.fid', function (val) {
          imgLoaded = false;
          filesService.fetch().then(function (files) {
            for (var i = 0; i < files.length; i++) {
              if (files[i].id == val) {
                scope.file = files[i];
                return;
              }
            }

            scope.file = null;
          });
        })

        scope.$watch(function () {
          return scope.file != null && !elem.hasClass('ng-hide') && imgLoaded;
        }, function (val) {
          if (val == false) return;
          var origWidth = preview.width(),
            origHeight = preview.height(),
            targWidth = attrs.width,
            targHeight = attrs.height;

          preview_wrapper.css({
            width: targWidth,
            height: targHeight,
            overflow: "hidden"
          });

          $(cropbox).Jcrop({
            onChange: function (c) {
              // $('.preview-existing', widget).css({display: 'none'});
              // skip newly added blank fields
              console.log(c);

              var rx = targWidth / c.w;
              var ry = targHeight / c.h;
              preview.css({
                width: Math.round(rx * origWidth) + 'px',
                height: Math.round(ry * origHeight) + 'px',
                marginLeft: '-' + Math.round(rx * c.x) + 'px',
                marginTop: '-' + Math.round(ry * c.y) + 'px',
                display: 'block'
              });
            },
            onSelect: function (c) {
              //$('.preview-existing', widget).css({display: 'none'});
              scope.data.dimensions.x = c.x;
              scope.data.dimensions.y = c.y;
              if (c.w) scope.data.dimensions.w = c.w;
              if (c.h) scope.data.dimensions.h = c.h;
            },
            aspectRatio: scope.data.dimensions.ratio,
            boxWidth: origWidth,
            boxHeight: origHeight,
            //minSize: [Drupal.settings.imagefield_crop[id].minimum.width, Drupal.settings.imagefield_crop[id].minimum.height],
            /*
             * Setting the select here calls onChange event, and we lose the original image visibility
             */
            setSelect: [
              scope.data.dimensions.x,
              scope.data.dimensions.y,
              scope.data.dimensions.width,
              scope.data.dimensions.height
            ]
          });
        });
      }
    }
  }]);
})(jQuery);