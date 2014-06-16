<?php

/**
 * Required title, # of columns, # of rows
 * Validate # of column, # of rows (must be positive integer)
 */
class OsImporterMediaGalleryValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['media_gallery_rows'] = $fields['media_gallery_columns'] = array(
      'validators' => array(
        'validateRowsColumns'
      ),
    );

    return $fields;
  }

  public function validateRowsColumns($field_name, $value) {
    if (empty($value) || $value < 1) {
      $params['@value'] = $value;
      $this->setError($field_name, 'The field @field positive. The given value is: @value.', $params);
    }
  }
}
