(function ($) {
  var rootPath;

    angular.module('UserPanel', ['AdminPanel'])
    .config(function (){
       rootPath = Drupal.settings.paths.adminPanelModuleRoot;
       restPath = Drupal.settings.paths.api;
       vsite = Drupal.settings.spaces.id;
       user_data = Drupal.settings.user_panel.user;
       paths = Drupal.settings.paths;
    }).controller("UserMenuController",['$scope', '$http', function ($scope, $http) {
    
      $scope.user = user_data;
      $scope.vsite = { id: vsite };
      $scope.paths = paths;
     
    }]).directive('rightMenu', function() {
      return {
       templateUrl: rootPath+'/templates/user_menu.html?vers='+Drupal.settings.version.userPanel,
       controller: 'UserMenuController',
     };
    }).directive('addDestination', function() {
      //Add the destination to a URL
	  return {
	    link: function(scope, element, attrs) {
		  var url = (typeof(attrs.href) == 'undefined') ? attrs.ngHref : attrs.href;
	      element.attr('href', url + ((url.indexOf('?') == -1) ? '?' : '&') + 'destination=' + encodeURIComponent(location.href));
  	    },
	  }
    }).directive('rightMenuToggle', function() {
        return {
            link: function(scope, element, attrs) {
        	  element.bind('click', function(e) {
        		  e.preventDefault();
        		  element.siblings('div').each( function () {
        	        if(this.style.display == 'none') {
        	          jQuery(this).slideDown();	
        	        } else {
        	          jQuery(this).slideUp();
        	        }
        	      });
              })  
            },
          }
        });
  
})(jQuery);
