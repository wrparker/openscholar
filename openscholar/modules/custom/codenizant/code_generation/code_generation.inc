<?php
/**
 * @file
 * @Name: code_generation.inc
 * @created: 02/01/2013
 * @Author: Cognizant
 * @Version: 1.0
 *
 * Proprietary and Confidential
 * Copyright (C) 2013 Cognizant Technology Solutions. All Rights Reserved.
 **/


/*
 * View modules
 */
/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_view_module() {

  global $base_url;

  $header = array(
    array(
      'data' => t('Module name'),
      'class' => '',
    ),
    array(
      'data' => t('Created on'),
      'class' => '',
    ),
    array(
      'data' => t('Action'),
      'class' => '',
    ),
  );

  $sql_count = "SELECT COUNT(1) FROM {code_generation}";
  $res_count = db_query( $sql_count )->fetchField();
  $sql = "SELECT * FROM {code_generation} ORDER BY id DESC";
  $rows = array();

  if ( $res_count > 0 ) {

    $result = db_query( $sql );
    foreach ( $result as $result_row) {


      $rows[] = array(
        array(
          'data' => $result_row->module_name,
          'class' => '',
        ),
        array(
          'data' => format_date($result_row->created_on, 'custom', 'Y-m-d H:i:s' ),
          'class' => '',
        ),
        array(
          'data' => l('Download', 'admin/code_generation/download/' . $result_row->id),
          'class' => '',
        ),
      );
    }
  }
  else {
    $rows[] = array(array(
        'data' => t('No modules found.'),
        'colspan' => 3,
        'class' => 'txt-align-cntr',
      ));
  }

  $variables['header'] = $header;
  $variables['rows'] = $rows;
  
  // TODO Please change this theme call to use an associative array for the $variables parameter.
  $output  = theme( 'table', $variables );
  // TODO Please change this theme call to use an associative array for the $variables parameter.
  // $output .= theme( 'pager', NULL, ROWS_PER_PAGE, 0 );

  return $output;
}


/*
 * Download modules
 */
/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_download_module() {

  $table_id = arg('3');
  global $user;

  $filepath = db_query("SELECT module_path FROM {code_generation} WHERE id = :id", array(':id' => $table_id))->fetchField();

  if ($filepath != '') {
    $info = explode('/', $filepath);

    //Check if the file exists and user is logged in to download the file
    if ( file_exists($filepath) && $user->uid ) {
      header("Content-Type: application/force-download\n");
      header("Content-Disposition: attachment; filename="  . $info[count($info) -1]);
      @readfile($filepath);
    }
    else {
      drupal_set_message(t("There is a problem to download the file. Please try again later."));
      drupal_goto('admin/code_generation/view');
    }
  }
  else {
    drupal_set_message(t("There is a problem to download the file. Please try again later."));
    drupal_goto('admin/code_generation/view');
  }
}


/**
 * Lines of code of modules
 */
function code_generation_lines_of_code_module() {

  $dir_path = $_SERVER['DOCUMENT_ROOT'] . '/' . DRUPAL_MODULE_PATH . '/';

  $output = shell_exec("phploc --suffixes " . SHELL_COMMAND_EXTENSIONS . " $dir_path");

  $output_array  = explode("\n", $output);

  foreach ($output_array as $key => $value) {
    if (empty($value)) {
      unset($output_array[$key]);
    }
  }

  reset($output_array);

  // Detailed output
  $loc_directories = $output_array[2];
  $loc_files = $output_array[3];
  $loc_lines_of_code = $output_array[5];
  $loc_cyclomatic_complexity = $output_array[6];
  $loc_comment_lines_of_code = $output_array[7];
  $loc_non_comment_lines_of_code = $output_array[8];

  $loc = preg_replace("/[^0-9]/", "",   $output_array[5]);
  $comment_line_code = preg_replace("/[^0-9]/", "",   $output_array[7]);
  $no_comment_line_code = preg_replace("/[^0-9]/", "",   $output_array[8]);

  $form['loc_directories'] = $loc_directories;
  $form['loc_files'] = $loc_files;
  $form['loc_lines_of_code'] = $loc_lines_of_code;
  $form['loc_cyclomatic_complexity'] = $loc_cyclomatic_complexity;
  $form['loc_comment_lines_of_code'] = $loc_comment_lines_of_code;
  $form['loc_non_comment_lines_of_code'] = $loc_non_comment_lines_of_code;
  $form['loc'] = $loc;
  $form['comment_line_code'] = $comment_line_code;
  $form['no_comment_line_code'] = $no_comment_line_code;
  $form['module_description'] = ALLOWED_FILE_EXTENSIONS;

  $theme = theme('code_generation_loc_module', array('form' => $form));

  return $theme;
}


/**
 * Lines of code of theme
 */
function code_generation_lines_of_code_theme() {

  $dir_path = $_SERVER['DOCUMENT_ROOT'] . '/' . DRUPAL_THEME_PATH . '/';

  $output = shell_exec("phploc --suffixes " . SHELL_COMMAND_EXTENSIONS . " $dir_path");

  $output_array  = explode("\n", $output);

  foreach ($output_array as $key => $value) {
    if (empty($value)) {
      unset($output_array[$key]);
    }
  }

  reset($output_array);
  
 //echo "<pre>";
 //print_r($output_array);exit();

  // Detailed output
  //$loc_directories = $output_array[2];
  //$loc_files = $output_array[3];
  $loc_lines_of_code = $output_array[2];
  $loc_cyclomatic_complexity = $output_array[3];
  $loc_comment_lines_of_code = $output_array[4];
  $loc_non_comment_lines_of_code = $output_array[5];

  $loc = preg_replace("/[^0-9]/", "",   $output_array[2]);
  $comment_line_code = preg_replace("/[^0-9]/", "",   $output_array[4]);
  $no_comment_line_code = preg_replace("/[^0-9]/", "",   $output_array[5]);

  //$form['loc_directories'] = $loc_directories;
  //$form['loc_files'] = $loc_files;
  $form['loc_lines_of_code'] = $loc_lines_of_code;
  $form['loc_cyclomatic_complexity'] = $loc_cyclomatic_complexity;
  $form['loc_comment_lines_of_code'] = $loc_comment_lines_of_code;
  $form['loc_non_comment_lines_of_code'] = $loc_non_comment_lines_of_code;
  $form['loc'] = $loc;
  $form['comment_line_code'] = $comment_line_code;
  $form['no_comment_line_code'] = $no_comment_line_code;
  $form['theme_description'] = ALLOWED_FILE_EXTENSIONS;

  $theme = theme('code_generation_loc_theme', array('form' => $form));

  return $theme;
}


/**
 * Lines of code of custom
 */
function code_generation_lines_of_code_custom($form,  &$form_state ) {

  $dir_path = $_SERVER['DOCUMENT_ROOT'] . '/' . DRUPAL_CUSTOM_PATH . '/';
  $ext_array = array('php', 'module', 'inc', 'install', 'info', 'js', 'css', 'html');
  
  foreach ($ext_array as $ext_value) {
    $output = shell_exec("find  $dir_path -name '*.$ext_value' | xargs wc -l");

    $form['extension_' . $ext_value] = array(
      '#type' => 'fieldset',
      '#title' => 'Extension: .' . $ext_value,
      '#weight' => 5,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    
    $output_arr  = explode("\n", $output);
    $output_array = array_slice($output_arr, 0, count($output_arr)-1);

    foreach ($output_array as $value) {
      $form['extension_' . $ext_value][$value] = array(
        '#type' => 'markup',
        '#markup' => '<div>' . $value . '</div><div style="clear:both;"></div>',
      );

      if (strpos($value, 'total')) {
        if ( preg_match_all('/\d+/', $value, $matches) ) {
          $value  = $matches[0][0];
        }
        $form['#' . $ext_value] = empty($value) ? 0 : $value;
      }
    }
  }

  return $form;
}
