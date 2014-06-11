<?php
class OsImporterLinkValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_links_link'] = array(
      'validator' => array(
        'validatorUrlNotEmpty',
      ),
    );

    return $fields;
  }

  /**
   * Validating the url field is not empty. We can't use the isNotEmpty since
   * the field is array with keys.
   */
  public function validatorUrlNotEmpty($field_name, $value) {
    if (empty($value['url'])) {
      $this->setError($field_name, 'You must supply a link with URL.');
    }
  }
}
