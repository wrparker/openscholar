<?php
/**
 * @file
 * os_booklets.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function os_booklets_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'view book content'.
  $permissions['view book content'] = array(
    'name' => 'view book content',
    'roles' => array(
      'viewer' => 'viewer',
    ),
    'module' => 'cp_user',
  );

  return $permissions;
}
