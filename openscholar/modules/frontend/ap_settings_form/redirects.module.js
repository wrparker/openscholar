(function () {

  var m = angular.module('redirectForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'redirectForm');
  }]);

  m.directive('redirects', [function() {
    return {
      template: '<p>URL redirects allow you to send users from a non-existing path on your site, to any other URL. You might want to use this to create a short link, or to transition users from an old URL that no longer exists to the new URL. Each site may only have a limited number of URL redirects.</p>' +
      '<ul class="redirect-list">' +
        '<li class="redirect-item" ng-repeat="r in redirects">' +
          '<span class="redirect-path">{{r.path}}</span> --> <span class="redirect-target">{{r.target}}</span> <a class="redirect-delete" redirect-id="{{r.id}}">delete</a>' +
        '</li>' +
      '</ul>' +
      '<a class="redirect-add" ng-show="showAddLink()" ng-click="toggleAddForm()">+ Add new redirect</a>' +
      '<div class="redirect-add-form" ng-show="showAddForm()">' +
        '<input type="text" ng-model="newRedirectPath"><input type="text" ng-model="newRedirectTarget">' +
      '</div>',
      scope: {
        value: '=',
        element: '='
      },
      link: function (scope, elem, attr) {
        var showAddLink = false;
        scope.showAddLink = function() {
          return showAddLink;
        }

        var showAddForm = false;
        scope.showAddForm = function () {
          return showAddForm;
        }


      }
    };
  }]);
})();