(function () {

  var m = angular.module('grouper', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'grouper');
  }]);

  m.directive('grouper', ['$http', function ($http) {
    var groups = [];

    $http.get('groups').then(function (resp) {
      Array.prototype.push.apply(groups, resp.data.data); // this adds group elements without creating a new array instance
    }, function (errorResp) {
      console.log(errorResp);
    });
    return {
      scope: {
        selected: '=ngModel'
      },
      templateUrl: Drupal.settings.paths.grouper + '/grouper.template.html',
      link: function (scope, elem, attrs) {
        scope.groups = groups;

        /**
         * Gets or sets the panel's visibile status
         */
        var showPanel = false;
        scope.ShowPanel = function (toggle) {
          if (toggle != undefined) {
            showPanel = !showPanel;
          }

          return showPanel;
        }

        /**
         * @returns string - human-readable, comma-separated list of all selected groups, on a single line
         */
        scope.SelectedGroupNames = function () {
          return "";
        }

        /**
         *
         */
        scope.GroupLabel = function (path) {

        }

        /**
         *
         */
        scope.Remove = function (path) {

        }

        /**
         *
         */
        scope.AddGroup = function (path) {

        }

        /**
         *
         */
        scope.IsGroupSelected = function (path) {

        }
      }
    }
  }]);
})();