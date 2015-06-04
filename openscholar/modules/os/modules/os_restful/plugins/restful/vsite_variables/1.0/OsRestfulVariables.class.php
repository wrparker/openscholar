<?php

/**
 * @file
 * Contains \OsRestfulLayout
 */

class OsRestfulVariables extends OsRestfulSpaces {

  protected $validateHandler = 'variables';
  protected $objectType = 'variable';

  /**
   * Verify the user have access to the manage layout.
   */
  public function checkGroupAccess() {
    parent::checkGroupAccess();
    $account = $this->getAccount();

    if (user_access('administer group', $account)) {
      return;
    }

    if ($this->group->author->getIdentifier() != $account->uid) {
      // The current user can't manage boxes.
      $this->throwException('You are not authorised to manage the variables of the group.');
    }
  }

  /**
   * @api {patch} api/variables Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Variables
   *
   * @apiDescription Update a variable in a vsite.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Integer}  vsite       Registration Date.
   * @apiSuccess {String}   object_id   The variable name.
   * @apiSuccess {Mixed}    value       The body of the publication.
   */
  public function updateSpace() {
    return $this->createUpdateVariable();
  }

  /**
   * @api {post} api/variables Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Variables
   *
   * @apiDescription Create a variable in a vsite.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Integer}  vsite       Registration Date.
   * @apiSuccess {String}   object_id   The variable name.
   * @apiSuccess {Mixed}    value       The body of the publication.
   */
  public function createSpace() {
    return $this->createUpdateVariable();
  }

  /**
   * Create and update are the same actions. Invoke them in a single method
   * that will be invoked by the rest call type.
   */
  private function createUpdateVariable() {
    // Check group access.
    $this->checkGroupAccess();

    $controller = $this->space->controllers->{$this->objectType};
    $controller->set($this->object->object_id, $this->object->value);
    return array(
      'name' => $this->object->object_id,
      'value' => $controller->get($this->object->object_id),
    );
  }

  /**
   * @api {delete} api/variables Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Variables
   *
   * @apiDescription Delete a variable in a vsite.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Integer}  vsite       Registration Date.
   * @apiSuccess {String}   object_id   The variable name.
   */
  public function deleteSpace() {
    // Check group access.
    $this->checkGroupAccess();

    $controller = $this->space->controllers->{$this->objectType};
    $controller->del($this->object->object_id);
  }

  /**
   * @api {get} api/variables/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Variables
   *
   * @apiDescription Get a variable from a vsite.
   *
   * @apiSuccess {String}   type         The space override type.
   * @apiSuccess {Integer}  id           The space override ID.
   * @apiSuccess {Mixed}    object_id    The space override object ID.
   * @apiSuccess {Mixed}    object_type  The space override object type.
   * @apiSuccess {Mixed}    value        The space override value.
   */
  public function getSpace() {
    return parent::getSpace();
  }
}
