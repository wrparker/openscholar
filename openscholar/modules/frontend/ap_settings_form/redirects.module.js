(function () {

  var m = angular.module('redirectForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'redirectForm');
  }]);

  m.service('RedirectService', ['$http', 'buttonSpinnerStatus', function ($http, bss) {
    var redirects = {};

    var restApi = Drupal.settings.paths.api + '/redirect/';
    var http_config = {
      params: {}
    };

    if (typeof Drupal.settings.spaces != 'undefined' && Drupal.settings.spaces.id) {
      http_config.params.vsite = Drupal.settings.spaces.id;
    }

    var self = this;

    this.Get = function () {
      return redirects;
    }

    this.Register = function (existing) {
      for (var i in existing) {
        var id = existing[i].id;
        if (typeof redirects[id] == 'undefined') {
          this.Count++;
        }
        redirects[id] = existing[i];
      }
    }

    this.Create = function (source, target) {
      var vals = {
        path: source,
        target: target
      };

      return $http.post(restApi, vals, http_config).then(function (r) {
        var newRedirect = r.data.data;
        redirects[newRedirect.id] = newRedirect;
        self.Count++;
        return r;
       },
       function (e) {

       });
    }

    this.Delete = function (id) {
      bss.SetState('redirect_delete_'+id, true);
      $http.delete(restApi+'/'+id, http_config).then(function (r) {
        if (typeof redirects[id] != 'undefined') {
          delete redirects[id];
          self.Count--;
          bss.SetState('redirect_delete_'+id, false);
        }
      },
      function (e) {
        bss.SetState('redirect_delete_'+id, false);
      });
    }

    this.Count = 0;
  }]);

  m.directive('redirects', ['$http', 'RedirectService', 'buttonSpinnerStatus', function($http, rs, bss) {
    var hasRegistered = false;
    return {
      template: '<p>URL redirects allow you to send users from a URL on your site, to any other URL. You might want to use this to create a short link, or to transition users from an old URL to the new URL. Each site may only have a maximum of 15 of URL redirects.</p>' +
      '<ul class="table-list">' +
        '<li class="redirect-item" ng-repeat="r in redirects">' +
          '<span class="redirect-path">{{r.path}}</span> &#8594; <span class="redirect-target">{{r.target}}</span> <a class="redirect-delete" ng-click="deleteRedirect(r.id)" button-spinner="redirect_delete_{{r.id}}">delete</a>' +
        '</li>' +
      '</ul>' +
      '<a class="redirect-add" ng-show="showAddLink()" ng-click="toggleAddForm()">+ Add new redirect</a>' +
      '<div class="redirect-add-form" ng-show="showAddForm">' +
        '<div class="display-inline"><label for="redirect-path">Redirect From</label> {{siteBaseUrl}}/<input type="text" id="redirect-path" class="redirect-new-element" ng-model="newRedirectPath" placeholder="Local path"><span class="description">(Example: my-path). Fragment anchors (e.g. #anchor) are not allowed.</span></div>' +
        '<div class="display-inline"><label for="redirect-target">Redirect To</label> <input type="text" id="redirect-target" class="redirect-new-element" ng-model="newRedirectTarget" placeholder="Target URL (i.e. http://www.google.com)"><span class="description">Enter any existing destination URL (like http://example.com) to redirect to.</span></div>' +
        '<button type="button" value="Add Redirect" ng-click="addRedirect()"">Add Redirect</button>' +
      '</div>',
      scope: {
        value: '=',
        element: '='
      },
      link: function (scope, elem, attr) {
        var showAddLink = false;
        scope.showAddLink = function() {
          return cp_redirect_max > rs.Count;
        }

        scope.showAddForm = false;

        scope.toggleAddForm = function () {
          scope.showAddForm = !scope.showAddForm;
        }

        scope.siteBaseUrl = Drupal.settings.paths.vsite_home;

        var cp_redirect_max = scope.element.maximum_value_count;
        //scope.$watch('redirects', function (val) {
        //  var count = val.length || 0;
        //  if (count < cp_redirect_max) {
        //    showAddLink = true;
        //  }
        //  else {
        //    showAddLink = false;
        //  }
        //}, true);
        if (!hasRegistered) {
          rs.Register(scope.element.value);
          hasRegistered = true;
        }
        scope.redirects = rs.Get();

        scope.newRedirectPath = '';
        scope.newRedirectTarget = '';
        scope.addRedirect = function () {
          rs.Create(scope.newRedirectPath, scope.newRedirectTarget).then(function (r) {
            scope.newRedirectPath = '';
            scope.newRedirectTarget = '';
            scope.showAddForm = false;
          });
        }

        scope.deleteRedirect = function (id) {
          rs.Delete(id);
        }
      }
    };
  }]);
})();