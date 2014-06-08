<?php

class OsImporterEventValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() + array(
      'field_date' => array(
        'preprocess' => array(
          array($this, 'preprocessOsDate'),
        ),
      ),
      'registration' => array(
        'validators' => array(
          array($this, 'isNotEmpty'),
          array($this, 'validateSignUp'),
        ),
      ),
    );
  }

  /**
   * Verify the start is occurring before the end date.
   */
  public function preprocessOsDate($value) {
    // Validate the date format for the start and end date.
    if (!DateTime::createFromFormat('M j Y', $value['start'])) {
      $params = array(
        '@date' => $value['start'],
        '@format' => date('M j Y'),
      );
      $this->setError(t('The start date, @date, is not valid. The date should be in a format similar to @format', $params));
    }

    // Validate the end date is after the start date.
    if (!DateTime::createFromFormat('M j Y', $value['end'])) {
      $params = array(
        '@date' => $value['end'],
        '@format' => date('M j Y'),
      );
      $this->setError(t('The start date, @date, is not valid. The date should be in a format similar to @format', $params));
    }

    return strtotime($value['start']);
  }

  /**
   * Verify the value of the sign up is one of the: true, false, on, off.
   */
  public function validateSignUp($value, $field) {
    if (empty($value)) {
      return;
    }

    $values = array('true', 'false', 'on', 'off');
    if (!in_array($value, $values)) {
      $params = array(
        '@value' => $value,
        '@values' => $values,
      );
      $this->setError(t('The field value(@value) should be of of the next values: @values', $params));
    }
  }
}
