(function () {

  var m = angular.module('displayTypeForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'displayTypeForm');
  }]);

  m.directive('displayType', ['$http', 'buttonSpinnerStatus', function($http, bss) {
    return {
      template:
        '<div class="profiles-display-types clearfix">' +
          '<label for="edit-os-profiles-disable-default-image">{{title}}</label>' +
          '<div class="first">' +
            '<div class="form-radios">' +
              '<div class="form-item form-type-radio" ng-repeat="(option, label) in options">' +
                '<input type="radio" name="os_profiles_display_type" value="{{option}}" /> {{label}}' +
              '</div>' +
            '</div>' +
          '</div>' +
          '<div class="second">' +
            '<p ng-bind-html="preview"></p>' +
          '</div>' +
        '</div>',

      scope: {
        value: '=',
        element: '='
      },

      link: function (scope, elem, attr) {
        scope.title = scope.element.title;
        scope.previews = scope.element.previews;
        scope.options = scope.element.options;
        scope.preview = scope.previews[scope.element.value];
      }
    };
  }]);
})();
