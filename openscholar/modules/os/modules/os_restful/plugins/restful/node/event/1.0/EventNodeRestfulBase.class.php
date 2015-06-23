<?php

class EventNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/event/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Event
   *
   * @apiDescription Consume event content.
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
   * @apiSuccess {Integer}  start_date      Event start date.
   * @apiSuccess {Integer}  end_date        Event end date.
   * @apiSuccess {Integer}  field_event_registration The event type. Use
   * event_signup_simple. Populate with [registration_type: event_signup_simple]
   *
   * @apiParam {Number} id The event ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['start_date'] = array(
      'property' => 'field_date',
      'sub_property' => 'value',
      'process_callbacks' => array(
        array($this, 'dateProcess'),
      ),
    );

    $public_fields['end_date'] = array(
      'property' => 'field_date',
      'sub_property' => 'value2',
      'process_callbacks' => array(
        array($this, 'dateProcess'),
      ),
    );

    $public_fields['field_event_registration'] = array(
      'property' => 'field_event_registration',
    );

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   */
  public function entityPreSave(\EntityMetadataWrapper $wrapper) {
    parent::entityPreSave($wrapper);
    $request = $this->getRequest();
    $date = $wrapper->field_date->value();
    $format = 'Y-m-d h:i:s';
    if (!empty($request['start_date'])) {
      $date[0]['value'] = date($format, strtotime($request['start_date']));
    }

    $date[0]['value2'] = empty($request['end_date']) ? $date[0]['value'] : date($format, strtotime($request['end_date']));

    $wrapper->field_date->set($date);
  }

  /**
   * @api {post} api/event Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Event
   *
   * @apiDescription Create a event entry.
   *
   * @apiParam {Number} id The event ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/event/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Event
   *
   * @apiDescription Delete a event entry.
   *
   * @apiParam {Number} id The event ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/event/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Event
   *
   * @apiDescription Update a event entry.
   *
   * @apiParam {Number} id The event ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
