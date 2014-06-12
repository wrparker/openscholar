<?php

class OsImporterClassValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    $field = parent::setFieldsInfo();

    $field['field_semester'] = array(
      'validators' => array(
        'isNotEmpty',
        'validationSemester',
      ),
    );

    return $field;
  }

  /**
   * Validate the semester field.
   */
  function validationSemester($field_name_name, $value) {
    $info = field_info_field($field_name_name);

    // Validate the semester.
    $allowed_values = $info['settings']['allowed_values'] + array('N/A' => 'N/A');
    if (!in_array($value, $allowed_values)) {
      $params = array(
        '@allowed-values' => implode(', ', $allowed_values),
        '@value' => $value,
      );

      $this->setError($field_name_name, 'The given value(@value) is not validate value for the semester field and should be one of @allowed-values', $params);
    }
  }
}
