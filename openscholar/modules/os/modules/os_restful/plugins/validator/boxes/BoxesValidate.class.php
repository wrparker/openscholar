<?php

class BoxesValidate extends OsObjectValidate {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = parent::publicFieldsInfo();

    $fields['widget'] = array(
      'property' => 'widget',
      'required' => TRUE,
    );

    $fields['options'] = array(
      'property' => 'options',
      'validators' => array(
        array($this, 'validateOptions'),
      ),
    );

    $fields['delta'] = array(
      'property' => 'delta',
      'validators' => array(
        array($this, 'validateDelta'),
      ),
    );

    return $fields;
  }

  /**
   * Validate the option attribute,
   */
  public function validateOptions($property, $value, $object) {
    if (empty($value['description'])) {
      $this->setError($property, 'The description is missing in the widgets settings.');
    }
  }

  /**
   * Verify we got delta when we updating a box.
   */
  public function validateDelta($property, $value, $object) {
    if (!property_exists($object, 'new')) {
      return;
    }

    if ($object->new) {
      return;
    }

    if (empty($object->delta)) {
      $this->setError($property, 'You need pass a delta for existing box.');
    }
  }
}
