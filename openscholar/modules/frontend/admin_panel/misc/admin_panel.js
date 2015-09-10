jQuery(document).ready(function ($) {
 
  var el = document.querySelector('.morph-button');
  new UIMorphingButton(el, {closeEl : '.icon-close',});

})

jQuery("#topRightMenu").click(function() {
	if (jQuery('#rightMenuSlide').css('display') == 'none') {
		jQuery("#rightMenuSlide").slideDown();
	} else {
		jQuery("#rightMenuSlide").slideUp();
	}
});