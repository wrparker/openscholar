<?php

/**
 * Setting the year for the field.
 *
 * @param EntityMetadataWrapper $field_wrapper
 *  The field wrapped with entity metadata wrapper.
 * @param $target
 *  The key target.
 * @param $value
 *  The value.
 * @param EntityValidateInterface $handler
 *  The validator handler. Used for setting errors.
 */
function _os_importer_map_default(EntityMetadataWrapper $field_wrapper, $target, $value, EntityValidateInterface $handler) {
  $field_value = !is_array($field_wrapper->value()) ? array($value) : $field_wrapper->value();
  $field_value[$target] = $field_value;
  $field_wrapper->set($field_value);
}

/**
 * Setting the year for the field.
 *
 * @see os_importer_map_default();
 */
function _os_importer_map_field_offered_year(EntityMetadataWrapper $field_wrapper, $target, $value, EntityValidateInterface $handler) {
  $handler->validateOfferedYear('field_offered_year', $value);
}
