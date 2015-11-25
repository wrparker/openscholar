(function () {
  var reportModule = angular.module('ReportModule', ['os-auth']);
  reportModule.controller('SiteReportQuery', ['$http', '$scope', function ($http, $scope) {

    $scope.updateCheckedValues = function($set, $value) {
      if (!$scope.params) {
        $scope.params = new Object();
      }
      $checked = eval ("$scope.query." + $set + "." + $value);

      if ($checked && $scope.params[$set]) {
        $scope.params[$set].push($value);
      }
      else if ($checked) {
        $scope.params[$set] = [$value];
      }
      else if ($scope.params[$set]) {
        angular.forEach($scope.params[$set], function($val, $index) {
          if ($val == $value) {
            delete $scope.params[$set][$index];
          }
        });
        if ($scope.params[$set].length == 0) {
          delete $scope.params[$set];
        }
      }
    };

    $scope.updateParam = function($field) {
      if (!$scope.params) {
        $scope.params = new Object();
      }

      $value = eval ("$scope.query." + $field);

      if ($value) {
        $scope.params[$field] = $value;
      }
      else if ($scope.params[$field]) {
        delete $scope.params[$field];
      }
    };

    $scope.update = function update($query) {
      $scope.headers = [];
      $scope.rows = [];

      if ($scope.params && $scope.params.lastupdate) {
        $scope.params.exclude = ['feed_importer', 'profile', 'harvard_course'];
      }

      var $request = {
        method: 'POST',
        url : Drupal.settings.paths.api + '/report_sites/attributes',
        headers : {'Content-Type' : 'application/json'},
        data : $scope.params,
      };

      $http($request).then(function($response) {
        var $responseData = angular.fromJson($response.data.data);
        var $keys = [];

        // get table headers from returned data
        for ($key in $responseData[0]) {
          if ($key && $key != "site_url") {
            $scope.headers.push($key);
          }
        }
        $scope.rows = $responseData;
      }, 
      // error
      function() {
      });
    };    

    $scope.sort = function sort($obj) {
      if ($scope.params.sort && ($scope.params.sort == $obj.header)) {
        $scope.params.sort = "-" + $obj.header;
      }
      else {
        $scope.params.sort = $obj.header;
      }

      $scope.update($scope.query);
    };
  }]);

  reportModule.filter('makelink', ['$sce', function($sce) {
    return function($value, $header, $row) {
      if ($header == "site_name" && $value) {
        $html = '<a href="' + $row['site_url'] + '" target="_new">' + $value + '</a>'
        return $sce.trustAsHtml($html);
      }
      else if ($header == "site_name") {
        $html = '<a href="' + $row['site_url'] + '" target="_new">[No Title]</a>'
        return $sce.trustAsHtml($html);
      }
      else {
        return $sce.trustAsHtml($value);
      }
    };
  }]);

  reportModule.filter('formatHeader', function($sce) {
    return function($header) {
      return $header.replace(/_/g, " ");
    };
  });

  var PagingModule = angular.module('PagingModule', ['ui.bootstrap']);

  PagingModule.controller('PaginationCtrl', function ($scope) {
    $scope.currentPage = 1;
    $scope.setPage = function () {
    };
  });


})();
