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
      'validators' => array(
        array($this, 'checkExistedDelta')
      ),
    );

    return $fields;
  }

  public function checkExistedDelta($property, $value, $object) {
    $this->setError($property, 'Foo!');
  }
}
