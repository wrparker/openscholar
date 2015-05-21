<?php

class PersonNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/person/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Person
   *
   * @apiDescription Consume person content.
   *
   * @apiParam {Number} id The person ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['address'] = array(
      'property' => 'field_address',
    );

    $public_fields['email'] = array(
      'property' => 'field_email',
    );

    $public_fields['first_name'] = array(
      'property' => 'field_first_name',
    );

    $public_fields['middle_name'] = array(
      'property' => 'field_middle_name_or_initial',
    );

    $public_fields['last_name'] = array(
      'property' => 'field_last_name',
    );

    $public_fields['phone'] = array(
      'property' => 'field_phone',
    );

    $public_fields['prefix'] = array(
      'property' => 'field_prefix',
    );

    $public_fields['professional_title'] = array(
      'property' => 'field_professional_title',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/person Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Person
   *
   * @apiDescription Create a person entry.
   *
   * @apiParam {Number} id The person ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/person/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Person
   *
   * @apiDescription Create a person entry.
   *
   * @apiParam {Number} id The person ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/person/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Person
   *
   * @apiDescription Create a person entry.
   *
   * @apiParam {Number} id The person ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
