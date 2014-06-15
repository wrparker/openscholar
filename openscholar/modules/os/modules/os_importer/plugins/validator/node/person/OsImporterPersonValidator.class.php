<?php

/**
 * Contains OsImporterPersonValidator
 */
class OsImporterPersonValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_first_name'] = array(
      'validators' => array(
        'isNotEmpty',
      ),
    );

    $fields['field_last_name'] = array(
      'validators' => array(
        'isNotEmpty',
      ),
    );

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
    list($width, $height) = getimagesize(reset($value));

    if ($width != 220 && $height != 220) {
      $params = array(
        '@width' => $width,
        '@height' => $height,
      );

      $this->setError($field_name, 'The size of the image need to be 220X220. The given image is @widthX@height', $params);
    }
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
