<?php

class SoftwareProjectNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/software_project/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Software project
   *
   * @apiDescription Consume software project content.
   *
   * @apiParam {Number} id The software project ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/software_project Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Software project
   *
   * @apiDescription Create a software project entry.
   *
   * @apiParam {Number} id The software project ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/software_project/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Software project
   *
   * @apiDescription Delete a software project entry.
   *
   * @apiParam {Number} id The software project ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/software_project/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Software project
   *
   * @apiDescription Update a software project entry.
   *
   * @apiParam {Number} id The software project ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
