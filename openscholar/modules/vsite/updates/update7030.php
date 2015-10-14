<?php

/**
 * Contains static methods for vsite update 7030.
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

    if ($id) {
      $query->propertyCondition('nid', $id, '>=');
    }

    return $query;
  }

  /**
   * @inheritdoc
   */
  public static function Iterator($entity) {
    $granted_roles = array('content editor', 'vsite admin');
    $roles = og_roles('node', $entity->type, $entity->nid);

    foreach ($roles as $rid => $role) {
      if (!in_array($role, $granted_roles)) {
        continue;
      }

      og_role_grant_permissions($rid, array('access content overview', 'administer files', 'administer comments'));
    }
  }
}
