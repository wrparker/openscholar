(function () {

  var m = angular.module('redirectForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'redirectForm');
  }]);

  m.directive('redirects', ['$http', function($http) {
    return {
      template: '<p>URL redirects allow you to send users from a URL on your site, to any other URL. You might want to use this to create a short link, or to transition users from an old URL to the new URL. Each site may only have a maximum of 15 of URL redirects.</p>' +
      '<ul class="table-list">' +
        '<li class="redirect-item" ng-repeat="r in redirects">' +
          '<span class="redirect-path">{{r.path}}</span> &#8594; <span class="redirect-target">{{r.target}}</span> <a class="redirect-delete" ng-click="deleteRedirect(r.id)">delete</a>' +
        '</li>' +
      '</ul>' +
      '<a class="redirect-add" ng-show="showAddLink()" ng-click="toggleAddForm()">+ Add new redirect</a>' +
      '<div class="redirect-add-form" ng-show="showAddForm()">' +
        '<div class="display-inline"><label for="redirect-path">Redirect From</label> {{siteBaseUrl}}/<input type="text" id="redirect-path" class="redirect-new-element" ng-model="newRedirectPath" placeholder="Local path"><span class="description">(Example: my-path). Fragment anchors (e.g. #anchor) are not allowed.</span></div>' +
        '<div class="display-inline"><label for="redirect-target">Redirect To</label> <input type="text" id="redirect-target" class="redirect-new-element" ng-model="newRedirectTarget" placeholder="Target URL (i.e. http://www.google.com)"><span class="description">Enter any existing destination URL (like http://example.com) to redirect to.</span></div>' +
        '<button type="button" value="Add Redirect" ng-click="addRedirect()">Add Redirect</button>' +
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

        scope.toggleAddForm = function () {
          showAddForm = !showAddForm;
        }

        scope.siteBaseUrl = Drupal.settings.paths.vsite_home;

        var cp_redirect_max = 15;
        scope.$watch('redirects', function (val) {
          var count = val.length || 0;
          if (count < cp_redirect_max) {
            showAddLink = true;
          }
          else {
            showAddLink = false;
          }
        })
        scope.redirects = scope.element.value;

        var restApi = Drupal.settings.paths.api + '/redirect/';
        var http_config = {
          params: {}
        };

        if (typeof Drupal.settings.spaces != 'undefined' && Drupal.settings.spaces.id) {
          http_config.params.vsite = Drupal.settings.spaces.id;
        }

        scope.newRedirectPath = '';
        scope.newRedirectTarget = '';
        scope.addRedirect = function () {
          var vals = {
            path: scope.newRedirectPath,
            target: scope.newRedirectTarget
          };

          $http.post(restApi, vals, http_config).then(function (r) {
            scope.redirects.push(r.data.data);
            scope.newRedirectPath = '';
            scope.newRedirectTarget= '';
            scope.showAddForm = false;
          },
          function (e) {

          });
        }

        scope.deleteRedirect = function (id) {
          var k = 0;
          $http.delete(restApi+'/'+id, http_config).then(function (r) {
              for (var i = 0; i < scope.redirects.length; i++) {
                if (scope.redirects[i].id == id) {
                  scope.redirects.splice(i, 1);
                  break;
                }
              }
          },
          function (e) {

          });

          for (var i = 0; i<scope.redirects.length; i++) {
            if (scope.redirects[i].id == id) {
              k = 0;
              break;
            }
          }
        }
      }
    };
  }]);
})();