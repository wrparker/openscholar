<?php

class SlideshowSlideNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/slideshow_slide/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Slideshow
   *
   * @apiDescription Consume slideshow content.
   *
   * @apiParam {Number} id The slideshow ID
   *
   * @apiSuccess {Number}   id            The slideshow ID.
   * @apiSuccess {String}   label         Label of the slideshow.
   * @apiSuccess {String}   self          The direct URl of the current end point.
   * @apiSuccess {object}   vsite         The vsite object
   * @apiSuccess {String}   vsite.title   Group name.
   * @apiSuccess {Integer}  vsite.id      Group ID.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    unset($public_fields['body']);

    return $public_fields;
  }

  /**
   * @api {post} api/slideshow_slide Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Slideshow
   *
   * @apiDescription Create a Slideshow entry.
   *
   * @apiParam {Number} id The slideshow ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/slideshow_slide/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Slideshow
   *
   * @apiDescription Delete a Slideshow entry.
   *
   * @apiParam {Number} id The slideshow ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/slideshow_slide/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Slideshow
   *
   * @apiDescription Update a Slideshow entry.
   *
   * @apiParam {Number} id The slideshow ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
