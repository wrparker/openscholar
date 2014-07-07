<?php

/**
 * @file
 * Contains OsBiosResource.
 */

class OsBiosResource extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulEntityBase::getPublicFields().
   */
  public function getPublicFields() {
    $public_fields = parent::getPublicFields();

    $public_fields['description'] = array(
      'property' => 'body',
      'sub_property' => 'value',
    );

    return $public_fields;
  }
}
