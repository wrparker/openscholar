<?php
/**
 * @file
 * contains OsImporterEntityValidateBase
 */

/**
 * Extending the entity validator class for plugins that just need to validate
 * the title and nothing more.
 */
class OsImporterEntityValidateBase extends EntityValidateBase {
  public function getFieldsInfo() {
    $fields = parent::getFieldsInfo();

    // Remove all validators for entityreference fields.
    foreach ($fields as $field_name => $info) {
      $field_info = field_info_field($field_name);
      $type = $field_info['type'];
      if ($type == 'entityreference') {
        unset($fields[$field_name]['validators']);
      }
    }

    return $fields;
  }
}
