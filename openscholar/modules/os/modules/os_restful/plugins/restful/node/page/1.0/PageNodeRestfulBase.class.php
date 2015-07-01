<?php

class PageNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/page/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Page
   *
   * @apiDescription Consume page content.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSuccess {Number}   id              The publication ID.
   * @apiSuccess {String}   label           Registration Date.
   * @apiSuccess {Object}   vsite           The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body            The body of the publication.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   * @apiSuccess {Integer}  path            The page of the page.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['path'] = array(
      'property' => 'path',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/page Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Page
   *
   * @apiDescription Create a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/page/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Page
   *
   * @apiDescription Delete a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/page/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Page
   *
   * @apiDescription Update a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }


}
