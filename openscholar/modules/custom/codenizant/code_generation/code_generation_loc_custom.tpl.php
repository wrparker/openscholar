<?php
/**
* @file
* @Name: code_generation_loc_custom.tpl.php
* @created: 02/01/2013
* @Author: Cognizant
* @Version: 1.0
*
* Proprietary and Confidential
* Copyright (C) 2013 Cognizant Technology Solutions. All Rights Reserved.
**/

$loc_php = empty($form['#php']) ? 0 : $form['#php'];
$loc_module = empty($form['#module']) ? 0 : $form['#module'];
$loc_inc = empty($form['#inc']) ? 0 : $form['#inc'];
$loc_install = empty($form['#install']) ? 0 : $form['#install'];
$loc_info = empty($form['#info']) ? 0 : $form['#info'];
$loc_js = empty($form['#js']) ? 0 : $form['#js'];
$loc_css = empty($form['#css']) ? 0 : $form['#css'];
$loc_html = empty($form['#html']) ? 0 : $form['#html'];
?>


<div>
<?php  print drupal_render_children($form); ?>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day', 'Hours per Day'],
	  ['PHP',    <?php print $loc_php; ?>, <?php print $loc_php; ?>],
	  ['MODULE', <?php print $loc_module; ?>, <?php print $loc_module; ?>],
	  ['INC',    <?php print $loc_inc; ?>, <?php print $loc_inc; ?>],
	  ['INSTALL',<?php print $loc_install; ?>, <?php print $loc_install; ?>],
	  ['INFO',   <?php print $loc_info; ?>, <?php print $loc_info; ?>],
	  ['JS',     <?php print $loc_js; ?>, <?php print $loc_js; ?>],
	  ['CSS',    <?php print $loc_css; ?>, <?php print $loc_css; ?>],
	  ['HTML',   <?php print $loc_html; ?>, <?php print $loc_html; ?>],
	]);

	var options = {
	  title: 'Lines of Code',
	  is3D : true
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
  }
</script>
<div class="dv_clear"></div>
<div id="chart_div" class="chart_div"></div>
<div class="dv_clear"></div>