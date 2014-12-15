<?php

/**
 * @file
 * Contains \RestfulQueryVariable
 */

class OsRestfulSpacesOverrides extends \RestfulDataProviderDbQuery implements \RestfulDataProviderDbQueryInterface, \RestfulDataProviderInterface {

  /**
   * Overrides \RestfulDataProviderEFQ::controllersInfo().
   */
  public static function controllersInfo() {
    return array(
      '' => array(
        \RestfulInterface::GET => 'getSpace',
        \RestfulInterface::POST => 'createSpace',
        \RestfulInterface::PUT => 'updateSpace',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function publicFieldsInfo() {
    return $this->simpleFieldsInfo(array('sid', 'type', 'id', 'object_id', 'object_type', 'value'));
  }

  /**
   * Simple fields mapping.
   *
   * @param array $fields
   *   The list of the properties.
   * @return array
   *   List of the schema fields.
   */
  public function simpleFieldsInfo($fields = array()) {
    $return = array();
    foreach ($fields as $field) {
      $return[$field] = array('property' => $field);
    }

    return $return;
  }

  /**
   * Throwing exception easily.
   * @param $message
   *   The exception message.
   * @throws RestfulBadRequestException
   */
  public function throwException($message) {
    throw new RestfulBadRequestException($message);
  }

  /**
   * un-serialize the value object.
   */
  public function mapDbRowToPublicFields($row) {
    $row->value = unserialize($row->value);
    return parent::mapDbRowToPublicFields($row);
  }

  /**
   * Verify the user's request has access CRUD in the current group.
   */
  public function checkGroupAccess() {
    // todo: Handle.
    return TRUE;
  }

  /**
   * Override the list method in order to return a specific delta from the
   * space override.
   */
  public function getSpace() {
    $list = parent::index();

    $request = $this->getRequest();

    if (!empty($request['type']) && !empty($list[0]['value'][$request['type']])) {
      // We need a sub value from the space.
      $sub_value = $list[0]['value'][$request['type']];

      if (!empty($request['delta']) && !empty($sub_value[$request['delta']])) {
        // We need a specif delta from the sub value.
        return $sub_value[$request['delta']];
      }

      return $sub_value;
    }

    return $list;
  }

  /**
   * Updating a given space override.
   */
  public function updateSpace() {
    if (!$this->checkGroupAccess()) {
      $this->throwException('You are not authorised.');
    }

    $request = $this->getRequest();
    $space = spaces_load('og', $request['filter']['sid']);
    $controller = $space->controllers->{$request['filter']['object_type']};
    $settings = $controller->get($request['delta']);
    $new_settings = array_merge((array) $settings, $request['settings']);
    $controller->set($request['delta'], (object) $new_settings);
  }

  /**
   * Validate object.
   *
   * @throws RestfulBadRequestException
   *   Throws exception with the error per object.
   * @return stdClass
   *   The clean request object convert ot a std object.
   */
  public function validate() {
    // Get the clean request.
    $request = $this->getRequest();
    static::cleanRequest($request);
    $object = (object) $request;

    $handler = entity_validator_get_schema_validator('spaces_overrides');
    $result = $handler->validate($object, TRUE);

    $errors_output = array();
    if (!$result) {
      $e = new \RestfulBadRequestException;
      $fields_errors = $handler->getErrors(FALSE);
      foreach ($fields_errors as $field => $errors) {

        foreach ($errors as $error) {
          $errors_output[$field][] = format_string($error['message'], $error['params']);
        }

        $e->addFieldError($field, implode(', ', $errors_output[$field]));
      }

      throw $e;
    }

    return $object;
  }

  /**
   * Creating a space override.
   */
  public function createSpace() {
    if (!$this->checkGroupAccess()) {
      $this->throwException('You are not authorised.');
    }

    $object = $this->validate();

    $space = spaces_load('og', $object->vsite);

    // Set up the blocks layout.
    ctools_include('layout', 'os');
    $contexts = array(
      $object->context,
      'os_public',
    );
    $blocks = os_layout_get_multiple($contexts, FALSE, TRUE);

    if (empty($blocks[$object->widget])) {
      // Creating a new widget.
      $options = array(
        'delta' => time(),
      ) + $object->options;

      // Create the box the current vsite.
      $box = boxes_box::factory($object->widget, $options);
      $space->controllers->boxes->set($box->delta, $box);

      // Add the block to the region.
      $blocks['boxes-' . $box->delta]['region'] = $object->region;
    }
    else {
      // todo: handle when we need to add widget to a specific region.
    }

    if (!array_key_exists($blocks['boxes-' . $box->delta], array('module', 'delta'))) {
      $blocks['boxes-' . $box->delta]['delta'] = $box->delta;
      $blocks['boxes-' . $box->delta]['module'] = 'boxes';
      $blocks['boxes-' . $box->delta]['weight'] = 0;
    }

    $space->controllers->context->set($object->context . ":reaction:block", array(
      'blocks' => $blocks,
    ));
  }
}
