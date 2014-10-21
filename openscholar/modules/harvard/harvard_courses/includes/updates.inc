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
    $wrapper = entity_metadata_wrapper('node', $entity);
    $sites = array();

    // Import by catalog number.
    if ($wrapper->field_cat_number->value()) {
      $sites += haravard_courses_sites_nid_by_import_type('cat_num', array($wrapper->field_cat_number->value()));
    }
    // Import by department ID and school name.
    if ($wrapper->field_department_id->value() && $wrapper->field_faculty->value()) {
      $sites += haravard_courses_sites_nid_by_import_type('department_school', array(
        'field_department_id' => $wrapper->field_department_id->value(),
        'field_faculty' => $wrapper->field_faculty->value(),
      ));
    }
    $wrapper->{OG_AUDIENCE_FIELD}->set($sites);
    $wrapper->save();
  }
}
