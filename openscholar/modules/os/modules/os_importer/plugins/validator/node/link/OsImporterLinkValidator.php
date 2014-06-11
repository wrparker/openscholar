<?php
class OsImporterLinkValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    return parent::setFieldsInfo() + array(
      'field_links_link' => array(
        'validator' => array(
          array($this, 'validatorUrlNotEmpty'),
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
      $this->setError(t('You must supply a link with URL.'));
    }
  }
}
