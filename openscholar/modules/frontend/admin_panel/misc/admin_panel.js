jQuery(document).ready(function($) {

	var el = document.querySelector('.morph-button');
	new UIMorphingButton(el, {
	closeEl : '.icon-close',
	onBeforeOpen : function() {
		// push main admin_panel
		$('#page_wrap').addClass('pushed');
	},
	onAfterOpen : function() {
		// add scroll class to main el
		$('.morph-button').addClass('scroll');
	},
	onBeforeClose : function() {
		$('.morph-button').removeClass('scroll');
		// push back main admin_panel
		$('#page_wrap').removeClass('pushed');
	}
	});

})

jQuery("#topRightMenu").click(function() {
	if (jQuery('#rightMenuSlide').css('display') == 'none') {
		jQuery("#rightMenuSlide").slideDown();
	} else {
		jQuery("#rightMenuSlide").slideUp();
	}
});