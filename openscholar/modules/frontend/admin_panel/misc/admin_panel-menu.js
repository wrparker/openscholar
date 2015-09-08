jQuery('#cssmenu li.active').addClass('open').children('ul').show();
			jQuery('#cssmenu li.has-sub > a').on('click', function() {
				jQuery(this).removeAttr('href');
				var element = jQuery(this).parent('li');
				if (element.hasClass('open')) {
					element.removeClass('open');
					element.find('li').removeClass('open');
					element.find('ul').slideUp(200);
				} else {
					element.addClass('open');
					element.children('ul').slideDown(200);
					element.siblings('li').children('ul').slideUp(200);
					element.siblings('li').removeClass('open');
					element.siblings('li').find('li').removeClass('open');
					element.siblings('li').find('ul').slideUp(200);
				}
			});

		
			jQuery("#topRightMenu").click(function() {
				if (jQuery('#rightMenuSlide').css('display') == 'none') {
					jQuery("#rightMenuSlide").slideDown();
				} else {
					jQuery("#rightMenuSlide").slideUp();
				}
			});