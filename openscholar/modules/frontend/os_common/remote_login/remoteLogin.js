angular.module('remoteLogin', [])
  .controller('remoteLoginForm', function($scope, $http) {
    $scope.username = '';
    $scope.password = '';
    $scope.status = '';

    $scope.login = function() {

      $http.get(Drupal.settings.os_restful.parentUrl + '/api/login-token', {
        headers: {
          'Authorization': 'Basic ' + Base64.encode($scope.username + ':' + $scope.password)
        }
      })
      .success(function(data) {
        $scope.status = "Hi there! you are logged in! Fetching your information....";

        $http.get(Drupal.settings.os_restful.parentUrl + '/api/users/me', {
          headers: {
            'access-token': data.access_token
          }
        }).success(function(data) {
          $scope.status = 'OK! you are recognised as ' + data.data[0].label;
        });
      })
      .error(function(data) {
        $scope.status = 'Wow! Are you sure this is your username and password?';
      })
    };

  });
