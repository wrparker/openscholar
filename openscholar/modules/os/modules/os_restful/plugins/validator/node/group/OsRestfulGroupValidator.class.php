<?php

class OsRestfulGroupValidator extends EntityValidateBase {

  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    FieldsInfo::setFieldInfo($fields['purl'], $this)
      ->setRequired()
      ->addCallback('validateSinglePurl');

    return $fields;
  }

  public function validateSinglePurl($field_name, $value) {
    $this->setError($field_name, 'foo');
  }

}
