<?php

class LayoutsValidate extends OsObjectValidate {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['blocks'] = array(
      'property' => 'blocks',
      'required' => TRUE,
    );

    $fields['object_id'] = array(
      'property' => 'object_id',
      'required' => TRUE,
    );

    return $fields;
  }
}
