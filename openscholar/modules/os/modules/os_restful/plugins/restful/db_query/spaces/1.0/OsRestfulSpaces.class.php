<?php

/**
 * @file
 * Contains \RestfulQueryVariable
 */

class OsRestfulSpaces extends \RestfulDataProviderDbQuery implements \RestfulDataProviderDbQueryInterface, \RestfulDataProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return $this->simpleFieldsInfo(array('sid', 'type', 'id', 'object_id', 'value'));
  }

  public function simpleFieldsInfo($fields = array()) {
    $return = array();
    foreach ($fields as $field) {
      $return[$field] = array('property' => $field);
    }

    return $return;
  }
}
