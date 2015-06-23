<?php

class BookNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/book/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Book
   *
   * @apiDescription Consume book content.
   *
   * @apiParam {Number} id The book ID
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
   * @api {post} api/book Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Book
   *
   * @apiDescription Create a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/book/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Book
   *
   * @apiDescription Delete a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/book/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Book
   *
   * @apiDescription Update a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
