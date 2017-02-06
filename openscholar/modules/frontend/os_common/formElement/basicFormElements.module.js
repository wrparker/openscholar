(function () {

  var m = angular.module('basicFormElements', ['osHelpers', 'ngSanitize']);

  /**
   * SelectOptGroup directive.
   */
  m.directive('feOptgroup', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
        '<div class="form-item form-type-select">' +
        '<select class="form-select" id="{{id}}" name="{{name}}" ng-model="value">' +
        '<option ng-repeat="category in categories | filterWithItems:false" value="{{category.id}}">{{category.name}}</option>' +
        '<optgroup ng-repeat="category in categories | filterWithItems:true" label="{{category.name}}">' +
        '<option ng-repeat="subCat in category.items" value="{{subCat.id}}">{{subCat.name}}</option>' +
        '</optgroup>' +
        '</select>' +
        '</div>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
        var items = [];
        angular.forEach(scope.element.options, function(value, key) {
          if (angular.isObject(value)) {
            var data = {};        
            data.id = key;
            data.name = key;
            data.items = [];
            angular.forEach(value, function(childOption, childKey) {
              var subcat = {};
              subcat.id = childKey;
              subcat.name = childOption;
              data.items.push(subcat);
            });
            items.push(data);
          } else {
            var data = {};
            data.id = key;
            data.name = value;
            items.push(data);
          }
        });
        scope.categories = items;
      }
    }
  }]);

  /**
   * Checkbox directive.
   * Arguments:
   *   name - string - the name of the element as Drupal expects it
   *   value - property on parent scope
   */
  m.directive('feCheckbox', [function () {
    return {
      require: 'ngModel',
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<input type="checkbox" id="{{id}}" name="{{name}}" value="1" class="form-checkbox" ng-model="value" ng-disabled="element.disabled" ng-true-value="1" ng-false-value="0"/><label class="option" for="{{id}}">{{title}}</label>',
      link: function (scope, elem, attr, ngModelController) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Textbox directive.
   */
  m.directive('feTextfield', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<input type="textfield" id="{{id}}" name="{{name}}" ng-model="value" class="form-text" ng-disabled="element.disabled">',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Textarea directive
   */
  m.directive('feTextarea', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<textarea id="{{id}}" name="{{name}}" ng-model="value" class="form-textarea" ng-disabled="element.disabled"></textarea>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Radios directive.
   */
  m.directive('feRadios', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<div id="{{id}}" class="form-radios">' +
        '<div class="form-item form-type-radio" ng-repeat="(val, label) in options">' +
          '<input ng-click="radiosClick()" type="radio" id="{{id}}-{{val}}" name="{{name}}" value="{{val}}" ng-model="$parent.value" class="form-radio" ng-disabled="element.disabled"><label class="option" for="{{id}}-{{val}}" ng-bind-html="label"></label>' +
        '</div>' +
      '</div> ',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.options = scope.element.options;
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Submit button directive
   *
   * This type of form element should always have some kind of handler on the server end to take care of whatever this needs to do.
   */
  m.directive('feSubmit', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}<input button-spinner="settings_form" spinning-text="Submitting" type="submit" id="{{id}}" name="{{name}}" value="{{label}}" class="form-submit" ng-disabled="element.disabled">',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.label = scope.element.value;
        scope.title = scope.element.title;

        elem.click(function (click) {
          scope.value = (scope.value + 1)%2;
        });
      }
    }
  }]);

  /**
   * Markup directive.
   *
   * Just markup that doesn't do anything.
   */
  m.directive('feMarkup', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '=',
      },
      template: '<div ng-bind-html="markup"></div>',
      link: function (scope, elem, attr) {
        scope.markup = $sce.trustAsHtml(scope.element.markup);
      }
    }
  }])

  /**
   * Container directive.
   *
   * Just markup along with a container id.
   */
  m.directive('feContainer', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '=',
      },
      template: '<div ng-bind-html="markup" id="{{cid}}"></div>',
      link: function (scope, elem, attr) {
        scope.markup = $sce.trustAsHtml(scope.element.markup);
        scope.cid = scope.element.cid;
      }
    }
  }])

})();
