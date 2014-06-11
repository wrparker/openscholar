<?php

/**
 * required title
 * validate date
 */
class OsImporterPresentationValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_links_link'] = array (
      'validator' => array(
        'validatorUrlNotEmpty',
      ),
    );

    return $fields;
  }
}
