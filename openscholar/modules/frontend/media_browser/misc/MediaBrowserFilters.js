(function() {

angular.module('mediaBrowser.filters', [])
  .filter('start', function () {
    return function (input, start) {
      if (input) {
        start = +start;
        return input.slice(start);
      }
      return '';
    }
  });

})();