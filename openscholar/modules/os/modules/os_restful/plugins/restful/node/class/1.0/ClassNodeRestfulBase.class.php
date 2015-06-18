<?php

class ClassNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/class/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Class
   *
   * @apiDescription Consume class content.
   *
   * @apiParam {Number} id The class ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/class Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Class
   *
   * @apiDescription Create a class entry.
   *
   * @apiParam {Number} id The class ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/class/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Class
   *
   * @apiDescription Delete a class entry.
   *
   * @apiParam {Number} id The class ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/class/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Class
   *
   * @apiDescription Update a class entry.
   *
   * @apiParam {Number} id The class ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
