<?php

class BlogNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/blog/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Blog
   *
   * @apiDescription Consume blog content.
   *
   * @apiParam {Number} id The blog ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/blog Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Blog
   *
   * @apiDescription Create a Blog entry.
   *
   * @apiParam {Number} id The blog ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/blog/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Blog
   *
   * @apiDescription Create a Blog entry.
   *
   * @apiParam {Number} id The blog ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/blog/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Blog
   *
   * @apiDescription Create a Blog entry.
   *
   * @apiParam {Number} id The blog ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
