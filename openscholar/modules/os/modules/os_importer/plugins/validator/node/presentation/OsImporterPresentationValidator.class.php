<?php

/**
 * required title
 * validate date
 */
class OsImporterPresentationValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_links_link__url'] = array (
      'validators' => array(
        'validatorUrlNotEmpty',
      ),
    );

    return $fields;
  }
}
