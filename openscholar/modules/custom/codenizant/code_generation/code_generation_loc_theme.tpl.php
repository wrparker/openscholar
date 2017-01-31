<?php
/**
* @file
* @Name: code_generation_loc_theme.tpl.php
* @created: 02/01/2013
* @Author: Cognizant
* @Version: 1.0
*
* Proprietary and Confidential
* Copyright (C) 2013 Cognizant Technology Solutions. All Rights Reserved.
**/

$no_comment_line_code = empty($form['no_comment_line_code']) ? 0 : $form['no_comment_line_code'];
$comment_line_code = empty($form['comment_line_code']) ? 0 : $form['comment_line_code'];
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Task', 'Hours per Day'],
	  ['NCLOC',   <?php print $no_comment_line_code; ?>],
	  ['CLOC',    <?php print $comment_line_code; ?>],
	]);

	var options = {
	  title: 'Comment of Code',
	  is3D : true
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
  }
</script>


<div class="dv_class">
<table width="90%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td align="left" valign="top" class="dv_table_td_class"><?php echo $form['loc_lines_of_code']; ?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="dv_table_td_class"><?php echo $form['loc_cyclomatic_complexity']; ?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="dv_table_td_class"><?php echo $form['loc_comment_lines_of_code']; ?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="dv_table_td_class"><?php echo $form['loc_non_comment_lines_of_code']; ?></td>
  </tr>
</table>
</div>

<div class="dv_clear"></div>
<div id="chart_div" class="chart_div"></div>
