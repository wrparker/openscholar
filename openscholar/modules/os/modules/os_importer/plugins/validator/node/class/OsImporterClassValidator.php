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
          array($this, 'SemesterValidation'),
        ),
      ),
      'field_offered_year' => array(
        'validators' => array(
          array($this, 'offeredYearValidation'),
        ),
      ),
    );
  }

  /**
   * Validate the semester field.
   *
   * @param $value
   */
  function SemesterValidation($value) {
    $info = field_info_field('field_semester');

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
   * Validating the offered year.
   *
   * @param $value
   */
  public function offeredYearValidation($value) {
    if (!is_numeric($value) || (is_numeric($value) && $value > 9999)) {
      $params = array(
        '@value' => $value,
      );

      $this->setError(t('The value for the year field is not valid value(@value). The value should be a year.', $params));
    }
  }
}
