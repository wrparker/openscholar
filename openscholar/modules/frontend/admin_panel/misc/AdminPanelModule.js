(function ($) {
  var rootPath;

    angular.module('AdminPanel', [])
    .config(function (){
       rootPath = Drupal.settings.paths.adminPanelModuleRoot;
       restPath = Drupal.settings.paths.api;
       vsite = Drupal.settings.spaces.id;
       cid = Drupal.settings.admin_panel.cid;
       uid = Drupal.settings.admin_panel.user;
    }).controller("AdminMenuController",['$scope', '$http', function ($scope, $http) {
    
      menu = 'admin_panel';
      params = { cid: cid, uid: uid};
      if (vsite) {
        params.vsite = vsite;
      }
      
      var url = restPath + '/cp_menu/' + menu;
      $http({method: 'get', url: url, params: params, cache: true}).
        then(function(response) {
          $scope.admin_panel = response.data.data;
        }); 
     
    }]).directive('toggleOpen', function() {
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
    }).directive('leftMenu', function() {
      return {
       templateUrl: rootPath+'/templates/admin_menu.html?vers='+Drupal.settings.version.adminPanel,
       controller: 'AdminMenuController',
       link: function(scope, element, attrs) {
      	 new UIMorphingButton(element[0], {
      			closeEl : '.icon-close',
      			onBeforeOpen : function() {
      				// push main admin_panel
      				jQuery('#page_wrap').addClass('pushed');
      			},
      			onAfterOpen : function() {
      				// add scroll class to main el
      				jQuery('.morph-button').addClass('scroll');
      			},
      			onBeforeClose : function() {
      				jQuery('.morph-button').removeClass('scroll');
      				// push back main admin_panel
      				jQuery('#page_wrap').removeClass('pushed');
      			}
      		});
      	 
  	   }
     };
   }).directive('addLocation', function() {
	  //For Qualtrics URL Remove after beta
      return {
        link: function(scope, element, attrs) {
    	  element.attr('href', attrs.href + '?osurl=' + encodeURIComponent(location.href) + '&uid=' + uid);
        },
      }
    });
  
})(jQuery);
