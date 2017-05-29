var siteCreationApp = angular.module('siteCreationApp', ["ngMessages"]);
siteCreationApp.controller('siteCreationCtrl', function($scope, $localStorage, $http, $location) {

  //Set default value for vsite
  $scope.vsite = {
    value: '0'
  };
  
  //Toggle open/close for 'who can view your site'
  $scope.showAll = false;
  $scope.toggleFunc = function() {
    $scope.showAll = !$scope.showAll;
  };
  
  //Reset value for other 'Type of site' based on selection
  $scope.clearRest = function(field) {
    if (field != 'individualScholar') {
      $scope.individualScholar = null;
    }
    if (field != 'projectLabSmallGroup') {
      $scope.projectLabSmallGroup = null;
    }
    if (field != 'departmentSchool') {
      $scope.departmentSchool = null;
    }
  }
  
  //Set status of next button to disabled initially
  $scope.btnDisable = true;
  
  //Navigate between screens
  $scope.page1 = true;
  $scope.navigatePage = function(pagefrom, pageto) {
    $scope[pagefrom] = false;
    $scope[pageto] = true;
  }
  
  //Set default value for Content Option
  $scope.contentOption = {
    value: '0'
  };

  //Site URL
  $scope.baseURL = $location.protocol() + '://' + $location.host();
  
  //Get all values and save them in localstorage for use
  $scope.saveAllValues = function() {
    var data = {};
    data["individualScholar"] = $scope.individualScholar;
    data["projectLabSmallGroup"] = $scope.projectLabSmallGroup;
    data["departmentSchool"] = $scope.departmentSchool;
    data["vsite"] = $scope.vsite.value;
    data["contentOption"] = $scope.contentOption.value;

    $localStorage.formData = data;
    console.log($localStorage.formData);
  }
  
});

//Validate form for existing site names
siteCreationApp.directive('formcheckDirective', ['$http', function($http) {
  var responseData;
  return {
    require: 'ngModel',
    link: function(scope, element, attr, siteCreationCtrl) {
      function formValidation(ngModelValue) {
        siteCreationCtrl.$setValidity('isinvalid', true);
        siteCreationCtrl.$setValidity('sitename', true);
        scope.btnDisable = true;
        if(ngModelValue){
          //Ajax call to get all existing sites
          $http({
            method : "GET",
            url : "sitecreation/validate/"+ngModelValue,
          }).then(function mySuccess(response) {
            responseData = response.data;
            if (responseData == "Invalid"){
              siteCreationCtrl.$setValidity('isinvalid', false);
            }
            else if (responseData == "Not-Available") {
              siteCreationCtrl.$setValidity('sitename', false);
            }
            else{
              scope.btnDisable = false;
            }
            return ngModelValue;
          });
        }
      }
      siteCreationCtrl.$parsers.push(formValidation);
    }
  };
}]);