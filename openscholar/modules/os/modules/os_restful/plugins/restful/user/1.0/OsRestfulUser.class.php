<?php

/**
 * @file
 * Contains RestfulEntityUser__1_0.
 */

class OsRestfulUser extends \RestfulEntityBaseUser {

  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['name'] = array(
      'property' => 'name',
    );

    return $public_fields;
  }
}
