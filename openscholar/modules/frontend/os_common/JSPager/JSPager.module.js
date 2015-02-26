/**
 * A pager for a set of content that can be filtered.
 * The number of pages updates as the filters get more or less precise.
 *
 * Other modules implement the actual filtering. This module handles all interactions the filter has with the pager.
 */

(function () {
  var rootPath = Drupal.settings.paths.JSPager;

  angular.module('JSPager', [])
    .directive('jsPager', function() {
      return {
        templateUrl: rootPath+'/pager.html',
        transclude: true,
        compile: pagerCompile,
        //link: pagerLink
      };
    })
    .filter('currentPage', ['scope.pager', function (pager) {
      function currentPage(input) {
        if (input) {
          start = pager.currentPage();
          return input.slice(start);
        }
        return '';
      }
      currentPage.$stateful = true;

    }]);

  function pagerCompile(element, attr, linker) {
    var allElements = [];
    return function($scope, $element, $attr) {
      var loop = $attr.jsPager,
        match = $attr.jsPager.match(/^\s*(.+)\s+in\s+(.*?)\s*(\s+track\s+by\s+(.+)\s*)?$/),
        index = match[1],
        collection = match[2],
        elements = [];

      $scope.$watchCollection(collection, function(collection) {
        var i, block, childScope;

        // check if elements have already been rendered
        if (elements.length > 0){
          // if so remove them from DOM, and destroy their scope
          for (i = 0; i < elements.length; i++) {
            elements[i].el.remove();
            elements[i].scope.$destroy();
          };
          elements = [];
        }

        for (i = 0; i < collection.length; i++) {
          // create a new scope for every element in the collection.
          childScope = $scope.$new();
          // pass the current element of the collection into that scope
          childScope[indexString] = collection[i];

          linker(childScope, function(clone){
            // clone the transcluded element, passing in the new scope.
            $element.append(clone); // add to DOM
            block = {};
            block.el = clone;
            block.scope = childScope;
            elements.push(block);
          });
        };
      });
    }
  }


  function pagerLink(scope, iElement, iAttrs, controller) {
    var current = 1;

    scope.pager.perPage = scope
    scope.pager.currentPage = currentPage;
    scope.pager.numPages = numPages;
    scope.pager.canPage = canPage;
    scope.pager.changePage = changePage;

    function currentPage() {
      var pages = numPages();
      if (pages && scope.current > pages) {
        current = pages;
      }
      return current;
    }

    function numPages() {
      return Math.ceil(scope.collectionLength/scope.pageSize);
    }

    function canPage(dir) {
      dir = parseInt(dir);
      var newPage = currentPage() + dir;

      return (1 <= newPage && newPage <= numPages());
    }

    function changePage(dir) {
      if (canPage(dir)) {
        dir = parseInt(dir);
        scope.currentPage += dir;
      }
    }
  }
})();