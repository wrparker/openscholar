<?php

class HarvardCoursesUpdate7004 {

  public static function query($id = NULL) {
    $query = new EntityFieldQuery();

    $query
      ->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'harvard_course');

    if ($id) {
      $query->propertyCondition('nid', $id, '>=');
    }

    return $query;

  }

  public static function iterator($entity) {
    dpm($entity);
  }
}
