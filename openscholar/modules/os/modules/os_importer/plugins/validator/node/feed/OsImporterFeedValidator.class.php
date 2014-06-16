<?php

class OsImporterFeedValidator extends OsImporterEntityValidateBase {

  public function setFieldsInfo() {
    $fields = parent::setFieldsInfo();

    $fields['field_url__url'] = array(
      'validators' => array(
        'validatorUrlNotEmpty',
      ),
    );

    return $fields;
  }
}
