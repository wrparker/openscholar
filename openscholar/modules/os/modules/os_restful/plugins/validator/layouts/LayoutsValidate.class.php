<?php

class LayoutsValidate extends OsObjectValidate {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    FieldsInfo::setFieldInfo($fields['blocks'], $this)
      ->setProperty('blocks')
      ->setRequired();

    FieldsInfo::setFieldInfo($fields['context'], $this)
      ->setProperty('context')
      ->setRequired();

    return $fields;
  }

}
