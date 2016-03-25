(function () {

  var m = angular.module('basicFormElements', ['osHelpers']);

  m.directive('checkbox', ['$filter', function ($filter) {
    return {
      scope: {
        name: '@',
        value: '=',
      },
      template: '<input type="checkbox" id="{{id}}" name="{{name}}" value="1" class="form-checkbox" ng-model="value" ng-true-value="1" ng-false-value="0"/>',
      link: function (scope, elem, attr) {
        console.log(scope);
        scope.id = attr['inputId'];
      }
    }
  }]);

})();
