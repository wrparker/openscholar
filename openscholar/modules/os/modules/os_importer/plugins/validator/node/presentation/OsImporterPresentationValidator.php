<?php

/**
 * required title
 * validate date
 */
class OsImporterPresentationValidator extends EntityValidateBase {

  public function setFieldsInfo() {
    return parent::setFieldsInfo() + array(
      'field_links_link' => array(
        'validator' => array(
          array($this, 'validatorUrlNotEmpty'),
        ),
      ),
    );
  }
}
