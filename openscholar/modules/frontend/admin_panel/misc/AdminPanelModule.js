(function ($) {
  var rootPath,
    open = angular.noop;

    angular.module('AdminPanel', [])
    .config(function (){
       rootPath = Drupal.settings.paths.moduleRoot;
    }).controller("MenuCtrl", function($scope, $http) {
      $http.get('/api/cp_menu/admin_panel?vsite=2516').
        success(function(data, status, headers, config) {
          $scope.menu = data;
        });
    });;
    
})(jQuery);
