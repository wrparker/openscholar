  WebFontConfig = {
    google: { families: [ 'Oswald:400,300,700:latin', 'Lora:400,700,400italic,700italic:latin' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })(); 

jQuery(document).ready(function(){

  //  var highestBox = 0;
     //   jQuery('.front-four-lops .front-lop-blockgrid').each(function(){  
             //   if(jQuery(this).height() > highestBox){   
            //    highestBox = jQuery(this).height();  
      //  }
   // });    
  //  jQuery('.front-four-lops .front-lop-blockgrid, .front-lop-software-tabs').height(highestBox);

jQuery("#block-os-primary-menu .nice-menu li.last a").click(function() {
   jQuery(".block-os-search-solr").toggleClass('open');
    });

});