/**
 * A pager for a set of content that can be filtered.
 * The number of pages updates as the filters get more or less precise.
 *
 * Other modules implement the actual filtering. This module handles all interactions the filter has with the pager.
 */

(function () {
  var rootPath = Drupal.settings.libraryPaths.JSPager;

  angular.module('JSPager', [])
    .directive('JSPager', function() {
      return {
        templateUrl: rootPath+'/pager.html',
        link: pagerLink,
        scope: {
          pageSize: '@pageSize',
          collectionLength: '=collectionLength'
        }
      };
    });


  function pagerLink(scope, iElement, iAttrs, controller) {
    scope.current = 1;

    scope.currentPage = currentPage;
    scope.numPages = numPages;
    scope.canPage = canPage;
    scope.changePage = changePage;

    function currentPage() {
      var pages = numPages();
      if (pages && scope.current > pages) {
        scope.current = pages;
      }
      return scope.current;
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