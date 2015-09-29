(function ($) {
  var rootPath;

    angular.module('AdminPanel', [ 'os-auth', 'ngCookies'])
    .config(function (){
       paths = Drupal.settings.paths
       vsite = Drupal.settings.spaces.id;
       cid = Drupal.settings.admin_panel.cid;
       uid = Drupal.settings.admin_panel.user;
    }).controller("AdminMenuController",['$scope', '$http', '$cookieStore', function ($scope, $http, $cookieStore) {
    
      var menu = 'admin_panel';
      $scope.paths = paths;
      
      params = { cid: cid, uid: uid};
      if (vsite) {
        params.vsite = vsite;
      }
      
      var url = paths.api + '/cp_menu/' + menu;
      $http({method: 'get', url: url, params: params, cache: true}).
        then(function(response) {
          $scope.admin_panel = response.data.data;
        }); 
      
      $scope.getListStyle = function(id) { 
    	var menu_state = $cookieStore.get('osAdminMenuState');
    	if (typeof(menu_state) !== 'undefined' && typeof(menu_state[id]) !== 'undefined' && menu_state[id]) {
    	  return {'display':'block'}; 
    	}
    	return {};
      };
     
    }]).directive('toggleOpen', ['$cookieStore', function($cookieStore) {
    
      var menu_state = $cookieStore.get('osAdminMenuState');
      if (typeof(menu_state) == 'undefined') {
        menu_state = {'main': false};  
      }
      
      openLink = function(elm) {
    	elm.addClass('open');
    	elm.children('ul').slideDown(200);
      }
      
      closeLink  = function(elm) {
    	elm.removeClass('open');
    	elm.find('li').removeClass('open');
    	elm.find('ul').slideUp(200);
      }
      
      return {
        link: function(scope, element, attrs) {
    	  if (element[0].nodeName == 'UL') {
    		if (element.parent('li').hasClass('open')) {
    		  element.css('display','block');	
    		}
    		return;
    	  }
    	  
    	  if (typeof(menu_state[attrs.id]) !== 'undefined' && menu_state[attrs.id]) {
    		element.parent('li').addClass('open');
    	  }
    	  
    	  element.bind('click', function() {
        	if (element.hasClass('toggleable')){
              element.removeAttr('href');
              var parent = jQuery(element).parent('li');
              if (parent.hasClass('open')) {
                menu_state[attrs.id] = false;
                closeLink(parent);
	          } else {
	            menu_state[attrs.id] = true;
	            openLink(parent);
	          }
        	}
        	scope.$apply(function () {
        	  $cookieStore.put('osAdminMenuState',menu_state);
            });
          })  
        },
      }
      
    }]).directive('leftMenu', ['$cookieStore', function($cookieStore) {
      var menu_state = $cookieStore.get('osAdminMenuState');
      if (typeof(menu_state) == 'undefined') {
        menu_state = {'main': false};  
      }
      
      return {
       templateUrl: paths.adminPanelModuleRoot+'/templates/admin_menu.html?vers='+Drupal.settings.version.adminPanel,
       controller: 'AdminMenuController',
       link: function(scope, element, attrs) {
      	  button = new UIMorphingButton(element[0], {
      			closeEl : '.icon-close',
      			onBeforeOpen : function() {
      				// push main admin_panel
      				jQuery('#page_wrap').addClass('pushed');
      			},
      			onAfterOpen : function() {
      			  // add scroll class to main el
      			  jQuery('.morph-button').addClass('scroll');
      			  menu_state['main'] = true;
      			  scope.$apply(function () {
        	        $cookieStore.put('osAdminMenuState',menu_state);
        	      });
      			},
      			onBeforeClose : function() {
      			  jQuery('.morph-button').removeClass('scroll');
      			  // push back main admin_panel
      			  jQuery('#page_wrap').removeClass('pushed');
      			  menu_state['main'] = false;
        		  scope.$apply(function () {
          	        $cookieStore.put('osAdminMenuState',menu_state);
          	      });
      			}
      		});
      	 if (menu_state.main) {
      	   button.toggle();
      	 }
  	   }
     };
   }]).directive('addLocation', function() {
	  //For Qualtrics URL Remove after beta
      return {
        link: function(scope, element, attrs) {
    	  element.attr('href', attrs.href + '?osurl=' + encodeURIComponent(location.href) + '&uid=' + uid);
        },
      }
    });
  
})(jQuery);
