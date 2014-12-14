<?php

class SpacesOverridesValidate extends ObjectValidateBase {

  /**
   * Overrides ObjectValidateBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $fields = array();

    $fields['vsite'] = array(
      'property' => 'id',
      'validators' => array(
        array($this, 'validateVsite'),
      ),
    );

    $fields['options'] = array(
      'property' => 'options',
      'validators' => array(
        array($this, 'validateOptions'),
      ),
    );

    return $fields;
  }

  /**
   * Validate the user passed a vsite.
   */
  public function validateVsite($property, $value, $object) {
    if (empty($object->vsite)) {
      $this->setError('vsite', 'You need to pass vsite ID.');
    }
  }

  /**
   * Validate the option attribute,
   */
  public function validateOptions($property, $value, $object) {
    if (empty($value['description'])) {
      $this->setError($property, 'The description is missing in the widgets settings.');
    }
  }
}
