(function() {

angular.module('mediaBrowser.filters', [])
  .filter('mbFilename', function () {
    return function (input, search) {
      if (input && search) {
        var results;
        search = search.toLowerCase();

        if (Array.isArray(input)) {
          results = [];
          for (var i=0; i<input.length; i++) {
            if (input[i].name.toLowerCase().indexOf(search) !== -1) {
              results.push(input[i]);
            }
          }
        }
        else if (typeof input == 'object') {
          results = {};
          for (var key in input) {
            if (input[key].name.toLowerCase().indexOf(search) !== -1) {
              results[key] = input[key];
            }
          }
        }
        else {
          return input;
        }

        return results;
      }
      return input;
    }
  });

})();