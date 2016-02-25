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
        $scope.status = "Hi there! you are logged in!";
      })
      .error(function(data) {
        $scope.status = 'Wow! Are you sure this is your username and password?'
      })
    };

  });
