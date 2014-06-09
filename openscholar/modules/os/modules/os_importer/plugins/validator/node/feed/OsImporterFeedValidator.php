<?php

class OsImporterFeedValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() + array(
      'field_url' => array(
        'validators' => array(
          array($this, 'validatorUrlNotEmpty')
        ),
      ),
    );
  }

  /**
   * Validating the url field is not empty. We can't use the isNotEmpty since
   * the field is array with keys.
   *
   * @param $value
   *  The field value.
   * @param $field
   *  The field name.
   */
  public function validatorUrlNotEmpty($value, $field) {
    if (empty($value['url'])) {
      $this->setError(t('The url of the feed is empty. Please supply a feeds with URL.'));
    }
  }
}
