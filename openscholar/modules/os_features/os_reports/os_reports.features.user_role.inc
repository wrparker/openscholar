<?php
/**
 * @file
 * os_reports.features.user_role.inc
 */

/**
 * Implements hook_user_default_roles().
 */
function os_reports_user_default_roles() {
  $roles = array();

  // Exported role: support team.
  $roles['report admin'] = array(
    'name' => 'report admin',
    'weight' => 9,
  );

  return $roles;
}
