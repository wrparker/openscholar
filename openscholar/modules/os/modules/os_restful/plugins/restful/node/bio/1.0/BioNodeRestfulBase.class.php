<?php

class BioNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/bio/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Biography
   *
   * @apiDescription Consume publications content.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSuccess {Number}   id              The publication ID.
   * @apiSuccess {String}   label           Registration Date.
   * @apiSuccess {Object[]} vsite           The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body            The body of the publication.
   * @apiSuccess {Object[]} files           The attached files.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/bio Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Biography
   *
   * @apiDescription Create a Biography entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Integer}  vsite     The vsite ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer[]} files    List of file IDs.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/bio/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Biography
   *
   * @apiDescription Create a Biography entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/bio/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Biography
   *
   * @apiDescription Create a Biography entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Integer}  vsite     The vsite ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer[]} files    List of file IDs.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
