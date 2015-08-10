<?php

class LinkNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/link/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Link
   *
   * @apiDescription Consume link content.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSuccess {Number}   id              The publication ID.
   * @apiSuccess {String}   label           Registration Date.
   * @apiSuccess {Object} vsite           The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body            The body of the publication.
   * @apiSuccess {Object} files           The attached files.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['url'] = array(
      'property' => 'field_links_link',
      'sub_property' => 'url',

    );

    return $public_fields;
  }

  /**
   * @api {post} api/link Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Link
   *
   * @apiDescription Update a link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/link/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Link
   *
   * @apiDescription Delete a link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/link/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Link
   *
   * @apiDescription Update a Link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
