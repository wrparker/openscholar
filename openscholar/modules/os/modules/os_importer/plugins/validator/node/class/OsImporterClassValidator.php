<?php

class OsImporterClassValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() +  array(
      'body' => array(
        'preprocess' => array(
          array($this, 'preprocessText'),
        ),
      ),
      'field_semester' => array(
        'validators' => array(
          array($this, 'validationSemester'),
        ),
      ),
      'field_offered_year' => array(
        'preprocess' => array(
          array($this, 'preprocessOfferedYear'),
        ),
      ),
    );
  }

  /**
   * Validate the semester field.
   *
   * @param $value
   *  The value of the field.
   * @param $field
   *  The field name.
   */
  function validationSemester($value, $field) {
    $info = field_info_field($field);

    // Validate the semester.
    $allowed_values = $info['settings']['allowed_values'] + array('N/A' => 'N/A');
    if (!in_array($value, $allowed_values)) {
      $params = array(
        '@allowed-values' => implode(', ', $allowed_values),
        '@value' => $value,
      );

      $this->setError(t('The given value(@value) is not validate value for the semester field and should be one of @allowed-values', $params));
    }
  }

  /**
   * Preprocess the offered year and preprocess the form on the way.
   */
  function preprocessOfferedYear($value) {
    if (!is_numeric($value['start']) || (is_numeric($value['start']) && $value['start'] > 9999)) {
      $params = array(
        '@value' => $value['start'],
      );

      $this->setError(t('The value for the year field is not valid value(@value). The value should be a year.', $params));
    }

    return $this->preprocessDate($value['start']);
  }
}
