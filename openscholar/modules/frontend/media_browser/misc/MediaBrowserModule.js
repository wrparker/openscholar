(function ($) {
  var rootPath,
    open = angular.noop;

  function defaultParams() {
    var params = {
      dialog: {
        buttons: {},
        dialogClass: 'media-wrapper',
        modal: true,
        draggable: false,
        resizable: false,
        minWidth: 600,
        width: 800,
        height: 650,
        position: 'center',
        title: undefined,
        overlay: {
          backgroundColor: '#000000',
          opacity: 0.4
        },
        zIndex: 10000,
        close: function (event, ui) {
          $(event.target).remove();
        }
      },
      browser: {
        panes: {
          web: true,
          upload: true,
          library: true
        }
      },
      onSelect: angular.noop,
      types: {
        image: 'image',
        video: 'video',
        audio: 'audio',
        executable: 'executable',
        document: 'document',
        html: 'html'
      }
    };

    return params;
  }

  angular.module('mediaBrowser', ['JSPager', 'EntityService', 'os-auth', 'ngSanitize', 'angularFileUpload', 'angularModalService', 'FileEditor', 'mediaBrowser.filters'])
    .config(function (){
       rootPath = Drupal.settings.paths.moduleRoot;
    })
    .run(['ModalService', function (ModalService) {
      open = function (params) {
        params = jQuery.extend(true, {}, defaultParams(), params);
        ModalService.showModal({
          templateUrl: rootPath+'/templates/browser.html?vers='+Drupal.settings.version.mediaBrowser,
          controller: 'BrowserCtrl',
          inputs: {
            params: params
          }
        }).then(function (modal) {
          modal.element.dialog(params.dialog);
          modal.close.then(function (result) {
            // run the function passed to us
            if (Array.isArray(result)) {
              if (result.length) {
                params.onSelect(result);
              }
            }
            else if (result) {
              params.onSelect(result);
            }
          });
        });
      }

      // if the File object is not supported by this browser, fallback to the original media browser
      if (typeof window.File != 'undefined') {
        Drupal.media = Drupal.media || {};
        Drupal.media.popups = Drupal.media.popups || {};
        var oldPopup = Drupal.media.popups.mediaBrowser;
        Drupal.media.popups.mediaBrowserOld = oldPopup;
        Drupal.media.popups.mediaBrowser = function (onSelect, globalOptions, pluginOptions, widgetOptions) {
          var options = Drupal.media.popups.mediaBrowser.getDefaults();
          options.global = $.extend({}, options.global, globalOptions);
          options.plugins = pluginOptions;
          options.widget = $.extend({}, options.widget, widgetOptions);


          // Params to send along to the iframe.  WIP.
          var params = {};
          $.extend(params, options.global);
          params.plugins = options.plugins;
          params.onSelect = onSelect;

          open(params);
        }

        for (var k in oldPopup) {
          if (!Drupal.media.popups.mediaBrowser[k]) {
            Drupal.media.popups.mediaBrowser[k] = oldPopup[k];
          }
        }
      }
    }])
  .controller('BrowserCtrl', ['$scope', '$filter', '$http', 'EntityService', '$sce', '$upload', '$timeout', 'params', 'close',
      function ($scope, $filter, $http, EntityService, $sce, $upload, $timeout, params, close) {

    // Initialization
    var service = new EntityService('files', 'id'),
      toEditForm = false;
    $scope.files = [];
    $scope.numFiles = 0;
    $scope.templatePath = rootPath;
    $scope.selection = 0;
    $scope.form = '';
    $scope.pane = 'upload';
    $scope.toBeUploaded = [];
    $scope.dupes = [];
    $scope.showButtons = false;
    $scope.params = params.browser;
    $scope.editing = false;
    $scope.deleting = false;
    $scope.activePanes = params.browser.panes;
    $scope.activePanes.edit = true;
    $scope.activePanes.delete = true;
    $scope.loading = true;

    $scope.availTypes = [
      {label: 'Image', value: 'image'},
      {label: 'Document', value: 'document'},
      {label: 'Video', value: 'video'},
      {label: 'HTML', value: 'html'},
      {label: 'Executable', value: 'executable'},
      {label: 'Audio', value: 'audio'}
    ];

    $scope.extensions = [];
    if (params.file_extensions) {
      $scope.extensions = params.file_extensions.split(' ');
    }
    if (!params.override_extensions) {
      var types = params.types;
      console.log(types);
      for (var t in types) {
        var ext = Drupal.settings.extensionMap[types[t]],
          i = 0, l = ext ? ext.length : false;

        if (!ext) continue;

        for (var i=0; i<l; i++) {
          if ($scope.extensions.indexOf(ext[i]) === -1) {
            $scope.extensions.push(ext[i]);
          }
        }
      }
    }
    $scope.extensions.sort();

    if (params.max_filesize) {
      $scope.maxFilesize = params.max_filesize;
    }
    else {
      $scope.maxFilesize = Drupal.settings.maximumFileSize;
    }

    $scope.isFiltered = function () {
      return $scope.filteredTypes.length || $scope.search != "";
    }

    $scope.clearFilters = function () {
      $scope.filteredTypes = [];
      $scope.search = '';
    }

    $scope.showHelp = false;

    if (close) {
      $scope.showButtons = true;
    }

    $scope.messages = {
      next: 0
    };

    // Watch for changes in file list
    $scope.$on('EntityService.files.add', function (event, file) {
      //$scope.files.push(file)
      $scope.pane = 'library';
      $scope.setSelection(file.id);
    });

    $scope.$on('EntityService.files.update', function (event, file) {
      $scope.files = service.getAll();
    });

    $scope.$on('EntityService.files.delete', function (event, id) {
      // Don't want to worry about what happens when you modify an array you're looping over
      var deleteMe;
      for (var i=0; i<$scope.files.length; i++) {
        if ($scope.files[i].id == id) {
          deleteMe = i;
          break;
        }
      }

      $scope.files.splice(deleteMe, 1);
    })

    service.fetch({})
      .then(function (result) {
        console.log(result);

        $scope.files = result.data.data;
        $scope.numFiles = $scope.files.length;
        $scope.loading = false;
      });


    $scope.changePanes = function (pane) {
      if ($scope.activePanes[pane]) {
        $scope.pane = pane;
        return true;
      }
      else {
        close(true);
      }
    }

    $scope.validate = function($file) {
      var file = $file;
      if (file && file instanceof File) {
        // TODO: Get validating properties from somewhere and check the file against them

        var maxFilesize = params.max_filesize_raw || Drupal.settings.maximumFileSizeRaw;
        var size = maxFilesize > file.size,   // file is smaller than max
          ext = file.name.slice(file.name.lastIndexOf('.')+1),
          extension = $scope.extensions.indexOf(ext) !== -1,    // extension is found
          id;

        if (!size) {
          addMessage(file.name + ' is larger than the maximum filesize of ' + (params.max_filesize || Drupal.settings.maximumFileSize));
        }
        if (!extension) {
          addMessage(file.name + ' is not an accepted file type.');
        }
        // if file is image and params specify max dimensions
        if (file.type.indexOf('image/') !== -1 && params.min_dimensions) {
          // since we can't force this function to wait, we have to use an onload
          // and check this before uploading
        }

        return size && extension;
      }
    }

    function addMessage(message) {
      var id = $scope.messages.next++;
      $scope.messages[id] = {
        text: message
      };
      $timeout(angular.bind($scope, removeMessage, id), 5000);
    }

    function removeMessage(id) {
      delete this.messages[id];
    }

    // looks for any files with a similar basename and extension to this file
    // if it finds any, it adds it to a list of dupes, then scans every file to find what the new name should be
    $scope.checkForDupes = function($files, $event, $rejected) {
      if ($files.length == 1) {
        toEditForm = true;
      }
      var toBeUploaded = [];
      $scope.dupes = [];
      for (var i=0; i<$files.length; i++) {
        var similar = [],
            basename = $files[i].name.replace(/\.[a-zA-Z0-9]*$/, ''),
            extension = $files[i].name.replace(basename, ''),
            dupeFound = false;

        for (var j=0; j<$scope.files.length; j++) {
          // find any file with a name that matches "basename{_dd}.ext" and add it to list of similar files
          if ($scope.files[j].filename.indexOf(basename) !== -1 && $scope.files[j].filename.indexOf(extension) !== -1) {
            similar.push($scope.files[j]);
            // also check if there is a file with the full filename and save this fact for later
            // this allows file.jpg to be uploaded when file_01.jpg already exists
            if ($scope.files[j].filename == $files[i].name) {
              dupeFound = true;
            }
          }
        }

        if (dupeFound) {
          // only one similar file found, drop _01 at the end
          if (similar.length == 1) {
            $files[i].newName = basename + '_01' + extension;
          }
          else {
            // lots of them found, look through all of them, find the highest number at the end, and add it
            // to the end of the original file name
            var max = 0;
            for (j=0; j<similar.length; j++) {
              var rem = similar[j].filename.replace(basename, '').replace(extension, '').replace('_', ''),
                num = rem ? parseInt(rem) : 0;

              if (num > max) {
                max = num;
              };
            }
            var num = max + 1;
            // convert num to a 2 digit string
            num = num < 10 ? '0'+num : num
            // and make the new file name
            $files[i].newName = basename+'_'+num+extension;
          }
          // with the new name complete, we can push this onto the list of dupes
          $scope.dupes.push($files[i]);
        }
        else {
          // not a dupe, just upload it silently
          toBeUploaded.push($files[i]);
        }
      }

      $scope.upload(toBeUploaded);
    }

    // renames the file before uploading
    $scope.rename = function ($index, $last) {
      $scope.dupes[$index].processed = true;

      if ($last) {
        finalizeDupes();
      }
    }

    // tells the server to replace the old file on disk with this new one
    // (just performs a swap on the hard drive)
    $scope.replace = function ($index, $last) {
      $scope.dupes[$index].processed = true;
      delete $scope.dupes[$index].newName;

      if ($last) {
        finalizeDupes();
      }
    }

    // cancels the upload process for this file
    $scope.cancelUpload = function ($index, $last) {
      $scope.dupes[$index].doNotUpload = true;
      $scope.dupes[$index].processed = true;

      if ($last) {
        finalizeDupes();
      }
    }

    function finalizeDupes() {
      var toBeUploaded = [];
      for (var i in $scope.dupes) {
        if (!$scope.dupes[i].doNotUpload) {
          toBeUploaded.push($scope.dupes[i]);
        }
      }

      $scope.upload(toBeUploaded);
      $scope.dupes = [];
    }

    (function () {
      var toBeUploaded = [],
        uploading = false,
        progress = null,
        currentlyUploading = 0;

      $scope.upload = function ($files) {
        for (var i=0; i<$files.length; i++) {
          toBeUploaded.push($files[i]);
        }

        if (!uploading && toBeUploaded.length) {
          uploading = true;
          $file = toBeUploaded[currentlyUploading];
          uploadOne($file);
        }
      }

      function uploadNext() {
        currentlyUploading++;
        if (currentlyUploading < toBeUploaded.length) {
          $file = toBeUploaded[currentlyUploading];
          uploadOne($file);
        }
        else {
          toBeUploaded = [];
          uploading = false;
          progress = null;
          currentlyUploading = 0;
          if (toEditForm) {
            // there's only one file, we can assume it's this one
            $scope.setSelection(e.data[0].id);
            $scope.changePanes('edit');
          }
          else {
            $scope.changePanes('library');
          }
        }
      }

      function uploadOne($file) {
        var fields = {};
        if (Drupal.settings.spaces) {
          fields.vsite = Drupal.settings.spaces.id;
        }
        $upload.upload({
          url: Drupal.settings.paths.api+'/files',
          file: $file,
          data: $file,                                        // UPLOADED FILES ARE NOT GETTING THE VSITE
          fileFormDataName: 'files[upload]',
          headers: {'Content-Type': $file.type},
          method: 'POST',
          fields: fields,
          fileName: $file.newName || null
        }).progress(function (e) {
          progress = e;
        }).success(function (e) {
          for (var i = 0; i< e.data.length; i++) {
            e.data[i].new = true;
            $scope.files.push(e.data[i]);
            service.register(e.data[i]);
          }
          uploadNext();
        }).error(function (e) {
          addMessage('Unable to upload file. Contact site administrator.');
          uploadNext();
        });
      }

      $scope.uploadProgress = function () {
        return {
          uploading: uploading,
          filename: uploading ? toBeUploaded[currentlyUploading].name : '',
          progressBar: (uploading && progress) ? parseInt(100.0 * progress.loaded / progress.total) : 0,
          index: currentlyUploading+1,
          numFiles: toBeUploaded.length
        }
      }
    })();


    // selected file
    $scope.setSelection = function (fid) {
      $scope.selection = fid;
      $scope.selected_file = angular.copy(service.get(fid));
    };

    // file edit form methods
    $scope.save = function() {
      service.edit($scope.selected_file);
    };

    $scope.deleteConfirmed = function() {
      service.delete($scope.selected_file)
        .then(function (resp) {
          $scope.changePanes('library');
        });
    };


    $scope.embed = 'URL or Markup';
    $scope.embedSubmit = function () {
      // construct the entity
      var data = {
        embed: this.embed,
      }

      service.add(data);
    }

    $scope.insert = function () {
      var results = [];
      $scope.selected_file.fid = $scope.selected_file.id; // hack to prevent rewriting a lot of Media's code.
      results.push($scope.selected_file);

      close(results);
    }

    $scope.cancel = function () {
      close([]);
    }
  }])
  .directive('mbOpenModal', ['ModalService', function(ModalService) {

    function link(scope, elem, attr,contr) {
      elem.bind('click', clickHandler);
    }

    function clickHandler(event) {
      event.preventDefault();
      // get stuff from the element we clicked on and Drupal.settings
      var elem = event.currentTarget,
        params = defaultParams(),
        panes = elem.attributes['panes'].value,
        types = elem.attributes['types'].value.split(',');

      for (var i in params.browser.panes) {
        params.browser.panes[i] = (panes.indexOf(i) !== -1);
      }

      params.browser.allowedTypes = {}
      for (i=0; i<types.length;i++) {
        params.browser.allowedTypes[types[i]] = types[i];
      }

      params.onSelect = function () {
        window.location.reload();
      }

      open(params);
    }

    return {
      template: '<ng-transclude></ng-transclude>',
      link: link,
      transclude: true
    }
  }]);
})(jQuery);
