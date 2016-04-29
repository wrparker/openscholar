<?php
/**
 * @file
 * cp_user.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function cp_user_user_default_permissions() {
  $permissions = array();
  // Exported permission: 'access viewer app'.
  $permissions['access viewer app'] = array(
    'name' => 'access viewer app',
    'roles' => array(
      'viewer' => 'viewer',
    ),
    'module' => 'cp_user',
  );

  return $permissions;
}
