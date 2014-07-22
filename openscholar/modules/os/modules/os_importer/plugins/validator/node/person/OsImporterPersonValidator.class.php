<?php

/**
 * @file
 * Contains OsImporterPersonValidator
 */
class OsImporterPersonValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['person_photo'] = array(
      'validators' => array(
        'validatorPersonPhoto',
      ),
    );

    return $fields;
  }

  /**
   * Validating the image is in 220X220.
   */
  public function validatorPersonPhoto($field_name, $value) {
    // Allow empty photo.
    if (empty($value)) {
      return;
    }

    $this->validatorPhoto($field_name, reset($value), 220, 220);
  }

  /**
   * Overriding parent:isNotEmpty().
   *
   * The person node don't need title by default since the title is generated
   * from the first\last\middle name and we already verifying the titles.
   */
  public function isNotEmpty($field_name, $value) {
    if ($field_name == 'title') {
      return;
    }

    parent::isNotEmpty($field_name, $value);
  }
}
