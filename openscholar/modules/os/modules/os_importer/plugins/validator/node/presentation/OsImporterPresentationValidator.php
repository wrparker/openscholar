<?php

/**
 * required title
 * validate date
 */
class OsImporterPresentationValidator extends NodeValidate {

  public function getFieldsInfo() {
    return parent::getFieldsInfo() + array(
      'field_links_link' => array(
        'validator' => array(
          array($this, 'validatorUrlNotEmpty'),
        ),
      ),
    );
  }
}
