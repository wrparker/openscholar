(function ($) {
  var rootPath,
    open = angular.noop;

    angular.module('AdminPanel', [])
    .config(function (){
       rootPath = Drupal.settings.paths.adminPanelModuleRoot;
       restPath = Drupal.settings.paths.api;
       vsite = Drupal.settings.spaces.id;
    }).controller("MenuCtrl", function($scope, $http) {
    
      menu = 'admin_panel';
      params = {};
      if (vsite) {
        params.vsite = vsite;
      }
      
      var url = restPath + '/cp_menu/' + menu;
      $http.get(url, {params: params}).
        success(function(data, status, headers, config) {
          $scope.admin_panel = data.data;
        });
      
      return {
        templateUrl: rootPath+'/templates/admin_menu.html?vers='+Drupal.settings.version.adminPanel,
      };
     
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
