(function () {
  var reportModule = angular.module('ReportModule', []);
  reportModule.controller('SiteReportQuery', ['$scope','$http', function ($scope, $http) {

    $scope.update = function(query) {
      var queryString = "?" + jQuery.param(query);

      console.log(queryString);

      var request = {
        method: 'GET',
        url : 'http://rsa.hwpi.harvard.edu/api/report_sites/attributes' + queryString,
        headers : {
          'Content-Type' : 'application/json'
        },
        data : query,
      };

      $http(request).then(function(response) {
        $scope.headers = [];

        var responseData = angular.fromJson(response.data.data);
        var key;
        for (key in responseData[0]) {
          $scope.headers.push(key);
        }
        $scope.rows = responseData;
      }, 
      // error
      function() {
      });
    };    
  }]);
})();
