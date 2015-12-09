<?php
/**
 * @file
 * os_reports.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function os_reports_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access reports'.
  $permissions['access reports'] = array(
    'name' => 'access reports',
    'roles' => array(
      'report admin' => 'report admin',
    ),
    'module' => 'os_reports',
  );

  return $permissions;
}
