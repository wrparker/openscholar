var siteCreationApp = angular.module('siteCreationApp', ["ngMessages"]);
siteCreationApp.controller('siteCreationCtrl', function($scope, $localStorage) {

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
  
  //Set status of next button based on input value
  $scope.checkEnable = function() {
    if ($scope.individualScholar || $scope.projectLabSmallGroup || $scope.departmentSchool){
      return false;
    }
    return true;
  }
  
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
siteCreationApp.directive('formcheckDirective', function() {
  var siteArray = ["test", "test1", "test2"]; //existing site name array
  return {
    require: 'ngModel',
    link: function(scope, element, attr, siteCreationCtrl) {
      function formValidation(ngModelValue) {
        if (siteArray.indexOf(ngModelValue) > -1) {
          siteCreationCtrl.$setValidity('sitename', false);
        } else {
          siteCreationCtrl.$setValidity('sitename', true);
        }
        return ngModelValue;
      }
      siteCreationCtrl.$parsers.push(formValidation);
    }
  };
});