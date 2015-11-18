(function () {
  angular.module('ReportModule', [])
    .controller('SiteReport', [$http, function($http) {
      this.update = function(query) {
        var apiUrl = '/api/report_sites/attributes';
        $http({method : 'get', url: apiUrl, params: query}).
          then(function(response) {
          });         
      };
    }]);
})();