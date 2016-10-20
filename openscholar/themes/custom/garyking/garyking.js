 WebFontConfig = {
      google: {
          families: ['Oswald:400,300,700:latin', 'Quattrocento']
      }
  };
  (function() {
      var wf = document.createElement('script');
      wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
      wf.type = 'text/javascript';
      wf.async = 'true';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(wf, s);
  })();

  jQuery(document).ready(function() {

      var classes = ["bg-one", "bg-two", "bg-three", "bg-four", "bg-five", "bg-six", "bg-seven", "bg-eight"];

      jQuery("body.not-front").each(function() {
          jQuery(this).addClass(classes[~~(Math.random() * classes.length)]);
      });

      jQuery("#block-os-primary-menu .nice-menu li.last a").click(function() {
          jQuery(".block-os-search-solr").toggleClass('expose');
      });

jQuery(".research-by-topic ul .item-list ul li a").after("<span></span>");
jQuery(".research-by-topic ul .item-list ul li .description a").after(" ");

    //  jQuery(".research-by-topic ul .item-list ul li span").click(function() {
      //    jQuery(".research-by-topic ul .item-list ul li").toggleClass('open');
    //  });



jQuery('.research-by-topic ul .item-list ul li span').click(function() {
    if (jQuery('.research-by-topic ul .item-list ul li').hasClass('open')){
        jQuery('.research-by-topic ul .item-list ul li').removeClass('open');  
    } else {
        jQuery('.research-by-topic ul .item-list ul li').addClass('open');
      }
});




      jQuery(".research-by-topic a").removeAttr("title");


  });