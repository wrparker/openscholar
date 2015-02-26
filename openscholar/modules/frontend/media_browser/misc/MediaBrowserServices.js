(function() {
  var rootPath = Drupal.settings.osRestModulePath,
    restPath = Drupal.settings.restBasePath;

  angular.module('mediaBrowser.services', [])
  /**
   * Service to maintain the list of files on a user's site
   */
    .factory('FileService', ['$rootScope', '$http', function ($rootScope, $http) {
      var files = [];
      $http.get(restPath+'/file')
        .success(function success(data) {
          files = data.list;
          $rootScope.$broadcast('FileService.changed', files);
        })
        .error(function errorFunc() {
          $http.get({url: restPath}).
            success(success).
            error(errorFunc);
        });

      return {
        getAll: function () {
          return files;
        },
        get: function (fid) {
          for (var i = 0; i < files.length; i++) {
            if (files[i].fid == fid) {
              return files[i];
            }
          }
          throw new Exception('FID not found');
        },
        add: function (file) {
          files.push(file);
          $rootScope.$broadcast('FileService.changed', files);
        },
        edit: function (file) {
          for (var i = 0; i < files.length; i++) {
            if (files[i].fid == file.fid) {
              files[i] = file;
              $rootScope.$broadcast('FileService.changed', files);
              operating = true;
              $http.put
              return true;
            }
          }
          return false;
        },
        delete: function (fid) {
          if (angular.isObject(fid)) {
            fid = fid.fid;
          }
          for (var i = 0; i < files.length; i++) {
            if (files[i].fid == fid) {
              files.splice(i, 1);
              $rootScope.$broadcast('FileService.changed', files);
              return true;
            }
          }
          return false;
        },
        operating: false
      };
    }]);
})();