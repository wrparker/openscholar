<?php

/**
 * @file
 * Contains  OsImporterEventValidator
 */
class OsImporterEventValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_date__start'] = array (
      'validators' => array(
        'isNotEmpty',
        'validateOsDate',
      ),
    );

    $fields['field_date__end'] = array (
      'validators' => array(
        'isNotEmpty',
        'validateOsDate',
      ),
    );

    $fields['registration'] = array (
      'validators' => array(
        'isNotEmpty',
        'validateSignUp',
      ),
    );

    return $fields;
  }

  /**
   * Verify the start is occurring before the end date.
   */
  public function validateOsDate($field_name, $value) {
    $value = reset($value);
    // Validate the date format for the start and end date.
    $date = DateTime::createFromFormat('M j Y', $value);

    if ($date && $date->format('M j Y') == $value) {
      return;
    }

    $params = array(
      '@date' => $value,
      '@format' => date('M j Y'),
    );
    $this->setError($field_name, 'The start date, @date, is not valid. The date should be in a format similar to @format', $params);
  }

  /**
   * Verify the value of the sign up is one of the: true, false, on, off.
   */
  public function validateSignUp($field_name, $value) {
    if (empty($value)) {
      return;
    }
    $value = reset($value);

    $values = array('true', 'false', 'on', 'off');
    if (!in_array($value, $values)) {
      $params = array(
        '@value' => $value,
        '@values' => implode(", ", $values),
      );

      $this->setError($field_name, 'The field value(@value) should be of of the next values: @values', $params);
    }
  }
}
