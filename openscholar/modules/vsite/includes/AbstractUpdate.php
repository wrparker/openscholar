<?php

/**
 * @file
 * Contains AbstractUpdate.php
 */

abstract class AbstractUpdate implements osUpdateBatch {

  static $nobelRoles = array('content editor', 'vsite admin');

  /**
   * Helper function to assign roles. For a much more clear code.
   *
   * @param $entity
   *   The vsite object.
   * @param $granted_roles
   *   The roles which will be granted with the roles.
   * @param $permission
   *   The permission to grant.
   */
  static protected function assignRoles($entity, $granted_roles, $permission) {
    $roles = og_roles('node', $entity->type, $entity->nid);

    foreach ($roles as $rid => $role) {
      if (!in_array($role, $granted_roles)) {
        continue;
      }

      og_role_grant_permissions($rid, $permission);
    }
  }

}
