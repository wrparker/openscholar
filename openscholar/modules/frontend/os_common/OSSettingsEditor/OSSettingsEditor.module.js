(function ($) {
  var libraryPath = '';

  angular.module('OSSettingsEditor', ['os-auth']).
    config(function () {
      libraryPath = Drupal.settings.paths.OSSettingsEditor;
    }).
    directive('editFeatureBlog', ['$http', function ($http) {
      return {
        scope: {
          onClose : '&'
        },
        templateUrl: libraryPath + '/settings_os_blog.html?vers='+Drupal.settings.version.FileEditor,
        link: function (scope, elem, attr, c, trans) {
          
      
        }
      }
    }]);

})(jQuery);
