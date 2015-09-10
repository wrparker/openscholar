(function ($) {
  var rootPath,
    open = angular.noop;

    angular.module('AdminPanel', [])
    .config(function (){
       rootPath = Drupal.settings.paths.moduleRoot;
    }).controller("MenuCtrl", function($scope, $http) {
      $scope.someContent = "RWB Rules";
      $http.get('/api/cp_menu/admin_panel?vsite=2516').
        success(function(data, status, headers, config) {
          $scope.admin_panel = data.data;
        });
    }).directive('toggleOpen', function() {
      return {
        link: function(scope, element, attrs) {
    	  element.bind('click', function() {
        	  if (element.hasClass('toggleable')){
                element.removeAttr('href');
                var parent = jQuery(element).parent('li');
                if (parent.hasClass('open')) {
                	parent.removeClass('open');
                	parent.find('li').removeClass('open');
                	parent.find('ul').slideUp(200);
	            } else {
	            	parent.addClass('open');
	            	parent.children('ul').slideDown(200);
	            	parent.siblings('li').children('ul').slideUp(200);
	            	parent.siblings('li').removeClass('open');
	            	parent.siblings('li').find('li').removeClass('open');
	            	parent.siblings('li').find('ul').slideUp(200);
	            }
        	  }
          })  
        },
      }
    });;
    
})(jQuery);
