<?php

/**
 * Contains static methods for vsite update 7031.
 */
class update extends AbstractUpdate {

  /**
   * @inheritdoc
   */
  public static function Query($id = NULL) {
    $query = self::getBaseQuery();

    if ($id) {
      $query->propertyCondition('nid', $id, '>=');
    }

    return $query;
  }

  /**
   * @inheritdoc
   */
  public static function Iterator($entity) {

    self::assignRoles($entity, array_merge(self::$nobelRoles, array('vsite user')), array('add content to books'));
    self::assignRoles($entity, self::$nobelRoles, array('administer book outlines'));
  }

}
