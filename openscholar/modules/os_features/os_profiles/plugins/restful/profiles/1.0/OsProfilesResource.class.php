<?php

/**
 * @file
 * Contains OsProfilesResource.
 */

class OsProfilesResource extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulEntityBase::getPublicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();
    
    $public_fields['photo'] = array(
      'property' => 'field_person_photo',
      'process_callback' => array($this, 'processPhoto'),
    );
    return $public_fields;
  }

  /**
   * Process callback; Return the URI value of an image.
   */
  protected function processPhoto($value) {
    return $value['uri'];
  }
}
