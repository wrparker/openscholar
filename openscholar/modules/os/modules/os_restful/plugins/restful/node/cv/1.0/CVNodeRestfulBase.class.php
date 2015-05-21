<?php

class CVNodeRestfulBase extends OsNodeRestfulBase {
  
  /**
   * @api {get} api/cv/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup CV
   *
   * @apiDescription Consume cv content.
   *
   * @apiParam {Number} id The cv ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/cv Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup CV
   *
   * @apiDescription Create a cv entry.
   *
   * @apiParam {Number} id The cv ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/cv/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup CV
   *
   * @apiDescription Create a cv entry.
   *
   * @apiParam {Number} id The cv ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/cv/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup CV
   *
   * @apiDescription Create a cv entry.
   *
   * @apiParam {Number} id The cv ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
