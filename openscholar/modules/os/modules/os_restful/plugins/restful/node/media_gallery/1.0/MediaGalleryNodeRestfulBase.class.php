<?php

class MediaGalleryNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/media_gallery/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Media gallery
   *
   * @apiDescription Consume media gallery content.
   *
   * @apiParam {Number} id The media gallery ID.
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
   * @apiSuccess {Integer}  rows            Number of rows for the images.
   * @apiSuccess {Integer}  columns         Number of columns for the images.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    // Body field Isn't attached.
    unset($public_fields['body']);

    $public_fields['columns'] = array(
      'property' => 'media_gallery_columns',
    );

    $public_fields['rows'] = array(
      'property' => 'media_gallery_rows',
    );

    $public_fields['files'] = array(
      'property' => 'media_gallery_file',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/media_gallery Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Media gallery
   *
   * @apiDescription Create a media gallery entry.
   *
   * @apiParam {Number} id The media gallery ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/media_gallery/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Media gallery
   *
   * @apiDescription Delete a media gallery entry.
   *
   * @apiParam {Number} id The media gallery ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/media_gallery/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Media gallery
   *
   * @apiDescription Update a media gallery entry.
   *
   * @apiParam {Number} id The media gallery ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
