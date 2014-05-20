//jQuery(document).ready(function() {
	//jQuery('.block-boxes-os_boxes_html:eq(1)').addClass('custhtml getsite');
	//jQuery('.block-boxes-os_boxes_html:eq(2)').addClass('custhtml whyuse');
   // jQuery('.block-boxes-os_boxes_html:eq(3)').addClass('custhtml sample');
   // jQuery('.block-boxes-os_boxes_html:eq(4)').addClass('custhtml about');
  // });
  

function equalHeight(group) {
   tallest = 0;
   group.each(function() {
      thisHeight = jQuery(this).height();
      if(thisHeight > tallest) {
         tallest = thisHeight;
      }
   });
   group.height(tallest);
}
jQuery(document).ready(function() {
   equalHeight(jQuery("#top-five-wrapper .top-five"));
});