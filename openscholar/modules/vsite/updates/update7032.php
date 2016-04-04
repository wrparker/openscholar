<?php

/**
 * Contains static methods for vsite update 7032.
 */
class update extends AbstractUpdate {

  /**
   * @inheritdoc
   */
  public static function Query($id = NULL) {

    $query = self::getBaseQuery();
    $query
      ->fieldCondition('og_roles_permissions', 'value', TRUE);

    if ($id) {
      $query->propertyCondition('nid', $id, '>=');
    }

    return $query;
  }

  /**
   * @inheritdoc
   */
  public static function Iterator($entity) {
    self::assignRoles($entity, self::$nobelRoles, array('add content to books'));
  }

}
