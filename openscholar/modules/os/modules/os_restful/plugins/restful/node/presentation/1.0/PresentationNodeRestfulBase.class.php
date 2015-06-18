<?php

class PresentationNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/presentation/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Presentation
   *
   * @apiDescription Consume presentation content.
   *
   * @apiParam {Number} id The presentation ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['date'] = array(
      'property' => 'field_presentation_date',
      'process_callbacks' => array(
        array($this, 'dateProcess'),
      ),
    );

    $public_fields['location'] = array(
      'property' => 'field_presentation_location',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/presentation Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Presentation
   *
   * @apiDescription Update a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/presentation/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Presentation
   *
   * @apiDescription Delete a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/presentation/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Presentation
   *
   * @apiDescription Update a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
