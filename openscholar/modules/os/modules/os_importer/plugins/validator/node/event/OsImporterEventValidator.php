<?php

class OsImporterEventValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_date'] = array(
      'validators' => array(
        'validateOsDate',
      ),
    );

    $fields['registration'] = array (
      'validators' => array(
        'isNotEmpty',
        'validateSignUp',
      ),
    );
  }

  /**
   * Verify the start is occurring before the end date.
   */
  public function validateOsDate($field_name, $value) {
    // Validate the date format for the start and end date.
    if (!DateTime::createFromFormat('M j Y', $value['start'])) {
      $params = array(
        '@date' => $value['start'],
        '@format' => date('M j Y'),
      );
      $this->setError($field_name, 'The start date, @date, is not valid. The date should be in a format similar to @format', $params);
    }

    // Validate the end date is after the start date.
    if (!DateTime::createFromFormat('M j Y', $value['end'])) {
      $params = array(
        '@date' => $value['end'],
        '@format' => date('M j Y'),
      );
      $this->setError($field_name, 'The start date, @date, is not valid. The date should be in a format similar to @format', $params);
    }
  }

  /**
   * Verify the value of the sign up is one of the: true, false, on, off.
   */
  public function validateSignUp($field_name_name, $value) {
    if (empty($value)) {
      return;
    }

    $values = array('true', 'false', 'on', 'off');
    if (!in_array($value, $values)) {
      $params = array(
        '@value' => $value,
        '@values' => $values,
      );

      $this->setError($field_name_name, 'The field value(@value) should be of of the next values: @values', $params);
    }
  }
}
