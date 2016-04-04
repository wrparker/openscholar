<?php

/**
 * Contains static methods for vsite update 7032.
 */
class update implements osUpdateBatch {

  /**
   * @inheritdoc
   */
  public static function Query($id = NULL) {
    $query = new EntityFieldQuery();

    $query
      ->entityCondition('entity_type', 'node')
      ->propertyCondition('type', array_keys(vsite_vsite_og_node_type_info()), 'IN');
    //todo add check override field permissin and role override.
    if ($id) {
      $query->propertyCondition('nid', $id, '>=');
    }

    return $query;
  }

  /**
   * @inheritdoc
   */
  public static function Iterator($entity) {
    // todo move to a class variable.
    $nobel_roles = array('content editor', 'vsite admin');

    self::assignRoles($entity, $nobel_roles, array('add content to books'));
  }

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
