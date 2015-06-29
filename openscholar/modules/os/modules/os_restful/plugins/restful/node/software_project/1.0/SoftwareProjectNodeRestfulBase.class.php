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
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Object}   vsite     The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   * @apiSuccess {String}   Date            The presentation date.
   * @apiSuccess {String}   location        The location of the presentation.
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
