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

    $public_fields['password'] = array(
      'property' => 'pass',
      'callback' => array($this, 'hideField')
    );

    $public_fields['status'] = array(
      'property' => 'status',
    );

    $public_fields['role'] = array(
      'property' => 'uid',
      'process_callbacks' => array(
        array($this, 'getRoles'),
      ),
    );

    return $public_fields;
  }

  /**
   * Hide the field value.
   *
   * @return null
   */
  protected function hideField() {
    return NULL;
  }

  /**
   * Overriding the create entity method in order to load the password.inc file.
   */
  public function createEntity() {
    require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');
    return parent::createEntity();
  }

  public function getRoles($value) {
    return user_load($value)->roles;
  }
}
