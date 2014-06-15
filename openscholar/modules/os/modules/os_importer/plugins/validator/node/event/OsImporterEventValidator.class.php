<?php

class OsImporterEventValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

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
    // Validate the date format for the start and end date.
    if (!DateTime::createFromFormat('M j Y', $value)) {
      $params = array(
        '@date' => $value,
        '@format' => date('M j Y'),
      );
      $this->setError($field_name, 'The start date, @date, is not valid. The date should be in a format similar to @format', $params);
    }
  }

  /**
   * Verify the value of the sign up is one of the: true, false, on, off.
   */
  public function validateSignUp($field_name, $value) {
    if (empty($value)) {
      return;
    }

    $values = array('true', 'false', 'on', 'off');
    if (!in_array($value, $values)) {
      $params = array(
        '@value' => $value,
        '@values' => $values,
      );

      $this->setError($field_name, 'The field value(@value) should be of of the next values: @values', $params);
    }
  }
}
