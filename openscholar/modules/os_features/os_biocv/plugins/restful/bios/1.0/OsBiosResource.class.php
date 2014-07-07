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

    $public_fields['site_name'] = array(
      'property' => 'og_group_ref',
      'sub_property' => 'title',
      'process_callback' => array($this, 'processGroup'),
    );

    return $public_fields;
  }

  /**
   * Process callback; Returns the title of the VSite this bio belongs to.
   */
  protected function processGroup($value) {
    $site_name = $value[0];
    return $site_name;
  }
}
