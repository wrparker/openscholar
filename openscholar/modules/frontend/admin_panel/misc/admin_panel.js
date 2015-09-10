jQuery(document).ready(function ($) {
				var docElem = window.document.documentElement, didScroll, scrollPosition, admin_panel = document.getElementById('admin_panel');

				// trick to prevent scrolling when opening/closing button
				function noScrollFn() {
					window.scrollTo( scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0);
				}

				function noScroll() {
					window.removeEventListener('scroll', scrollHandler);
					window.addEventListener('scroll', noScrollFn);
				}

				function scrollFn() {
					window.addEventListener('scroll', scrollHandler);
				}

				function canScroll() {
					window.removeEventListener('scroll', noScrollFn);
					scrollFn();
				}

				function scrollHandler() {
					if (!didScroll) {
						didScroll = true;
						setTimeout(function() {
							scrollPage();
						}, 60);
					}
				};

				function scrollPage() {
					scrollPosition = {
						x : window.pageXOffset || docElem.scrollLeft,
						y : window.pageYOffset || docElem.scrollTop
					};
					didScroll = false;
				};

				scrollFn();

				var el = document.querySelector('.morph-button');

				new UIMorphingButton(el, {
					closeEl : '.icon-close',
					onBeforeOpen : function() {
						// don't allow to scroll
						noScroll();
						// push main admin_panel
						//classie.addClass(admin_panel, 'pushed');
						$('#admin_panel').addClass('pushed');
					},
					onAfterOpen : function() {
						// can scroll again
						canScroll();
						// add scroll class to main el
						//classie.addClass(el, 'scroll');
						$('.morph-button').addClass('scroll');
					},
					onBeforeClose : function() {
						// remove scroll class from main el
						//classie.removeClass(el, 'scroll');
						$('.morph-button').removeClass('scroll');
						// don't allow to scroll
						noScroll();
						// push back main admin_panel
						//classie.removeClass(admin_panel, 'pushed');
						$('#admin_panel').removeClass('pushed');
					},
					onAfterClose : function() {
						// can scroll again
						canScroll();
					}
				});
});


jQuery("#topRightMenu").click(function() {
	if (jQuery('#rightMenuSlide').css('display') == 'none') {
		jQuery("#rightMenuSlide").slideDown();
	} else {
		jQuery("#rightMenuSlide").slideUp();
	}
});