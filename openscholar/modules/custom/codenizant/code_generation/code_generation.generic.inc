<?php
/**
 * @file
 * @Name: code_generation.generic.inc
 * @created: 02/01/2013
 * @Author: Cognizant
 * @Version: 1.0
 *
 * Proprietary and Confidential
 * Copyright (C) 2013 Cognizant Technology Solutions. All Rights Reserved.
 **/


$hook_defs = array();


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_generic_module() {
  $content = '<div id="code_generation_create_generic_module_form">';
  $content .= render(drupal_get_form('code_generation_create_generic_module_form'));
  $content .= '</div>';
  return $content;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_create_generic_module_form() {
  $form = array();
  $form['module_name'] = array(
    "#type" => "textfield",
    "#title" => t("Module name"),
    "#description" => t("Name of module to auto generate."),
    '#required' => TRUE,
  );
  $hooks = _code_generation_get_hook_names();
  $form['code_generation_hooks'] = array(
    "#type" => "select",
    "#multiple" => TRUE,
    "#title" => t("Create implementations of the following hooks"),
    "#options" => $hooks,
    "#description" => t("The selected hooks will be implemented in the simplest possible way, be prepared to add own stuff."),
    '#required' => TRUE,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Create'),
  );
  return $form;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_create_generic_module_form_validate($form, &$form_state) {
  $do_not_create_yet = file_default_scheme() . '://' . $form_state['values']['module_name'];
  $module_location = file_default_scheme() . '://' . $form_state['values']['module_name'] . '.zip';

  if ( file_prepare_directory($do_not_create_yet, !FILE_CREATE_DIRECTORY) || file_exists($module_location) ) {
    form_set_error('error ', t('Module file with the same name is already existinig. Please provide another name to proceed.'));
  }
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function code_generation_create_generic_module_form_submit($form, &$form_state) {
  global $hook_defs;
  require_once DRUPAL_ROOT . '/' . drupal_get_path('module', 'code_generation') . '/code_generation.common.inc';
  $template_path = GENERIC_TEMPLATES_PATH;

  $module_name = $form_state['values']['module_name'];
  $path = file_default_scheme() . '://' . $module_name;

  _code_generation_create_standard_module_files($module_name, $path);
  _code_generation_copy_templates($template_path, "info.template", $path, $module_name . ".info", array("%module_name" => $module_name));
  $hook_implementations = array();
  foreach ($form_state['values']['code_generation_hooks'] as $hook_name) {
    $hook_implementations[] = strtr($hook_defs[$hook_name], array("%module_name" => $module_name));
  }
  _code_generation_copy_templates($template_path, "module.template", $path, $module_name . ".module", array("%module_name" => $module_name, "%hook_implementations" => implode("\n", $hook_implementations)));

  $module_name = $form_state['values']['module_name'];
  $path = MODULE_PATH . '/' . $module_name;

  // Module path
  $zip_name = $module_name . '.zip';
  $zip_dir  = $module_name . '/';
  $zip_path = $_SERVER['DOCUMENT_ROOT'] . '/' . MODULE_PATH . '/';
  $zip_path_module = $_SERVER['DOCUMENT_ROOT'] . '/' . MODULE_PATH . '/' . $module_name;
  $zip_full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . MODULE_PATH . '/' . $zip_name;

  // Create module as zip
  $command = "cd $zip_path && zip -r $zip_name $zip_dir";
  exec($command);

  // Remove the original module folder
  $command = "rm -rf $zip_path_module";
  exec($command);

  // Save data
  // TODO Please review the conversion of this statement to the D7 database API syntax.
  /* db_query( "INSERT INTO {code_generation} (module_name, module_path, created_on) VALUES ('%s', '%s', '%s')", $module_name, $zip_full_path, REQUEST_TIME) */
  $id = db_insert('code_generation')
  ->fields(array(
    'module_name' => $module_name,
    'module_path' => $zip_full_path,
    'created_on' => REQUEST_TIME,
  ))
  ->execute();

  // Redirect
  unset($_SESSION['messages']);
  drupal_set_message(t("Module has been created successfully."));
  drupal_goto('admin/code_generation/view');
}


/**
 * Get the list of hook names.
 */
function _code_generation_get_hook_names() {
  global $hook_defs;
  $template_path = GENERIC_TEMPLATES_PATH;
  file_scan_directory($template_path, '/^hook_[A-Za-z_]+.template$/', array('callback' => '_code_generation_pass_hooks', 'recurse' => FALSE));
  $out = "";
  foreach ($hook_defs as $hook_name => $hook_def) {
    $out[$hook_name] = $hook_name;
  }
  
  return $out;
}


/**
 * Pass templates for known hooks
 */
function _code_generation_pass_hooks($file_name) {
  global $hook_defs; 
  $hook_name = substr($file_name, 
                    strlen(GENERIC_TEMPLATES_PATH . "/"), 
                    strlen($file_name) - strlen(GENERIC_TEMPLATES_PATH . "/") - strlen(".template"));
  $hook_defs[$hook_name] = file_get_contents($file_name);
}
