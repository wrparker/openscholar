<?php

class OsImporterClassValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    $field_names = parent::setFieldsInfo();

    $field_names['field_semester'] = array(
      'validators' => array(
        'isNotEmpty',
        'validationSemester',
      ),
    );

    $field_names['field_offered_year'] = array(
      'validators' => array(
        'isNotEmpty',
        'validateOfferedYear',
      ),
    );
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

  /**
   * Preprocess the offered year and preprocess the form on the way.
   */
  function validateOfferedYear($field_name_name,$value) {
    if (!is_numeric($value['start']) || (is_numeric($value['start']) && $value['start'] > 9999)) {
      $params = array(
        '@value' => $value['start'],
      );

      $this->setError($field_name_name, 'The value for the year field is not valid value(@value). The value should be a year.', $params);
    }
  }
}
