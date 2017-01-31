<?php
/**
 * @file
 * @Name: code_generation.common.inc
 * @created: 02/01/2013
 * @Author: Cognizant
 * @Version: 1.0
 *
 * Proprietary and Confidential
 * Copyright (C) 2013 Cognizant Technology Solutions. All Rights Reserved.
 **/


define('COMMON_TEMPLATES_PATH', drupal_get_path('module', 'code_generation') . '/templates/common');


function _code_generation_create_standard_module_files($module_name, $path, $template_path = COMMON_TEMPLATES_PATH) {
  // Write the standard module files for
  $file_mime = "text/plain";
  if (!file_prepare_directory($path, FILE_CREATE_DIRECTORY)) {
    form_set_error('path', '"' . $path . t('" is not a valid file path. System must be able to write to #full_path', array('#full_path' => $path)));
    return;
  }
  // LICENSE.txt
  _code_generation_copy_templates($template_path, "LICENSE.txt", $path, "LICENSE.txt");
  // CHANGELOG.txt
  _code_generation_copy_templates($template_path, "CHANGELOG.txt", $path, "CHANGELOG.txt", array("%module_name" => $module_name));
  // README.txt
  _code_generation_copy_templates($template_path, "README.txt", $path, "README.txt", array("%module_name" => $module_name));
  // modulename.install
  _code_generation_copy_templates($template_path, "install.template", $path, $module_name . ".install", array("%module_name" => $module_name));
}


/**
 * Copy the template files and make the appropriate string substitutions
 */
function _code_generation_copy_templates($template_path, $src_file_name, $dst_file_path, $dst_file_name, $substitutions = array()) {
  $template = file_get_contents($template_path . "/" . $src_file_name);
  $template_file = tempnam(file_directory_temp(), 'tmp_');
  if ($dst = fopen($template_file, "w")) {
    fwrite($dst, strtr($template, $substitutions));
    fclose($dst);
  }

  chmod($template_file, 0755);
  rename($template_file, $dst_file_path . "/" . $dst_file_name);
}
