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
      'property' => 'roles',
      'process_callbacks' => array(
        array($this, 'getRoles'),
      ),
    );

    $public_fields[OG_AUDIENCE_FIELD] = array(
      'property' => OG_AUDIENCE_FIELD,
      'process_callbacks' => array(
        array($this, 'vsiteFieldDisplay'),
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

  /**
   * Refactor the roles property with rid-name format.
   */
  public function getRoles($roles) {
    $return = array();
    foreach ($roles as $role) {
      $info = user_role_load($role);
      $return[$info->rid] = $info->name;
    }
    return $return;
  }

  /**
   * Display the id and the title of the group.
   */
  public function vsiteFieldDisplay($values) {
    $groups = array();
    foreach ($values as $value) {
      $groups[] = array('title' => $value->title, 'id' => $value->nid);
    }
    return $groups;
  }
}
