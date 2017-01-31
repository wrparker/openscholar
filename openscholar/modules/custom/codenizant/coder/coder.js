jQuery(document).ready(function(){
 

jQuery('#snapshot-list-table tbody tr td').click(function(){
	location.href = jQuery(this).find('a').attr('href');
}); 


if(Drupal.settings.coder.filter_report){
jQuery('.coder-warning').each(function(i){
  if(!jQuery(this).hasClass('coder-' + Drupal.settings.coder.filter_report))
  jQuery(this).parent().remove();
});
}


if(getURLParameter('normal') == '1'){
jQuery('.coder-warning').each(function(i){
  if(!jQuery(this).hasClass('coder-normal'))
  jQuery(this).parent().remove();
});  
}

if(getURLParameter('critical') == '1'){
jQuery('.coder-warning').each(function(i){
  if(!jQuery(this).hasClass('coder-critical'))
  jQuery(this).parent().remove();
});  
}


jQuery('.item-list').each(function(i){
if(jQuery.trim(jQuery(this).find('ul').html()) == '' ){
jQuery(this).html('No issues found.');
}

});

if(Drupal.settings.coder.cachedata == '1'){
jQuery('<div id="tabs-wrapper" class="clear-block"><ul class="tabs primary"><li id="report_loading" style="display:none;"><img src="'+Drupal.settings.basePath+ Drupal.settings.coder.coder_module_path + '/images/report-load.gif" />&nbsp;Saving snapshot...</li></ul></div>').insertAfter(jQuery('.messages'));
execute_snapshot();
}


jQuery('.snapshot_del_link').each(function(i){

var link = jQuery(this).attr('href');
jQuery(this).click(function(event){
event.preventDefault();
if(confirm('Do you want to delete the snapshot?')){
	location.href = link;	
}
return false;
});

});





});




function execute_snapshot(){

jQuery('#report_loading').show();
	jQuery.ajax({
		url:Drupal.settings.basePath + 'save_archive_data',
		type:'post',
		dataType:'json',
		data:{'report_html':jQuery('#coder-page-form').html(),'project_name':Drupal.settings.coder.modulename,'module_count':jQuery('#module_count').html(),'files_count':jQuery('#files_count').html(),'critical_count':jQuery('#critical_count').html(),'normal_count':jQuery('#normal_count').html()},
		success:function(jData){
		
				if(jData.updated){
					jQuery('#report_loading').html('Snapshot saved successfully').fadeOut(10000);						
				}
			}
});


}



if (Drupal.jsEnabled) {
  jQuery.fn.extend({
    check : function() { return this.each(function() { this.checked = true; }); },
    uncheck : function() { return this.each(function() { this.checked = false; }); }
  });

  jQuery(document).ready(
  
  
 
  
  
  
    function() {
      jQuery("input:checkbox").click(
        function() {
          core = this.form.elements.namedItem("edit-coder-core");
          active = this.form.elements.namedItem("edit-coder-active-modules");
          if (this == core || this == active) {
            modules = "input[id^=edit-coder-modules-]";
            themes = "input[id^=edit-coder-themes-]";
            if (core.checked || active.checked) {
              jQuery(modules).uncheck();
              jQuery(themes).uncheck();
              if (core.checked) {
                modules += '.coder-core';
                themes += '.coder-core';
              }
              if (active.checked) {
                modules += '.coder-active';
                themes += '.coder-active';
              }
              jQuery(modules).check();
              jQuery(themes).check();
            }
            else {
              if (this == active) {
                modules += ".coder-active";
                themes += ".coder-active";
              }
              else {
                modules += ".coder-core";
                themes += ".coder-core";
              }
              jQuery(modules).uncheck();
              jQuery(themes).uncheck();
            }
          }
          else if (this.id.substr(0, 19) == "edit-coder-modules-" || this.id.substr(0, 18) == "edit-coder-themes-") {
            core.checked = false;
            active.checked = false;
          }
          return true;
        }
      );
      jQuery("img.coder-more").click(
        function() {
          jQuery('.coder-description', this.parentNode).slideToggle();
        }
      );
    }
  );
}


function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}


