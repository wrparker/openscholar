<?php

class OsImporterClassValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() +  array(
      'body' => array(
        'preprocess' => array(
          array($this, 'preprocessText'),
        ),
      ),
      '_field_semester' => array(
        'validators' => array(
          array($this, 'preprocessText'),
        ),
      ),
    );
  }

  public function validate() {
    $fields = $this->getFields();

    $fields['body'] = array('value' => $fields['body']);

    $info = field_info_field('field_semester');

    // Validate the semester.
    $allowed_values = $info['settings']['allowed_values'] + array('N/A' => 'N/A');
    if (!in_array($fields['field_semester'], $allowed_values)) {
      $params = array(
        '@allowed-values' => implode(', ', $allowed_values),
        '@value' => $fields['field_semester'],
      );

      $this->setError(t('The given value(@value) is not validate and should be one of @allowed-values', $params));
    }

    // Validate the year.
    $year = $fields['field_offered_year:start'];
    if (!is_numeric($year) || (is_numeric($year) && $year > 9999)) {
      $params = array(
        '@value' => $year,
      );

      $this->setError(t('The year field is not valid value(@value). the value should be a year.', $params));
    }

    $this->setFields($fields);

    parent::validate();
  }
}
