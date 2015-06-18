<?php

/**
 * @file
 * Contains RestfulEntityUser__1_0.
 */

class OsRestfulUser extends \RestfulEntityBaseUser {

  /**
   * @api {get} api/users/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup User
   *
   * @apiDescription Consume the user entity.
   *
   * @apiParam {Integer} id The ID of the user
   */
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
   *
   * @api {post} api/users Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup User
   *
   * @apiDescription Create a user entity.
   *
   * @apiParam {String} name The name of the user
   * @apiParam {String} mail The email of the user
   * @apiParam {String} password The password for the user
   * @apiParam {Integer} role The role ID of the user
   * @apiParam {Integer} vsite The VSite of the user
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');
    return parent::createEntity();
  }

  /**
   * @api {patch} api/users/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup User
   *
   * @apiDescription Update a user entity.
   *
   * @apiParam {String} name The name of the user
   * @apiParam {String} mail The email of the user
   * @apiParam {String} password The password for the user
   * @apiParam {Integer} role The role ID of the user
   * @apiParam {Integer} vsite The VSite of the user
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
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
